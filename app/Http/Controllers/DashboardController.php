<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Retrieve all domains
        $domains = Domain::all();
        $domains = Domain::with('company')->get();

        // Get current date
        $currentDate = Carbon::now();

        // Initialize counts
        $activeCount = 0;
        $expiredCount = 0;

        // Loop through domains to calculate counts
        foreach ($domains as $domain) {
            $expiryDate = Carbon::parse($domain->expiry_date);

            if ($expiryDate->gt($currentDate)) {
                // Domain is active
                $activeCount++;
            } else {
                // Domain has expired
                $expiredCount++;
            }
        }

        // Pass counts to the view
        return view('auth.dashboard', [
            'activeCount' => $activeCount,
            'expiredCount' => $expiredCount,
        ],compact('domains'));
    }
}
