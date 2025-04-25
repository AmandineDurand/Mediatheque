<?php

namespace App\Enum;

enum Frequence: string
{
    case Mensuel = 'mensuel';
    case Journalier = 'journalier';
    case Annuel = 'annuel';
    case Semestriel = 'semestriel';

    public function toString(): string
    {
        return $this->value;
    }
}