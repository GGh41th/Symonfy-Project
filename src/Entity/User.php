<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile_image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, FeedBack>
     */
    #[ORM\OneToMany(targetEntity: FeedBack::class, mappedBy: 'user')]
    private Collection $feedback_id;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $task_id;

    public function __construct()
    {
        $this->feedback_id = new ArrayCollection();
        $this->task_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profile_image;
    }

    public function setProfileImage(?string $profile_image): static
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, FeedBack>
     */
    public function getFeedbackId(): Collection
    {
        return $this->feedback_id;
    }

    public function addFeedbackId(FeedBack $feedbackId): static
    {
        if (!$this->feedback_id->contains($feedbackId)) {
            $this->feedback_id->add($feedbackId);
            $feedbackId->setUserId($this);
        }

        return $this;
    }

    public function removeFeedbackId(FeedBack $feedbackId): static
    {
        if ($this->feedback_id->removeElement($feedbackId)) {
            // set the owning side to null (unless already changed)
            if ($feedbackId->getUserId() === $this) {
                $feedbackId->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTaskId(): Collection
    {
        return $this->task_id;
    }

    public function addTaskId(Task $taskId): static
    {
        if (!$this->task_id->contains($taskId)) {
            $this->task_id->add($taskId);
            $taskId->setUserId($this);
        }

        return $this;
    }

    public function removeTaskId(Task $taskId): static
    {
        if ($this->task_id->removeElement($taskId)) {
            // set the owning side to null (unless already changed)
            if ($taskId->getUserId() === $this) {
                $taskId->setUserId(null);
            }
        }

        return $this;
    }
}
