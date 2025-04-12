<?php
namespace QuickZoom\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use QuickZoom\Contracts\ZoomServiceInterface;

class ZoomController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomServiceInterface $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function index()
    {
        try {
            $meetings = $this->zoomService->listMeetings();
            return response()->json(['success' => true, 'data' => $meetings]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:200',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15|max:300',
            'agenda' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $meeting = $this->zoomService->createMeeting('me', $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Meeting created successfully',
                'data' => $meeting
            ], 201);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function handleError(\Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Zoom API Error',
            'error' => $e->getMessage()
        ], 500);
    }
}