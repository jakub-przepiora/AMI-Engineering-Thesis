<?php

namespace App\Entity;

use App\Repository\ColumnRepository;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=ColumnRepository::class)
 */
class Column
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
    private $id_table;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $column_name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getColumnName(): ?string
    {
        return $this->column_name;
    }

    public function setColumnName(string $column_name): self
    {
        $this->column_name = $column_name;

        return $this;
    }
}
