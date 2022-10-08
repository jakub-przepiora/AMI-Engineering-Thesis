<?php

namespace App\Entity;

use App\Repository\TimersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimersRepository::class)
 */
class Timers
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
    private $id_task;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="bigint")
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_start;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTask(): ?int
    {
        return $this->id_task;
    }

    public function setIdTask(int $id_task): self
    {
        $this->id_task = $id_task;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDataStart(): ?\DateTimeInterface
    {
        return $this->data_start;
    }

    public function setDataStart(\DateTimeInterface $data_start): self
    {
        $this->data_start = $data_start;

        return $this;
    }
}
