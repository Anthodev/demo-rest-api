<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Answer
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
    private string $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("int")
     * })
     */
    private int $votes;

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
     * @var Poll
     * @ORM\ManyToOne(targetEntity=Poll::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private Poll $poll;

    public function __construct()
    {
        $this->votes = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

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

    public function getPoll(): ?Poll
    {
        return $this->poll;
    }

    public function setPoll(?Poll $poll): self
    {
        $this->poll = $poll;

        return $this;
    }
}
