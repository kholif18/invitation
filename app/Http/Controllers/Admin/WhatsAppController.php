<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    /**
     * Display WhatsApp messages list
     */
    public function index()
    {
        return view('admin.communications.whatsapp.index');
    }
    
    /**
     * Get WhatsApp messages data (AJAX)
     */
    public function getData(Request $request)
    {
        $messages = $this->getDummyMessages();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $messages = array_filter($messages, function($message) use ($request) {
                return $message['status'] === $request->status;
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $messages = array_filter($messages, function($message) use ($search) {
                return strpos(strtolower($message['recipient_name']), $search) !== false ||
                       strpos(strtolower($message['phone']), $search) !== false;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => array_values($messages)
        ]);
    }
    
    /**
     * Show send message form
     */
    public function create()
    {
        return view('admin.communications.whatsapp.send');
    }
    
    /**
     * Send WhatsApp message
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'message' => 'required|string',
            'schedule_date' => 'nullable|date'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp messages sent successfully',
            'sent_count' => count($request->recipients)
        ]);
    }
    
    /**
     * Show message details
     */
    public function show($id)
    {
        $messages = $this->getDummyMessages();
        $message = collect($messages)->firstWhere('id', (int)$id);
        
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }
    
    /**
     * Delete message
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
    
    /**
     * Resend WhatsApp message
     */
    public function resend($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Message resent successfully'
        ]);
    }
    
    /**
     * Schedule WhatsApp message
     */
    public function schedule(Request $request, $id)
    {
        $request->validate([
            'schedule_date' => 'required|date|after:now'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message scheduled successfully',
            'schedule_date' => $request->schedule_date
        ]);
    }
    
    /**
     * Bulk send messages
     */
    public function bulkSend(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'message' => 'required|string'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->recipients) . ' messages sent successfully'
        ]);
    }
    
    /**
     * Get message status
     */
    public function status($id)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'delivered',
                'delivered_at' => now()->subHours(2),
                'read_at' => now()->subHours(1),
                'read_by' => 'Guest'
            ]
        ]);
    }
    
    /**
     * Get WhatsApp statistics
     */
    public function statistics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_sent' => 1234,
                'delivered' => 1156,
                'failed' => 78,
                'read_rate' => 92,
                'response_rate' => 45
            ]
        ]);
    }
    
    /**
     * Export message logs
     */
    public function exportLogs()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="whatsapp_logs_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Recipient', 'Phone', 'Message', 'Status', 'Sent Date', 'Read Date']);
            
            $messages = $this->getDummyMessages();
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message['id'],
                    $message['recipient_name'],
                    $message['phone'],
                    substr($message['message'], 0, 50) . '...',
                    $message['status'],
                    $message['sent_date'],
                    $message['read_date'] ?? '-'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Connect WhatsApp Business API
     */
    public function connect()
    {
        return view('admin.communications.whatsapp.connect');
    }
    
    /**
     * Verify WhatsApp connection
     */
    public function verifyConnection(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'api_key' => 'required|string'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp connection verified successfully',
            'status' => 'connected'
        ]);
    }
    
    /**
     * Disconnect WhatsApp
     */
    public function disconnect()
    {
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp disconnected successfully'
        ]);
    }
    
    /**
     * Get dummy message data
     */
    private function getDummyMessages()
    {
        return [
            [
                'id' => 1,
                'recipient_name' => 'John Doe',
                'phone' => '+628123456789',
                'message' => 'Your presence is requested at John & Sarah\'s wedding on December 25, 2024 at 6:00 PM. Please RSVP via the link provided.',
                'status' => 'delivered',
                'sent_date' => '2024-12-20 14:30:00',
                'read_date' => '2024-12-20 15:45:00'
            ],
            [
                'id' => 2,
                'recipient_name' => 'Jane Smith',
                'phone' => '+628987654321',
                'message' => 'Don\'t forget to RSVP for John & Sarah\'s wedding! Click the link to confirm your attendance.',
                'status' => 'delivered',
                'sent_date' => '2024-12-19 10:15:00',
                'read_date' => '2024-12-19 11:20:00'
            ],
            [
                'id' => 3,
                'recipient_name' => 'Robert Johnson',
                'phone' => '+628555555555',
                'message' => 'Reminder: John & Sarah\'s wedding is tomorrow at 6:00 PM. See you there!',
                'status' => 'sent',
                'sent_date' => '2024-12-24 09:00:00',
                'read_date' => null
            ]
        ];
    }
}
