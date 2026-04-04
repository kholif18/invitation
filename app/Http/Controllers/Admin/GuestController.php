<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function index(Invitation $invitation)
    {
        $guests = $invitation->guests()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.guests.index', compact('invitation', 'guests'));
    }

    public function create(Invitation $invitation)
    {
        return view('admin.guests.create', compact('invitation'));
    }

    public function store(Request $request, Invitation $invitation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'sending_method' => 'required|in:email,whatsapp,both',
            'number_of_guests' => 'required|integer|min:1|max:10',
            'message' => 'nullable|string|max:500',
        ]);

        $guest = $invitation->guests()->create($validated);

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', 'Guest added successfully!');
    }

    public function edit(Invitation $invitation, Guest $guest)
    {
        return view('admin.guests.edit', compact('invitation', 'guest'));
    }

    public function update(Request $request, Invitation $invitation, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'sending_method' => 'required|in:email,whatsapp,both',
            'number_of_guests' => 'required|integer|min:1|max:10',
            'message' => 'nullable|string|max:500',
        ]);

        $guest->update($validated);

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', 'Guest updated successfully!');
    }

    public function destroy(Invitation $invitation, Guest $guest)
    {
        $guest->delete();

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', 'Guest deleted successfully!');
    }

    public function sendInvitation(Invitation $invitation, Guest $guest)
    {
        // Here you would implement actual sending logic (email/WhatsApp)
        // For now, just mark as sent
        
        $guest->update([
            'is_sent' => true,
            'sent_at' => now()
        ]);

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', 'Invitation sent to ' . $guest->name . ' successfully!');
    }

    public function sendBulk(Invitation $invitation, Request $request)
    {
        $request->validate([
            'guest_ids' => 'required|array',
            'guest_ids.*' => 'exists:guests,id'
        ]);

        $guests = $invitation->guests()->whereIn('id', $request->guest_ids)->get();
        
        foreach ($guests as $guest) {
            $guest->update([
                'is_sent' => true,
                'sent_at' => now()
            ]);
        }

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', 'Invitations sent to ' . $guests->count() . ' guest(s)!');
    }

    public function import(Invitation $invitation)
    {
        return view('admin.guests.import', compact('invitation'));
    }

    public function processImport(Request $request, Invitation $invitation)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Skip header row if exists
        $header = fgetcsv($handle);
        
        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $guestData = [
                    'name' => $row[0] ?? null,
                    'email' => $row[1] ?? null,
                    'phone' => $row[2] ?? null,
                    'sending_method' => $row[3] ?? 'both',
                    'number_of_guests' => $row[4] ?? 1,
                    'message' => $row[5] ?? null,
                ];

                if ($guestData['name']) {
                    $invitation->guests()->create($guestData);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row " . ($imported + 1) . ": " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "Successfully imported {$imported} guests.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('admin.invitations.guests.index', $invitation)
            ->with('success', $message);
    }

    public function export(Invitation $invitation)
    {
        $guests = $invitation->guests()->get();
        
        $filename = "guests_{$invitation->id}_{$invitation->groom_full_name}_{$invitation->bride_full_name}.csv";
        
        $handle = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($handle, ['Name', 'Email', 'Phone', 'Sending Method', 'Number of Guests', 'Message', 'Status', 'Sent At']);
        
        // Add data
        foreach ($guests as $guest) {
            fputcsv($handle, [
                $guest->name,
                $guest->email,
                $guest->phone,
                $guest->sending_method,
                $guest->number_of_guests,
                $guest->message,
                $guest->attendance_status,
                $guest->sent_at
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function() use ($handle) {
                // Stream the file
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}
