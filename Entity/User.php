<?php

namespace Module\User\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Module\User\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(
    fields: ['email'],
    message: 'Is already in use on that email.',
    errorPath: 'email'
)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    public const STATUS_NEW = 10;
    public const STATUS_CONFIRMED = 20;
    public const STATUS_BLOCKED = 30;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Assert\Length(min: 3, max: 200)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\Length(min: 5, max: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\Choice(choices: [self::STATUS_NEW, self::STATUS_CONFIRMED, self::STATUS_BLOCKED])]
    private int $status;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 20)]
    #[ORM\Column(type: "string", length: 20, unique: true)]
    private string $phone;

    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[Assert\NotBlank]
    #[Assert\Length(min: 20, max: 255)]
    #[ORM\Column(type: "string", length: 255)]
    private string $address;

    #[ORM\Column(type: "date_immutable")]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: "date_immutable", nullable: true)]
    private DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->setStatus(self::STATUS_NEW);
        $this->setCreatedAt(new DateTimeImmutable());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): string
    {
        return '_ELENYUM_';
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}