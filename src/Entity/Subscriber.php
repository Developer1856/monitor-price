<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(
        message: 'Email {{ value }} не является валидным email.',
    )]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Advert::class, inversedBy: 'subscribers')]
    private Collection $ads;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Advert>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Advert $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads->add($ad);
        }

        return $this;
    }

    public function removeAd(Advert $ad): self
    {
        $this->ads->removeElement($ad);

        return $this;
    }
}
