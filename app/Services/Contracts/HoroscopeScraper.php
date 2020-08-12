<?php

namespace App\Services\Contracts;

interface HoroscopeScraper
{
    public function scrape(string $date);
}