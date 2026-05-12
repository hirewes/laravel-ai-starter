<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardStatsResource;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboardService): DashboardStatsResource
    {
        return new DashboardStatsResource($dashboardService->forUser($request->user()));
    }
}
