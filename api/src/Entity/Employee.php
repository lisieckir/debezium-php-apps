<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?string $lastName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void 
    {
        $this->name = $name;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}