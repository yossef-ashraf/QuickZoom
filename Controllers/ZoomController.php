<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YourVendor\QuickZoom\Facades\QuickZoom;

class ZoomController extends Controller
{
    /**
     * Create a new Zoom meeting
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMeeting(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:10',
            'timezone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            $data = [
                'topic' => $request->topic,
                'type' => 2, // Scheduled meeting
                'start_time' => $request->start_time,
                'duration' => $request->duration,
                'timezone' => $request->timezone ?? 'UTC',
                'password' => $request->password ?? '',
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'waiting_room' => true,
                ]
            ];

            $meeting = QuickZoom::createMeeting($data);

            return response()->json([
                'success' => true,
                'data' => $meeting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get meeting details
     *
     * @param string $meetingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMeeting($meetingId)
    {
        try {
            $meeting = QuickZoom::getMeeting($meetingId);

            return response()->json([
                'success' => true,
                'data' => $meeting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all meetings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listMeetings()
    {
        try {
            $meetings = QuickZoom::listMeetings([
                'type' => 'scheduled',
                'page_size' => 30,
            ]);

            return response()->json([
                'success' => true,
                'data' => $meetings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}