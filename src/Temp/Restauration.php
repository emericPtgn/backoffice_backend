<?php

namespace App\Document;

use App\Repository\RestaurationRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: RestaurationRepository::class, collection: 'restauration')]
class Restauration extends Commerce
{
    #[MongoDB\Field(type: "string")]
    private string $typeCuisine;

    // Getters et setters...

    public function getTypeCuisine(): string
    {
        return $this->typeCuisine;
    }

    public function setTypeCuisine(string $typeCuisine): self
    {
        $this->typeCuisine = $typeCuisine;
        return $this;
    }
}
