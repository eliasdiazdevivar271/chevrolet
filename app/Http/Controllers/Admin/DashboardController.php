<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Promotion;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'vehicleCount' => Vehicle::count(),
            'promotionCount' => Promotion::count(),
            'messageCount' => ContactMessage::count(),
        ]);
    }
}
