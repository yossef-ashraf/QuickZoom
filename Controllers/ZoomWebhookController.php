<?php
namespace QuickZoom\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use QuickZoom\Events\ZoomMeetingEnded;
use QuickZoom\Events\ZoomMeetingStarted;
use QuickZoom\Events\ZoomParticipantJoined;
use QuickZoom\Events\ZoomParticipantLeft;

class ZoomWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? '';
        
        Log::info('QuickZoom Webhook received', ['event' => $event, 'payload' => $payload]);
        
        switch ($event) {
            case 'meeting.started':
                event(new ZoomMeetingStarted($payload));
                break;
            case 'meeting.ended':
                event(new ZoomMeetingEnded($payload));
                break;
            case 'meeting.participant_joined':
                event(new ZoomParticipantJoined($payload));
                break;
            case 'meeting.participant_left':
                event(new ZoomParticipantLeft($payload));
                break;
            default:
                Log::info('Unhandled Zoom webhook event', ['event' => $event]);
                break;
        }
        
        return response()->json(['message' => 'Webhook processed successfully']);
    }
}