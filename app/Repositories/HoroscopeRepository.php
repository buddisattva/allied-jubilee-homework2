<?php

namespace App\Repositories;

use App\Entities\Horoscope;

class HoroscopeRepository
{
    private $horoscope;

    public function __construct(Horoscope $horoscope)
    {
        $this->horoscope = $horoscope;
    }

    public function updateOrCreate(array $matchings, array $values)
    {
        return $this->horoscope->updateOrInsert($matchings, $values);
    }

    public function getAll()
    {
        return $this->horoscope
            ->orderBy('date')
            ->orderBy('zodiac_sign')
            ->get();
    }
}