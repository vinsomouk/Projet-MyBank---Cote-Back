<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount; // Montant de la transaction

    /**
     * @ORM\Column(type="datetime")
     */
    private $date; // Date de la transaction

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name; // Nom du destinataire ou de la source

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label; // Libellé de la transaction

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $category; // Catégorie de la transaction (ex: alimentation, transport, etc.)

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $type; // Type de transaction (ex: "ajout" ou "envoi")

    // Getters et setters pour chaque champ
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}