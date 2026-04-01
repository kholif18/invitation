<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    /**
     * Display list of message templates
     */
    public function index()
    {
        return view('admin.communications.templates.index');
    }
    
    /**
     * Get templates data (AJAX)
     */
    public function getData(Request $request)
    {
        $templates = $this->getDummyTemplates();
        
        // Apply filters
        if ($request->has('type') && $request->type) {
            $templates = array_filter($templates, function($template) use ($request) {
                return $template['type'] === $request->type;
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $templates = array_filter($templates, function($template) use ($search) {
                return strpos(strtolower($template['name']), $search) !== false;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => array_values($templates)
        ]);
    }
    
    /**
     * Show create template form
     */
    public function create()
    {
        return view('admin.communications.templates.create');
    }
    
    /**
     * Store new template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,whatsapp,both',
            'subject' => 'required_if:type,email,both|nullable|string|max:255',
            'content' => 'required|string',
            'is_default' => 'boolean'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Template created successfully',
            'template_id' => rand(100, 999)
        ]);
    }
    
    /**
     * Show template details
     */
    public function show($id)
    {
        $templates = $this->getDummyTemplates();
        $template = collect($templates)->firstWhere('id', (int)$id);
        
        return response()->json([
            'success' => true,
            'data' => $template
        ]);
    }
    
    /**
     * Edit template
     */
    public function edit($id)
    {
        $templates = $this->getDummyTemplates();
        $template = collect($templates)->firstWhere('id', (int)$id);
        
        return view('admin.communications.templates.edit', compact('template'));
    }
    
    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully'
        ]);
    }
    
    /**
     * Delete template
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }
    
    /**
     * Duplicate template
     */
    public function duplicate($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Template duplicated successfully',
            'new_template_id' => rand(100, 999)
        ]);
    }
    
    /**
     * Set template as default
     */
    public function setDefault($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Template set as default successfully'
        ]);
    }
    
    /**
     * Preview template
     */
    public function preview(Request $request, $id)
    {
        $templates = $this->getDummyTemplates();
        $template = collect($templates)->firstWhere('id', (int)$id);
        
        $variables = $request->get('variables', []);
        $preview = $template['content'];
        
        foreach ($variables as $key => $value) {
            $preview = str_replace("[$key]", $value, $preview);
        }
        
        return response()->json([
            'success' => true,
            'preview' => $preview
        ]);
    }
    
    /**
     * Get templates by category
     */
    public function byCategory($type)
    {
        $templates = $this->getDummyTemplates();
        $filtered = array_filter($templates, function($template) use ($type) {
            return $template['type'] === $type || $template['type'] === 'both';
        });
        
        return response()->json([
            'success' => true,
            'data' => array_values($filtered)
        ]);
    }
    
    /**
     * Get email templates only
     */
    public function emailTemplates()
    {
        return $this->byCategory('email');
    }
    
    /**
     * Get WhatsApp templates only
     */
    public function whatsappTemplates()
    {
        return $this->byCategory('whatsapp');
    }
    
    /**
     * Import templates
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,csv'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Templates imported successfully',
            'count' => rand(1, 10)
        ]);
    }
    
    /**
     * Export templates
     */
    public function export()
    {
        $templates = $this->getDummyTemplates();
        
        return response()->json([
            'success' => true,
            'data' => $templates,
            'filename' => 'templates_export_' . date('Y-m-d') . '.json'
        ]);
    }
    
    /**
     * Export single template
     */
    public function exportTemplate($id)
    {
        $templates = $this->getDummyTemplates();
        $template = collect($templates)->firstWhere('id', (int)$id);
        
        return response()->json([
            'success' => true,
            'data' => $template,
            'filename' => 'template_' . $id . '_' . date('Y-m-d') . '.json'
        ]);
    }
    
    /**
     * Get available template variables
     */
    public function getVariables()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['name' => 'Guest Name', 'variable' => '[Guest Name]', 'description' => 'Recipient\'s full name'],
                ['name' => 'Event Name', 'variable' => '[Event Name]', 'description' => 'Wedding or event name'],
                ['name' => 'Event Date', 'variable' => '[Event Date]', 'description' => 'Date of the event'],
                ['name' => 'Event Time', 'variable' => '[Event Time]', 'description' => 'Time of the event'],
                ['name' => 'Event Location', 'variable' => '[Event Location]', 'description' => 'Venue address'],
                ['name' => 'RSVP Link', 'variable' => '[RSVP Link]', 'description' => 'Personalized RSVP link'],
                ['name' => 'Couple Name', 'variable' => '[Couple Name]', 'description' => 'Names of the couple'],
                ['name' => 'Groom Name', 'variable' => '[Groom Name]', 'description' => 'Groom\'s name'],
                ['name' => 'Bride Name', 'variable' => '[Bride Name]', 'description' => 'Bride\'s name'],
            ]
        ]);
    }
    
    /**
     * Get dummy template data
     */
    private function getDummyTemplates()
    {
        return [
            [
                'id' => 1,
                'name' => 'Wedding Invitation',
                'type' => 'email',
                'subject' => 'You\'re Invited to [Couple Name]\'s Wedding',
                'content' => "Dear [Guest Name],\n\nYou are cordially invited to celebrate the wedding of [Groom Name] and [Bride Name] on [Event Date] at [Event Time].\n\nVenue: [Event Location]\n\nPlease RSVP by clicking the link below:\n[RSVP Link]\n\nWe look forward to celebrating with you!",
                'usage_count' => 156,
                'is_default' => true
            ],
            [
                'id' => 2,
                'name' => 'RSVP Reminder',
                'type' => 'both',
                'subject' => 'Reminder: Please RSVP for [Couple Name]\'s Wedding',
                'content' => "Dear [Guest Name],\n\nWe haven't received your RSVP response for [Couple Name]'s wedding yet. Please confirm your attendance by clicking the link below:\n\n[RSVP Link]\n\nThank you!",
                'usage_count' => 89,
                'is_default' => false
            ],
            [
                'id' => 3,
                'name' => 'Thank You Message',
                'type' => 'whatsapp',
                'subject' => null,
                'content' => "Dear [Guest Name],\n\nThank you for attending [Couple Name]'s wedding! Your presence made our day truly special.\n\nWe appreciate your love and support!",
                'usage_count' => 234,
                'is_default' => true
            ],
            [
                'id' => 4,
                'name' => 'Location Reminder',
                'type' => 'whatsapp',
                'subject' => null,
                'content' => "Hi [Guest Name]!\n\nDon't forget! [Couple Name]'s wedding is tomorrow at [Event Time] at [Event Location].\n\nSee you there! 🎉",
                'usage_count' => 78,
                'is_default' => false
            ],
            [
                'id' => 5,
                'name' => 'Post Wedding Survey',
                'type' => 'email',
                'subject' => 'We\'d Love Your Feedback!',
                'content' => "Dear [Guest Name],\n\nWe hope you had a wonderful time at our wedding! We'd love to hear your feedback.\n\nPlease take a moment to fill out our survey: [Survey Link]\n\nThank you!",
                'usage_count' => 34,
                'is_default' => false
            ]
        ];
    }
}
