<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CompanySubUser;
use App\Mail\ActivityTaskEmail;
use App\Mail\ActivityCallEmail;
use App\Mail\ActivityMeetingEmail;
use App\Mail\ActivityEventEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use PhpParser\Node\Expr\Exit_;

class ActivityController extends Controller
{ 
    /**
     * Display a listing of activities.
     */
  public function index(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Please login to continue',
                'redirect' => route('auth.login')
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'error' => true,
                'message' => 'Company session expired. Please login again.',
                'redirect' => route('auth.login')
            ], 401);
        }

        $query = Activity::with(['user', 'relatedModel'])
            ->where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc');

        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('type', 'LIKE', '%' . $request->search . '%');
            });
        }

        if (!empty($request->filter)) {
            $query->where('type', $request->filter);
        }

        $activities = $query->paginate(8);

        return response()->json([
            'error' => false,
            'message' => 'Activities fetched successfully',
            'data' => $activities->items(),
            'pagination' => [
                'total' => $activities->total(),
                'per_page' => $activities->perPage(), 
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'next_page_url' => $activities->nextPageUrl(),
                'prev_page_url' => $activities->previousPageUrl()
            ]
        ]);
   
    } catch (\Exception $e) {
        Log::error('Activity Index Error: ' . $e->getMessage());
        return response()->json([
            'error' => true,
            'message' => 'An error occurred while fetching activities'
        ], 500);
    }
}

    /**
     * Store a new activity.
     */
    public function store(Request $request)
    {
        try {

            $companyId = Session::get('selected_company_id');
            $userId = Auth::id();


            $validEmails = [];

            if($request->type == "task") {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'type' => 'required|string|in:task,call,meeting,event',
                    'relatedToName_values' => 'nullable|string',
                    'related_to_type' => 'nullable|string',
                    'start_date' => 'required|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date',
                    'priority' => 'required|string|in:low,medium,high'
                ]);
            
                $activityData = [
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'title' => $validated['title'],
                    'description_agenda_notes' => $validated['description'],
                    'type' => $validated['type'],
                    'related_to' => $validated['relatedToName_values'],
                    'related_to_type' => $validated['related_to_type'],
                    'start_date' => $validated['start_date'],
                    'priority' => $validated['priority'],
                    'status' => 'pending'
                ];
            
                // Initialize empty array
                $validEmails = [];
                
                if (!empty($validated['relatedToName_values'])) {
                    $emails = array_map('trim', explode(',', $validated['relatedToName_values']));
                    
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $validEmails[] = $email;
                        } else {
                            Log::warning("Invalid email skipped: " . $email);
                        }
                    }
                }
            
                // Only proceed if we have valid emails
                if (!empty($validEmails)) {
                    $user = Auth::user();
                    $companyProfile = $user->companyProfile;
                    $supportEmail = config('mail.support_email', config('mail.from.address'));
                    
                    foreach ($validEmails as $email) {
                        Mail::to($email)->queue(
                            new ActivityTaskEmail(
                                activityName: $validated['title'],
                                assignedTo: $email,
                                assignedBy: $user->name,
                                startTime: $validated['start_date'],
                                endTime: $validated['end_date'] ?? null,
                                priority: $validated['priority'],
                                status: 'Assigned',
                                description: $validated['description'] ?? 'No description provided',
                                companyName: $companyProfile ? $companyProfile->company_name : 'Company',
                                supportEmail: $supportEmail
                            )
                        );
                    }
                }
            }else  if($request->type == "meeting"){
                $validated = $request->validate([
                    'meetingTitle' => 'required|string|max:255',
                    'meetingAgenda' => 'nullable|string',
                    'type' => 'required|string|in:task,call,meeting,event',
                    'meeting_participants_values' => 'nullable|string',
                    'meetingDate' => 'nullable|date|after_or_equal:start_date',
                    'meetingDuration' => 'nullable|string',

                ]);
    
               
    
                $activityData = [
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'title' => $validated['meetingTitle'],
                    'description_agenda_notes' => $validated['meetingAgenda'],
                    'type' => $validated['type'],
                    'participants' => $validated["meeting_participants_values"],
                    'start_date' => $validated['meetingDate'],
                    'duration' => $validated["meetingDuration"],
                    'status' => 'pending'
                ];

                $validEmails = [];
                if (!empty($validated['meeting_participants_values'])) {
                    $emails = array_map('trim', explode(',', $validated['meeting_participants_values']));
                    
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $validEmails[] = $email;
                        } else {
                            Log::warning("Invalid email skipped: " . $email);
                        }
                    }
                }
            
                // Create activity first
                $activity = Activity::create($activityData);
            
                // Send emails if valid addresses exist
                if (!empty($validEmails)) {
                    $user = Auth::user();
                    $companyProfile = $user->companyProfile;
                    $supportEmail = config('mail.support_email', config('mail.from.address'));
            
                    // Generate links (you'll need to implement these based on your application)
                    // $joinLink = route('meetings.join', ['meeting' => $activity->id]);
                    // $rescheduleLink = route('meetings.reschedule', ['meeting' => $activity->id]);
                    // $calendarLink = route('meetings.calendar', ['meeting' => $activity->id]);

                    $joinLink = null; // Placeholder, replace with actual link
                    $rescheduleLink = null; // Placeholder, replace with actual link
                    $calendarLink = null; // Placeholder, replace with actual link
            
                    foreach ($validEmails as $email) {
                        try {
                            Mail::to($email)->queue(new ActivityMeetingEmail(
                                meetingTitle: $validated['meetingTitle'],
                                timeUntilMeeting: Carbon::now()->diffInMinutes($validated['meetingDate']),
                                meetingDateTime: $validated['meetingDate'],
                                attendees: $validated['meeting_participants_values'],
                                companyName: $companyProfile ? $companyProfile->company_name : 'Company',
                                supportEmail: $supportEmail,
                                timezone: $user->timezone ?? null,
                                meetingAgenda: $validated['meetingAgenda'] ?? null,
                                meetingLocation: $validated['meetingLocation'] ?? null,
                                joinLink: $joinLink,
                                rescheduleLink: $rescheduleLink,
                                calendarLink: $calendarLink
                            ));
                        } catch (\Exception $e) {
                            Log::error('Meeting email queue failed: ' . $e->getMessage(), [
                                'email' => $email,
                                'meeting_id' => $activity->id
                            ]);
                            // Continue with other emails even if one fails
                        }
                    }
                }

               

            }else  if($request->type == "call"){


                $validated = $request->validate([
                    'callTitle' => 'required|string|max:255',
                    'callNotes' => 'nullable|string',
                    'type' => 'required|string|in:task,call,meeting,event',
                    'contact_values' => 'nullable|string',
                    'callType' => 'nullable|string',
                    // 'start_date' => 'required|date',
                    'callDate' => 'nullable|date|after_or_equal:start_date',
                    
                ]);
    
               
    
                $activityData = [
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'title' => $validated['callTitle'],
                    'description_agenda_notes' => $validated['callNotes'],
                    'type' => $validated['type'],
                    'contact' => $validated['contact_values'],
                    'call_event_type' => $validated['callType'],
                    'start_date' => $validated['callDate'],
                    'status' => 'pending'
                ];


                 
                $validEmails = [];
                
                if (!empty($validated['contact_values'])) {
                    $emails = array_map('trim', explode(',', $validated['contact_values']));
                    
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $validEmails[] = $email;
                        } else {
                            Log::warning("Invalid email skipped: " . $email);
                        }
                    }
                }
            
                // Only proceed if we have valid emails
                if (!empty($validEmails)) {
                    $user = Auth::user();
                    $companyProfile = $user->companyProfile;
                    $supportEmail = config('mail.support_email', config('mail.from.address'));

                 
                    

                    $companyProfile = $user->companyProfile;
                    $supportEmail = config('mail.support_email', config('mail.from.address'));
                    
                    foreach ($validEmails as $email) {
                        try {
                    
                            Mail::to($email)->queue(new ActivityCallEmail(
                                callTitle: $validated['callTitle'],
                                timeUntilCall: Carbon::now()->diffInMinutes($validated['callDate']),
                                callDateTime: $validated['callDate'],
                                participants: $validated['contact_values'],
                                companyName: $companyProfile ? $companyProfile->company_name : 'Company',
                                supportEmail: $supportEmail,
                                timezone: "n", // If you have this available
                                callPurpose: $validated['callNotes'] ?? null
                            ));
                        } catch (\Exception $e) {
                            Log::error('Mail queue failed: ' . $e->getMessage());
                            return response()->json(['error' => 'Mail queue failed', 'message' => $e->getMessage()], 500);
                        }
                    }
                }

            

            }else  if($request->type == "event"){
                $validated = $request->validate([
                    'eventName' => 'required|string|max:255',
                    'eventDescription' => 'nullable|string',
                    'type' => 'required|string|in:task,call,meeting,event',
                    'eventParticipants_values' => 'nullable|string',
                    'eventType' => 'nullable|string',
                    'eventStart' => 'required|date',
                    'eventEnd' => 'nullable|date|after_or_equal:start_date',
        
                ]);
    
               
    
                $activityData = [
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'title' => $validated['eventName'],
                    'description_agenda_notes' => $validated['eventDescription'],
                    'type' => $validated['type'],
                    'participants' => $validated["eventParticipants_values"],
                    'call_event_type' => $validated['eventType'],
                    'start_date' => $validated['eventStart'],
                    'end_date' => $validated["eventEnd"],
                    'status' => 'pending'
                ];

                  // Process emails
    $validEmails = [];
    if (!empty($validated['eventParticipants_values'])) {
        $emails = array_map('trim', explode(',', $validated['eventParticipants_values']));
        
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            } else {
                Log::warning("Invalid email skipped: " . $email);
            }
        }
    }

    // Create activity first
    $activity = Activity::create($activityData);

    // Send emails if valid addresses exist
    if (!empty($validEmails)) {
        $user = Auth::user();
        $companyProfile = $user->companyProfile;
        $supportEmail = config('mail.support_email', config('mail.from.address'));

        // Generate links (implement based on your application)
        // $joinLink = route('events.join', ['event' => $activity->id]);
        // $calendarLink = route('events.calendar', ['event' => $activity->id]);
        // $manageRSVPLink = route('events.rsvp', ['event' => $activity->id]);

        $joinLink = null; // Placeholder, replace with actual link
        $calendarLink = null; // Placeholder, replace with actual link
        $manageRSVPLink = null; // Placeholder, replace with actual link

        foreach ($validEmails as $email) {
            try {
                Mail::to($email)->queue(new ActivityEventEmail(
                    eventTitle: $validated['eventName'],
                    timeUntilEvent: Carbon::now()->diffInMinutes($validated['eventStart']),
                    eventDateTime: $validated['eventStart'],
                    eventHost: $validated['eventHost'] ?? $user->fullname,
                    companyName: $companyProfile ? $companyProfile->company_name : 'Company',
                    supportEmail: $supportEmail,
                    timezone: $user->timezone ?? null,
                    eventDescription: $validated['eventDescription'] ?? null,
                    eventLocation: $validated['eventLocation'] ?? null,
                    joinLink: $joinLink,
                    calendarLink: $calendarLink,
                    manageRSVPLink: $manageRSVPLink
                ));
            } catch (\Exception $e) {
                Log::error('Event email queue failed: ' . $e->getMessage(), [
                    'email' => $email,
                    'event_id' => $activity->id
                ]);
                // Continue with other emails even if one fails
            }
        }
    }


            }
           

            // Only add end_date if it's set
            if (isset($validated['end_date'])) {
                $activityData['end_date'] = $validated['end_date'];
            }

            $activity = Activity::create($activityData);
           

        
            

            return response()->json([
                'success' => true,
                'message' => 'Activity created successfully',
                'data' => $activity
            ]);

        } catch (\Exception $e) {
            Log::error('Activity Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

   
  
    /**
     * Delete an activity.
     */
    public function destroy($id)
    {
        try {
            $activity = Activity::where('company_id', Session::get('selected_company_id'))
                ->where('id', $id)
                ->first();

            if (!$activity) {
                return response()->json(['error' => 'Activity not found'], 404);
            }

            $activity->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Activity Delete Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete activity'], 500);
        }
    }

    /**
     * Complete an activity.
     */
    public function complete($id)
    {
        try {
            $activity = Activity::where('company_id', Session::get('selected_company_id'))
                ->where('id', $id)
                ->first();

            if (!$activity) {
                return response()->json(['error' => 'Activity not found'], 404);
            }

            $activity->update([
                'status' => 'completed',
                'end_date' => now()
            ]);

            return response()->json(['success' => true, 'activity' => $activity]);
        } catch (\Exception $e) {
            Log::error('Activity Complete Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to complete activity'], 500);
        }
    }

    /**
 * Update an activity.
 */
public function update(Request $request, $id)
{
    try {
        $activity = Activity::where('company_id', Session::get('selected_company_id'))
            ->where('id', $id)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        $companyId = Session::get('selected_company_id');
        $userId = Auth::id();

        if($request->type == "task"){
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|in:task,call,meeting,event',
                'related_to' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'priority' => 'required|string|in:low,medium,high'
            ]);

            $activityData = [
                'title' => $validated['title'],
                'description_agenda_notes' => $validated['description'],
                'type' => $validated['type'],
                'related_to' => $validated['related_to'],
                'start_date' => $validated['start_date'],
                'priority' => $validated['priority']
            ];

        } else if($request->type == "meeting") {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|in:task,call,meeting,event',
                'participants' => 'nullable|string',
                'start_date' => 'required|date',
                'duration' => 'nullable|string',
            ]);

            $activityData = [
                'title' => $validated['title'],
                'description_agenda_notes' => $validated['description'],
                'type' => $validated['type'],
                'participants' => $validated['participants'],
                'start_date' => $validated['start_date'],
                'duration' => $validated['duration']
            ];

        } else if($request->type == "call") {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|in:task,call,meeting,event',
                'contact' => 'nullable|string',
                'call_event_type' => 'nullable|string',
                'start_date' => 'required|date',
            ]);

            $activityData = [
                'title' => $validated['title'],
                'description_agenda_notes' => $validated['description'],
                'type' => $validated['type'],
                'contact' => $validated['contact'],
                'call_event_type' => $validated['call_event_type'],
                'start_date' => $validated['start_date']
            ];

        } else if($request->type == "event") {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_participants' => 'nullable|string',
                'type' => 'required|string|in:task,call,meeting,event',
                'participants' => 'nullable|string',
                'call_event_type' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $activityData = [
                'title' => $validated['title'],
                'description_agenda_notes' => $validated['description'],
                'type' => $validated['type'],
                'participants' => $validated['event_participants'],
                'call_event_type' => $validated['call_event_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date']
            ];
        }

        // Only add end_date if it's set
        if (isset($validated['end_date'])) {
            $activityData['end_date'] = $validated['end_date'];
        }

        $activity->update($activityData);

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'data' => $activity
        ]);

    } catch (\Exception $e) {
        Log::error('Activity Update Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Refresh activities.
     */
    public function refresh(Request $request)
    {
        $activities = Activity::where('company_id', session('selected_company_id'))
            ->with(['relatedModel'])
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'type' => $activity->type,
                    'related_to' => $activity->related_to,
                    'related_to_type' => $activity->related_to_type,
                    'relatedModel' => $activity->relatedModel,
                    'start_date' => $activity->start_date,
                    'all_day' => $activity->all_day,
                    'status' => $activity->status,
                    'priority' => $activity->priority
                ];
            });

        return response()->json([
            'activities' => $activities
        ]);
    }


public function getActivityStatistics()
{
    try {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Company session not found'
            ], 401);
        }

        // Get all counts in a single query for better performance
        $stats = Activity::where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->selectRaw('
                COUNT(*) as total_activities,
                SUM(CASE WHEN type = "task" THEN 1 ELSE 0 END) as task_count,
                SUM(CASE WHEN type = "call" THEN 1 ELSE 0 END) as call_count,
                SUM(CASE WHEN type = "meeting" THEN 1 ELSE 0 END) as meeting_count,
                SUM(CASE WHEN type = "event" THEN 1 ELSE 0 END) as event_count,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count
            ')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total_activities' => $stats->total_activities,
                'by_type' => [
                    'task' => $stats->task_count,
                    'call' => $stats->call_count,
                    'meeting' => $stats->meeting_count,
                    'event' => $stats->event_count
                ],
                'completion_rate' => $stats->total_activities > 0 
                    ? round($stats->completed_count / $stats->total_activities * 100, 2)
                    : 0
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Activity statistics error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch activity statistics'
        ], 500);
    }
}

/**
 * Display the specified activity.
 */
public function show($id)
{
    try {
        $activity = Activity::where('company_id', Session::get('selected_company_id'))
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Activity not found'
        ], 404);
    }
}


  public function sub_users_contact(){
    try {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Company session not found'
            ], 401);
        }

        $users = CompanySubUser::where('company_id', $companyId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);

    } catch (\Exception $e) {
        Log::error('Users contact error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch users contact',
            'messagem' => $e->getMessage()
        ], 500);
   }
 }
}