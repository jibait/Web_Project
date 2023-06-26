<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "product_id")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product_description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $product_img = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product_rate = null;

    #[ORM\Column(length: 255)]
    private ?string $product_tag = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'products')]
    private Collection $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductTitle(): ?string
    {
        return $this->product_title;
    }

    public function setProductTitle(string $product_title): static
    {
        $this->product_title = $product_title;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->product_description;
    }

    public function setProductDescription(?string $product_description): static
    {
        $this->product_description = $product_description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getProductImg(): ?string
    {
        return $this->product_img;
    }

    public function setProductImg(string $product_img): static
    {
        $this->product_img = $product_img;

        return $this;
    }

    public function getProductRate(): ?string
    {
        return $this->product_rate;
    }

    public function setProductRate(?string $product_rate): static
    {
        $this->product_rate = $product_rate;

        return $this;
    }

    public function getProductTag(): ?string
    {
        return $this->product_tag;
    }

    public function setProductTag(string $product_tag): static
    {
        $this->product_tag = $product_tag;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

}
