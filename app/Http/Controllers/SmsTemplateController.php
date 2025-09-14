<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of SMS templates.
     */
    public function index(Request $request)
    {
        $query = SmsTemplate::with('creator');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $templates = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'templates' => $templates->items(),
                'pagination' => [
                    'current_page' => $templates->currentPage(),
                    'last_page' => $templates->lastPage(),
                    'total' => $templates->total()
                ]
            ]);
        }

        return view('sms.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new SMS template.
     */
    public function create()
    {
        return view('sms.templates.create');
    }

    /**
     * Store a newly created SMS template.
     */
    public function store(Request $request)
    {
        try {
            // Clean up variables array - remove empty values
            $variables = array_filter($request->input('variables', []), function($var) {
                return !empty(trim($var));
            });
            
            $data = $request->all();
            $data['variables'] = $variables;
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255|unique:sms_templates,name',
                'description' => 'nullable|string|max:500',
                'message' => 'required|string|max:1000',
                'category' => 'required|in:general,birthday,event,announcement,reminder,welcome,thank_you,invitation,emergency,custom',
                'variables' => 'nullable|array',
                'variables.*' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $template = SmsTemplate::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'message' => $data['message'],
                'category' => $data['category'],
                'variables' => $variables,
                'created_by' => Auth::id(),
                'is_active' => true
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'template' => $template->load('creator'),
                    'message' => 'SMS template created successfully!'
                ]);
            }

            return redirect()->route('message.templates.index')
                            ->with('success', 'SMS template created successfully!');
        } catch (\Exception $e) {
            \Log::error('SMS Template creation failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create template. Please try again.'
                ], 500);
            }
            
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to create template. Please try again.']);
        }
    }

    /**
     * Display the specified SMS template.
     */
    public function show(SmsTemplate $template)
    {
        $template->load(['creator']);
        return view('sms.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified SMS template.
     */
    public function edit(SmsTemplate $template)
    {
        return view('sms.templates.edit', compact('template'));
    }

    /**
     * Update the specified SMS template.
     */
    public function update(Request $request, SmsTemplate $template)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sms_templates,name,' . $template->id,
            'description' => 'nullable|string|max:500',
            'message' => 'required|string|max:1000',
            'category' => 'required|in:general,birthday,event,announcement,reminder,welcome,thank_you,invitation,emergency,custom',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'message' => $request->message,
            'category' => $request->category,
            'variables' => $request->variables ?? [],
            'is_active' => $request->get('is_active', true)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'template' => $template->load('creator'),
                'message' => 'SMS template updated successfully!'
            ]);
        }

        return redirect()->route('sms.templates.index')
                        ->with('success', 'SMS template updated successfully!');
    }

    /**
     * Remove the specified SMS template.
     */
    public function destroy(SmsTemplate $template)
    {
        // Check if template is being used
        if ($template->messages()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete template that has been used in SMS messages.'
            ], 400);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'SMS template deleted successfully!'
        ]);
    }

    /**
     * Toggle template status.
     */
    public function toggleStatus(SmsTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active,
            'message' => 'Template status updated successfully!'
        ]);
    }

    /**
     * Duplicate a template.
     */
    public function duplicate(SmsTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->created_by = Auth::id();
        $newTemplate->usage_count = 0;
        $newTemplate->save();

        return response()->json([
            'success' => true,
            'template' => $newTemplate->load('creator'),
            'message' => 'Template duplicated successfully!'
        ]);
    }

    /**
     * Preview template with sample data.
     */
    public function preview(Request $request, SmsTemplate $template)
    {
        $sampleData = $request->get('sample_data', []);
        $previewMessage = $template->processVariables($sampleData);

        return response()->json([
            'success' => true,
            'preview' => $previewMessage,
            'character_count' => strlen($previewMessage),
            'sms_count' => ceil(strlen($previewMessage) / 160)
        ]);
    }
}
