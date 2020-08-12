<?php

namespace App\Console\Commands;

use App\Services\Contracts\HoroscopeScraper;
use Illuminate\Console\Command;

class ScraperDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape horoscopes.';

    /**
     * Execute the console command.
     *
     * @param HoroscopeScraper $scraper
     * @return mixed
     */
    public function handle(HoroscopeScraper $scraper)
    {
        $horoscopes = $scraper->scrape();
        dd($horoscopes);
    }
}
