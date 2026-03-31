<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Data dummy untuk dashboard
        $invitationsCount = 124;
        $guestsCount = 3847;
        $newInvitations = 12;
        $confirmedGuests = 2156;
        $attendanceRate = $guestsCount > 0 ? round(($confirmedGuests / $guestsCount) * 100, 1) : 0;
        $pendingInvitations = 8;
        
        // Recent invitations data
        $recentInvitations = [];
        for ($i = 1; $i <= 5; $i++) {
            $recentInvitations[] = (object)[
                'id' => $i,
                'event_name' => ['Sarah & John Wedding', 'Annual Corporate Gala', 'Birthday Bash', 'Tech Conference 2024', 'Graduation Ceremony'][$i-1],
                'event_date' => now()->addDays($i * 3),
                'guests_count' => rand(50, 200),
                'status' => ['sent', 'pending', 'sent', 'draft', 'sent'][$i-1]
            ];
        }
        
        // Chart data
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $chartData = [15, 22, 28, 35, 42, 48, 52, 58, 62, 68, 72, 78];
        
        return view('admin.dashboard', compact(
            'invitationsCount',
            'guestsCount',
            'newInvitations',
            'confirmedGuests',
            'attendanceRate',
            'pendingInvitations',
            'recentInvitations',
            'chartLabels',
            'chartData'
        ));
    }
}
