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
    #[ORM\Column(name:'idComplaint')]
    private ?int $idComplaint = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idComplaintSubject', referencedColumnName: 'idComplaintSubject', nullable: false)]
    private ?ComplaintSubject $subject = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name:'sentDateTime')]
    private ?\DateTimeInterface $sentDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name:'readDateTime')]
    private ?\DateTimeInterface $readDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'sentComplaints')]
    #[ORM\JoinColumn(name: 'idSender', referencedColumnName: 'idUser', nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receivedComplaints')]
    #[ORM\JoinColumn(name: 'idRecipient', referencedColumnName: 'idUser', nullable: false)]
    private ?User $recipient = null;

    #[ORM\Column(length: 1024)]
    private ?String $status = '';

    const STATUS_ACTIVE = 'Active';
    const STATUS_ARCHIVED = 'Archived';

    public function getAll(){
        return [
            'idComplaint' => $this->idComplaint,
            'subject'=> $this->subject->getAll(),
            'sentDateTime' => $this->sentDateTime,
            'readDateTime' => $this->readDateTime,
            'status' => $this->status,
            'sender' => $this->sender->getAll(),
            'recipient' => $this->recipient->getAll(),
        ];
    }

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

    public function getStatus(): ?String
    {
        return $this->status;
    }

    public function setStatus(?String $Status): static
    {
        $this->status = $Status;

        return $this;
    }
}
