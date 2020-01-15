<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 * @ORM\Entity()
 * @UniqueEntity("email")
 */
class User
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
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="users", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
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
     * @return User
     */
    public function setFullName(string $fullName): User
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
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return User
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
