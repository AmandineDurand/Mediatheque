<?php

namespace App\Enum;

enum StatutDemande: string
{
    case EnAttente = 'en_attente';
    case Approuvee = 'approuvee';
    case Refusee = 'refusee';

    public function toString(): string
    {
        return $this->value;
    }
}