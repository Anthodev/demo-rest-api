<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PollRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Poll
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private string $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private string $question;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\All({
     *     @Assert\DateTime
     * })
     */
    private ?\DateTimeInterface $endDate;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("bool")
     * })
     */
    private bool $doUsersMustBeConnected;

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
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="polls")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="pollsParticipated")
     */
    private Collection $participants;

    /**
     * @var Collection|Answer[]
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="poll", orphanRemoval=true)
     */
    private Collection $answers;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("int")
     * })
     */
    private int $totalVotes;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->doUsersMustBeConnected = false;
        $this->totalVotes = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDoUsersMustBeConnected(): ?bool
    {
        return $this->doUsersMustBeConnected;
    }

    public function setDoUsersMustBeConnected(bool $doUsersMustBeConnected): self
    {
        $this->doUsersMustBeConnected = $doUsersMustBeConnected;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setPoll($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getPoll() === $this) {
                $answer->setPoll(null);
            }
        }

        return $this;
    }

    public function getTotalVotes(): ?int
    {
        return $this->totalVotes;
    }

    public function setTotalVotes(int $totalVotes): self
    {
        $this->totalVotes = $totalVotes;

        return $this;
    }
}
