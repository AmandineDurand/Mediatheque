<?php

namespace App\Enum;

enum FormatVid: string
{
    case Mp4 = 'MP4';
    case M4v = 'M4V';

    public function toString(): string
    {
        return $this->value;
    }
}