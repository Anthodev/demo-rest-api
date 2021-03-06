<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_users", "get_user"})
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Groups({"get_users", "get_user"})
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private string $username;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\All({
     *     @Assert\Type("string")
     * })
     */
    private ?string $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private string $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_users", "get_user"})
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Email
     * })
     */
    private string $email;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\DateTime
     * })
     */
    private \DateTimeInterface $createdAt;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\All({
     *     @Assert\DateTime
     * })
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @Groups({"get_users", "get_user"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Role $role;

    /**
     * @var Collection|Poll[]
     * @ORM\OneToMany(targetEntity=Poll::class, mappedBy="owner")
     */
    private Collection $polls;

    /**
     * @var Collection|Poll[]
     * @ORM\ManyToMany(targetEntity=Poll::class, mappedBy="participants")
     */
    private Collection $pollsParticipated;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
        $this->pollsParticipated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()->getCode()];
    }

    /**
     * @return Collection|Poll[]
     */
    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(Poll $poll): self
    {
        if (!$this->polls->contains($poll)) {
            $this->polls[] = $poll;
            $poll->setOwner($this);
        }

        return $this;
    }

    public function removePoll(Poll $poll): self
    {
        if ($this->polls->removeElement($poll)) {
            // set the owning side to null (unless already changed)
            if ($poll->getOwner() === $this) {
                $poll->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Poll[]
     */
    public function getPollsParticipated(): Collection
    {
        return $this->pollsParticipated;
    }

    public function addPollsParticipated(Poll $pollsParticipated): self
    {
        if (!$this->pollsParticipated->contains($pollsParticipated)) {
            $this->pollsParticipated[] = $pollsParticipated;
            $pollsParticipated->addParticipant($this);
        }

        return $this;
    }

    public function removePollsParticipated(Poll $pollsParticipated): self
    {
        if ($this->pollsParticipated->removeElement($pollsParticipated)) {
            $pollsParticipated->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->role,
            // see section on salt below
            // $this->salt,
        ]);
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->role,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
