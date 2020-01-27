<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class UserClient
 * @ORM\Entity()
 * @UniqueEntity("email")
 */
class UserClient
{
    /**
     * @var int
     *
     * @Groups({"list", "show"})
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"list", "show"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"Create","Update"})
     */
    private $fullName;

    /**
     * @var string
     *
     * @Groups({"list", "show"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"Create","Update"})
     * @Assert\Email(groups={"Create","Update"}, message="The email '{{ value }}' is not a valid email")
     */
    private $email;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userClients", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserClient
     */
    public function setId(int $id): UserClient
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return UserClient
     */
    public function setFullName(string $fullName): UserClient
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserClient
     */
    public function setEmail(string $email): UserClient
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserClient
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
