<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\RegulationType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RegulationService extends AbstractService implements ServiceInterface
{
    private $regulationCategoryRepository;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        RegulationCategoryRepository $regulationCategoryRepository,
        RegulationRepository $repository,
        CustomSerializer $serializer
    ) {
        $this->regulationCategoryRepository = $regulationCategoryRepository;

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    public function getRules(): array
    {
        $regulation = $this->repository->getRegulation();

        $ruleList = [];
        $category = '';

        /** @var array $rule */
        foreach ($regulation as $rule) {
            if ($category !== $rule['category']) {
                $category = $rule['category'];
            }

            $ruleList[$category][] = $rule['description'];
        }

        return $ruleList;
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new Regulation(), RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }
}
