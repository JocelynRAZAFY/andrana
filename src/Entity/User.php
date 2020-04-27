<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    /**
     * Administrateur
     */
    const USER_ADMIN = 'ROLE_ADMIN';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    const ROLE_USER = 'ROLE_USER';

    const PWD = '123';

    const USER_PROFILE = 'f37ed1e3-5c30-4102-bb81-5abc80eb93e6';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $emailInitial;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statusValidationEmail;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $emailSocial;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nameSocial;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $isSocial;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $enabled;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $addressDelivery;

    /**
     * @ORM\Column(type="string", length=550,nullable=true)
     */
    private $companyName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validationDate;

    /**
     * @ORM\Column(type="string", length=550,nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=550,nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=550,nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $deleted;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {

        return (string) $this->username;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getStatusValidationEmail(): ?bool
    {
        return $this->statusValidationEmail;
    }

    public function setStatusValidationEmail(bool $statusValidationEmail): self
    {
        $this->statusValidationEmail = $statusValidationEmail;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmailSocial(): ?string
    {
        return $this->emailSocial;
    }

    public function setEmailSocial(?string $emailSocial): self
    {
        $this->emailSocial = $emailSocial;

        return $this;
    }

    public function getNameSocial(): ?string
    {
        return $this->nameSocial;
    }

    public function setNameSocial(?string $nameSocial): self
    {
        $this->nameSocial = $nameSocial;

        return $this;
    }

    public function getIsSocial(): ?bool
    {
        return $this->isSocial;
    }

    public function setIsSocial(?bool $isSocial): self
    {
        $this->isSocial = $isSocial;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getValidationDate(): ?\DateTimeInterface
    {
        return $this->validationDate;
    }

    public function setValidationDate(?\DateTimeInterface $validationDate): self
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getEmailInitial(): ?string
    {
        return $this->emailInitial;
    }

    public function setEmailInitial(string $emailInitial): self
    {
        $this->emailInitial = $emailInitial;

        return $this;
    }

    public function getAddressDelivery(): ?string
    {
        return $this->addressDelivery;
    }

    public function setAddressDelivery(?string $addressDelivery): self
    {
        $this->addressDelivery = $addressDelivery;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }


}
