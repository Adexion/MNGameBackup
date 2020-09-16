import {ClassInterface} from "../interface/class.interface";
import {Connection} from "../../library/connection";
import {ItemListInterface} from "../interface/itemList.interface";

const $ = require('jquery');

declare const window: any;

export class ItemShopService implements ClassInterface {
    private itemLists: ItemListInterface[];
    private buttonComponent: any;
    private timeout: NodeJS.Timeout;

    constructor(private connection: Connection) {
    }

    generate() {
        this.connection.get('shop/list').then(itemLists => this.itemLists = itemLists);
        let collection: HTMLCollection = document.getElementsByClassName('open-modal');

        Array.from(collection).forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event) => {
                document.querySelector('#paySafeCard-form').setAttribute('style', 'display: none;');

                let target: EventTarget = event.currentTarget;
                if (target instanceof HTMLButtonElement) {
                    let itemListId = Number(target.attributes.getNamedItem('data-item-list-id').value);
                    this.putScriptInsidePaypalContentModal(itemListId);
                }
            });
        });

        document.querySelector('#wallet').addEventListener('click', () => {
            document.querySelector('#modal .modal-dialog .modal-content .modal-body .modal-form').innerHTML = `
                <div class="form-group">
                    <label for="money">Kwota doładowania</label>
                    <input id="money" class="form-control" type="number" step="0.1" min="1" value="1"/>
                </div>
            `;

            document.querySelector('#paySafeCard-form').setAttribute('style', 'display: block;');

            this.renderPaypalButton(1, 0, this.connection);
            $('#money').bind('change keyup', (event: Event) => {
                if (this.timeout !== undefined) {
                    clearTimeout(this.timeout);
                }
                let paySafeCardInput: HTMLInputElement = document.querySelector('#paySafeCardMoney');

                if (event.target instanceof HTMLInputElement) {
                    let money = Number(event.target.value);
                    paySafeCardInput.value = event.target.value;

                    this.timeout = setTimeout(() => {
                        this.renderPaypalButton(money, 0, this.connection)
                    }, 500)
                }
            });
        });
    }

    private putScriptInsidePaypalContentModal(itemListId: number) {
        document.querySelector('#modal .modal-dialog .modal-content .modal-body .modal-form').innerHTML = '';

        let chooseItemList: ItemListInterface;
        this.itemLists.forEach((itemList: ItemListInterface) => {
            if (itemList.id === itemListId) {
                chooseItemList = itemList;
            }
        });

        if (chooseItemList !== undefined) {
            this.renderPaypalButton(
                chooseItemList.price - (chooseItemList.promotion * chooseItemList.price),
                itemListId,
                this.connection
            );
        }
    }

    private renderPaypalButton(price: number, itemListId: number, connection: Connection) {
        if (this.buttonComponent !== undefined) {
            this.buttonComponent.close();
        }

        this.buttonComponent = window.paypal.Buttons({
            createOrder: function (data: any, actions: any) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: 'PLN',
                            value: price
                        }
                    }]
                });
            },
            onApprove: async function (data: any, actions: any) {
                let details = await actions.order.capture();

                ItemShopService.redirectToStatusPage(details.id, itemListId).then();
            }
        });

        this.buttonComponent.render('#paypal-button-container');
    };

    static async redirectToStatusPage(detailsId: string, itemListId: number) {
        let form = document.createElement("form");

        form.method = "POST";
        form.action = "/paypal/status";

        const element1 = document.createElement("input");
        element1.name = "orderId";
        element1.value = detailsId;
        element1.type = 'hidden';
        form.appendChild(element1);

        const element2 = document.createElement("input");
        element2.name = "itemListId";
        element2.value = itemListId.toString();
        element2.type = 'hidden';

        form.appendChild(element2);
        document.body.appendChild(form);

        form.submit();
    }
}

