<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use MNGame\Dto\MicroSMSDto;
use MNGame\Dto\PaypalDto;
use MNGame\Enum\ExecutionTypeEnum;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ServerRepository")
 */
class Server
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public ?string $host = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private ?int $port = null;

    /**
     * Caution!! must be plain text
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $paypal = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $microSMS = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $userOnlineCommand = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $executionType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }
    public function setPort(?int $port)
    {
        $this->port = $port;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    public function getPaypal(): ?PaypalDto
    {
        return $this->paypal ? unserialize($this->paypal) : new PaypalDto();
    }

    public function setPaypal(PaypalDto $paypal)
    {
        $this->paypal = serialize($paypal);
    }

    public function getMicroSMS(): ?MicroSMSDto
    {
        return $this->microSMS ? unserialize($this->microSMS) : new MicroSMSDto();
    }

    public function setMicroSMS(?MicroSMSDto $microSMS)
    {
        $this->microSMS = serialize($microSMS);
    }

    public function getUserOnlineCommand(): ?string
    {
        return $this->userOnlineCommand;
    }

    public function setUserOnlineCommand(?string $userOnlineCommand)
    {
        $this->userOnlineCommand = $userOnlineCommand;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image)
    {
        $this->image = $image;
    }

    /**
     * @throws ReflectionException
     */
    public function getExecutionType(): ?ExecutionTypeEnum
    {
        return new ExecutionTypeEnum($this->executionType);
    }

    public function setExecutionType(?ExecutionTypeEnum $executionType)
    {
        $this->executionType = $executionType->getValue();
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host)
    {
        $this->host = $host;
    }
}