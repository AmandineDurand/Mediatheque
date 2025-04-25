<?php

namespace App\Enum;

enum TypeCont: string
{
    case Retard = 'retard';
    case Degradation = 'dégradation';

    public function toString(): string
    {
        return $this->value;
    }
}