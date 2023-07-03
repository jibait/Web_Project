<?php

namespace App\Entity;

use App\Repository\ProductUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductUserRepository::class)]

class ProductUser
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id")]
    private $product;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private $user;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }


}
