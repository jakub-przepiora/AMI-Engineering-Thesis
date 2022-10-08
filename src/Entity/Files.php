<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 */
class Files
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=125)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $add_data;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link_to_file;

    /**
     * @ORM\Column(type="integer")
     */
    private $task_id;

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

    public function getAddData(): ?\DateTimeInterface
    {
        return $this->add_data;
    }

    public function setAddData(\DateTimeInterface $add_data): self
    {
        $this->add_data = $add_data;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getLinkToFile(): ?string
    {
        return $this->link_to_file;
    }

    public function setLinkToFile(string $link_to_file): self
    {
        $this->link_to_file = $link_to_file;

        return $this;
    }

    public function getTaskId(): ?int
    {
        return $this->task_id;
    }

    public function setTaskId(int $task_id): self
    {
        $this->task_id = $task_id;

        return $this;
    }
}
