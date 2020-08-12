<?php

namespace App\Http\Controllers;

use App\Repositories\HoroscopeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $horoscopeRepo;

    public function __construct(HoroscopeRepository $horoscopeRepo)
    {
        $this->horoscopeRepo = $horoscopeRepo;
    }

    public function index()
    {
        return Cache::remember('horoscopes', 60, function () {
            return $this->horoscopeRepo->getAll();
        });
    }
}
