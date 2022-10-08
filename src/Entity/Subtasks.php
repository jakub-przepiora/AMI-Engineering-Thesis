<?php

namespace App\Entity;

use App\Repository\SubtasksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubtasksRepository::class)
 */
class Subtasks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_creator;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_data;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modification_data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCreator(): ?int
    {
        return $this->id_creator;
    }

    public function setIdCreator(int $id_creator): self
    {
        $this->id_creator = $id_creator;

        return $this;
    }

    public function getCreateData(): ?\DateTimeInterface
    {
        return $this->create_data;
    }

    public function setCreateData(\DateTimeInterface $create_data): self
    {
        $this->create_data = $create_data;

        return $this;
    }

    public function getModificationData(): ?\DateTimeInterface
    {
        return $this->modification_data;
    }

    public function setModificationData(\DateTimeInterface $modification_data): self
    {
        $this->modification_data = $modification_data;

        return $this;
    }
}
