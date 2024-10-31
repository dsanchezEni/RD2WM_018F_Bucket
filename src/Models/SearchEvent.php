<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Classe permettant de contenir tous les critères de recherche pour notre formulaire
 * de notre page Events.
 */
class SearchEvent
{
    #[Assert\NotBlank]
    public \DateTime $dateEvent;
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $city;

    public function getDateEvent(): \DateTime
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTime $dateEvent): SearchEvent
    {
        $this->dateEvent = $dateEvent;
        return $this;
    }

    public function getCity(): string
    {
        return ucfirst(strtolower($this->city));
    }

    public function setCity(string $city): SearchEvent
    {
        $this->city = $city;
        return $this;
    }


}