<?php

namespace App\Services;

use App\Services\Contracts\HoroscopeScraper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector;

class Click108HoroscopeScraper implements HoroscopeScraper
{
    const BASE_URL = 'https://astro.click108.com.tw/daily_%s.php?iAstro=%s&iAcDay=%s';
    const ZODIAC_SIGNS = [
        '牧羊座' => 0,
        '金牛座' => 1,
        '雙子座' => 2,
        '巨蟹座' => 3,
        '獅子座' => 4,
        '處女座' => 5,
        '天秤座' => 6,
        '天蠍座' => 7,
        '射手座' => 8,
        '摩羯座' => 9,
        '水瓶座' => 10,
        '雙魚座' => 11,
    ];

    public function scrape(): Collection
    {
        $date = now()->format('Y-m-d');

        return collect(self::ZODIAC_SIGNS)->map(function ($code, $sign) use ($date) {
            $url = sprintf(self::BASE_URL, $code, $code, $date);
            $client = new Client();
            try {
                $response = $client->get($url);
                $body = $response->getBody();
                $content = $body->getContents();
                $body->close();
            } catch (GuzzleException $e) {
                throw $e;
            }

            $crawler = new Crawler($content);
            $todayContent = $this->getTodayContent($crawler);
            unset($crawler);

            $overall = $this->parse($todayContent, '整體運勢');
            $love = $this->parse($todayContent, '愛情運勢');
            $business = $this->parse($todayContent, '事業運勢');
            $finance = $this->parse($todayContent, '財運運勢');

            return [
                'date' => $date,
                'zodiac' => $sign,
                'overall_score' => $overall['score'],
                'overall_detail' => $overall['detail'],
                'love_score' => $love['score'],
                'love_detail' => $love['detail'],
                'business_score' => $business['score'],
                'business_detail' => $business['detail'],
                'finance_score' => $finance['score'],
                'finance_detail' => $finance['detail']
            ];
        });
    }

    /**
     * @param Crawler $crawler
     * @return string
     */
    private function getTodayContent(Crawler $crawler): string
    {
        $filter = '.TODAY_CONTENT';
        return current($crawler
            ->filter($filter)
            ->extract(['_text']));
    }

    /**
     * @param string $todayContent
     * @param string $subject
     * @return array
     */
    private function parse(string $todayContent, string $subject): array
    {
        $filtered = collect(explode("\r\n", $todayContent))
            ->filter(function ($item) use ($subject) {
                return strpos($item, $subject) !== false;
            })->first();

        $score = mb_substr_count($filtered, '★');
        $detail = mb_split('：', $filtered)[1];

        return [
            'score' => $score,
            'detail' => $detail
        ];
    }
}