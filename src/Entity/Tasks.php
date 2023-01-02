<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TasksRepository::class)
 */
class Tasks
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
     * @ORM\Column(type="integer")
     */
    private $id_column;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_table;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $task_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_desc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_data;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modification_data;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $assign_user_id;

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

    public function getIdColumn(): ?int
    {
        return $this->id_column;
    }

    public function setIdColumn(int $id_column): self
    {
        $this->id_column = $id_column;

        return $this;
    }

    public function getIdTable(): ?int
    {
        return $this->id_table;
    }

    public function setIdTable(int $id_table): self
    {
        $this->id_table = $id_table;

        return $this;
    }

    public function getTaskName(): ?string
    {
        return $this->task_name;
    }

    public function setTaskName(string $task_name): self
    {
        $this->task_name = $task_name;

        return $this;
    }

    public function getTaskDesc(): ?string
    {
        return $this->task_desc;
    }

    public function setTaskDesc(string $task_desc): self
    {
        $this->task_desc = $task_desc;

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

    public function setModificationData(?\DateTimeInterface $modification_data): self
    {
        $this->modification_data = $modification_data;

        return $this;
    }

    public function getAssignUserId(): ?int
    {
        return $this->assign_user_id;
    }

    public function setAssignUserId(?int $assign_user_id): self
    {
        $this->assign_user_id = $assign_user_id;

        return $this;
    }
}
