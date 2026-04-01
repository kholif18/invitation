<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    /**
     * Display communications dashboard
     */
    public function index()
    {
        return view('admin.communications.index');
    }
    
    /**
     * Get communications dashboard data (AJAX)
     */
    public function dashboard()
    {
        // Dummy data for dashboard
        $data = [
            'email_campaigns' => [
                'total' => 12,
                'sent' => 8,
                'scheduled' => 3,
                'draft' => 1,
                'open_rate' => 68
            ],
            'whatsapp' => [
                'total_sent' => 1234,
                'delivered' => 1156,
                'read_rate' => 92,
                'pending' => 78
            ],
            'templates' => [
                'total' => 12,
                'email' => 7,
                'whatsapp' => 5
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
