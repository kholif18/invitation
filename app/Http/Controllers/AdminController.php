<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $invitationsCount = Invitation::count();
        $guestsCount = Guest::count();

        return view('admin.dashboard', compact('invitationsCount', 'guestsCount'));
    }
}
