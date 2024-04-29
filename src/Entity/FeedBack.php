<?php

namespace App\Entity;

use App\Repository\FeedBackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedBackRepository::class)]
class FeedBack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $submission_text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class , inversedBy: 'feedback_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubmissionText(): ?string
    {
        return $this->submission_text;
    }

    public function setSubmissionText(string $submission_text): static
    {
        $this->submission_text = $submission_text;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }


}
