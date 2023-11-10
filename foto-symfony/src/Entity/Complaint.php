<?php

namespace App\Entity;

use App\Repository\ComplaintRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
class Complaint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idComplaint = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idComplaintSubject', referencedColumnName: 'idComplaintSubject', nullable: false)]
    private ?ComplaintSubject $subject = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name:'sentDateTime')]
    private ?\DateTimeInterface $sentDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name:'readDateTime')]
    private ?\DateTimeInterface $readDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'SentComplaints')]
    #[ORM\JoinColumn(name: 'idSender', referencedColumnName: 'idUser', nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receivedComplaints')]
    #[ORM\JoinColumn(name: 'idRecipient', referencedColumnName: 'idUser', nullable: false)]
    private ?User $recipient = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idComplaintStatus', referencedColumnName: 'idComplaintStatus', nullable: false)]
    private ?ComplaintStatus $Status = null;

    public function getIdComplaint(): ?int
    {
        return $this->idComplaint;
    }

    public function getSubject(): ?ComplaintSubject
    {
        return $this->subject;
    }

    public function setSubject(?ComplaintSubject $Subject): static
    {
        $this->subject = $Subject;

        return $this;
    }

    public function getSentDateTime(): ?\DateTimeInterface
    {
        return $this->sentDateTime;
    }

    public function setSentDateTime(\DateTimeInterface $SentDateTime): static
    {
        $this->sentDateTime = $SentDateTime;

        return $this;
    }

    public function getReadDateTime(): ?\DateTimeInterface
    {
        return $this->readDateTime;
    }

    public function setReadDateTime(?\DateTimeInterface $readDateTime): static
    {
        $this->readDateTime = $readDateTime;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $Sender): static
    {
        $this->sender = $Sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $Recipient): static
    {
        $this->recipient = $Recipient;

        return $this;
    }

    public function getStatus(): ?ComplaintStatus
    {
        return $this->Status;
    }

    public function setStatus(?ComplaintStatus $Status): static
    {
        $this->Status = $Status;

        return $this;
    }
}
