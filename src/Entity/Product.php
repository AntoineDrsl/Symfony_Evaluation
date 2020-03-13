<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 2,
     * max = 255,
     * minMessage = "Le nom du produit doit faire au moins {{ limit }} caractères.",
     * maxMessage = "Le nom du produit ne peut pas faire plus de {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\File(
     * mimeTypes={"image/png", "image/jpeg"},
     * mimeTypesMessage = "Le format de votre fichier est invalide. Veuillez entrer un fichier de type {{ types }}."
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     * message = "Le stock ne peut pas être négatif."
     * )
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive(
     * message = "Le prix ne peut pas être négatif ou nul."
     * )
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartLine", mappedBy="product", orphanRemoval=true)
     */
    private $cartLines;

    public function __construct()
    {
        $this->cartLines = new ArrayCollection();
    }

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|CartLine[]
     */
    public function getCartLines(): Collection
    {
        return $this->cartLines;
    }

    public function addCartLine(CartLine $cartLine): self
    {
        if (!$this->cartLines->contains($cartLine)) {
            $this->cartLines[] = $cartLine;
            $cartLine->setProduct($this);
        }

        return $this;
    }

    public function removeCartLine(CartLine $cartLine): self
    {
        if ($this->cartLines->contains($cartLine)) {
            $this->cartLines->removeElement($cartLine);
            // set the owning side to null (unless already changed)
            if ($cartLine->getProduct() === $this) {
                $cartLine->setProduct(null);
            }
        }

        return $this;
    }

    /**
    * @ORM\PostRemove
    */
    public function deleteFile() 
    {
        if(file_exists(__DIR__ . '/../../public/assets/images/'.$this->image)) {
            unlink(__DIR__ . '/../../public/assets/images/'.$this->image);
        }
        return true;
    }
}
