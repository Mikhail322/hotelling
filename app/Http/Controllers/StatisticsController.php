<?php

namespace App\Http\Controllers;

use App\Services\Hotelling\HotelsWithRejections;
use App\Services\Hotelling\RejectionMoneyLost;
use App\Services\Hotelling\TopFiveHotelsWeekendStayService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(
        TopFiveHotelsWeekendStayService $topFiveHotelsWeekendStayService,
        HotelsWithRejections $hotelsWithRejections,
        RejectionMoneyLost $rejectionMoneyLost,
    )
    {
        return view('welcome',
            [
                'task1' => $topFiveHotelsWeekendStayService->execute(),
                'task2' => $hotelsWithRejections->execute(),
                'task3' => $rejectionMoneyLost->execute(),
            ]);
    }
}
