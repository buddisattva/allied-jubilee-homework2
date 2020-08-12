<?php

namespace App\Console\Commands;

use App\Repositories\HoroscopeRepository;
use App\Services\Contracts\HoroscopeScraper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScraperDaily extends Command
{
    // Constrained by click108.
    const MAX_BACKTRACE = 4;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:daily {--date=today}';

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
     * @param HoroscopeRepository $horoscopeRepo
     * @return mixed
     */
    public function handle(HoroscopeScraper $scraper, HoroscopeRepository $horoscopeRepo)
    {
        $date = $this->option('date');
        try {
            $date = Carbon::parse($date);
        } catch (\Throwable $throwable) {
            $this->error('Date string should be YYYY-mm-dd or other standard formats.');

            return false;
        }

        $today = today();
        if ($date->diffInDays($today) > self::MAX_BACKTRACE) {
            $this->warn('Cannot scrape horoscopes before ' .
                $today->subDays(self::MAX_BACKTRACE)->format('Y-m-d'));

            return false;
        }

        $date = $date->format('Y-m-d');
        $horoscopes = $scraper->scrape($date);
        $horoscopes->each(function ($item) use ($horoscopeRepo) {
            $horoscopeRepo->updateOrCreate([
                'date' => $item['date'],
                'zodiac_sign' => $item['zodiac_sign']
            ], [
                "overall_score" => $item['overall_score'],
                "overall_detail" => $item['overall_detail'],
                "love_score" => $item['love_score'],
                "love_detail" => $item['love_detail'],
                "business_score" => $item['business_score'],
                "business_detail" => $item['business_detail'],
                "finance_score" => $item['finance_score'],
                "finance_detail" => $item['finance_detail'],
            ]);
        });

        $this->info("Updated horoscopes for $date.");

        return true;
    }
}
