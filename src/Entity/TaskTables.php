<?php

namespace App\Entity;

use App\Repository\TaskTablesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskTablesRepository::class)
 */
class TaskTables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $tab_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_owner;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tabMode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTabName(): ?string
    {
        return $this->tab_name;
    }

    public function setTabName(string $tab_name): self
    {
        $this->tab_name = $tab_name;

        return $this;
    }

    public function getIdOwner(): ?int
    {
        return $this->id_owner;
    }

    public function setIdOwner(int $id_owner): self
    {
        $this->id_owner = $id_owner;

        return $this;
    }

    public function getTabMode(): ?string
    {
        return $this->tabMode;
    }

    public function setTabMode(?string $tabMode): self
    {
        $this->tabMode = $tabMode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
