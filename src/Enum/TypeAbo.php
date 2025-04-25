<?php

namespace App\Enum;

enum TypeAbo: string
{
    case Mensuel = 'mensuel';
    case Annuel = 'annuel';

    public function toString(): string
    {
        return $this->value;
    }
}