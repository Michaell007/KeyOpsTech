<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParcelRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ParcelRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("libelle", message="Cette valeur est déjà utilisée")
 * @ApiResource(
 *     collectionOperations={
 *         "POST"= {"openapi_context"={
*            "summary"="Créer une parcel",
*            "description" = "Création d'une nouvelles parcel",
*          }},
 *         "GET"={"openapi_context"={
*            "summary"="Récuperer toutes les parcels",
*            "description" = "Récuperer la collections de parcels",
*          }}
*      },
 *     itemOperations= {
 *         "DELETE"= {"openapi_context"={
*            "summary"="Supprimer une parcel",
*            "description" = "Suppression de parcel par Id",
*          }},
 *        "PUT"={"openapi_context"={
*            "summary"="Mise a jour d'une parcel",
*            "description" = "Mise a jour d'une parcel",
*          }},
*          "GET"={"openapi_context"={
*            "summary"="Récuperer les details d'une parcel",
*            "description" = "Récuperer les details d'une parcel",
*          }}
*      },
 *     normalizationContext={"groups"={"parcel:read"}},
 *     denormalizationContext={"groups"={"parcel:write"}}
 * )
 */
class Parcel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"parcel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide")
     * @Groups({"parcel:read","parcel:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"parcel:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"parcel:read"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="parcels")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide")
     * @Groups({"parcel:read","parcel:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide")
     * @Groups({"parcel:read","parcel:write","user:read"})
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));    
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
