# QuickZoom Package Structure

quickzoom/
├── composer.json
├── LICENSE
├── README.md
├── src/
│   ├── Console/
│   │   └── Commands/
│   │       └── InstallQuickZoomCommand.php
│   ├── Contracts/
│   │   └── ZoomServiceInterface.php
│   ├── Events/
│   │   ├── ZoomMeetingEnded.php
│   │   ├── ZoomMeetingStarted.php
│   │   ├── ZoomParticipantJoined.php
│   │   └── ZoomParticipantLeft.php
│   ├── Facades/
│   │   └── QuickZoom.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ZoomController.php
│   │   │   └── ZoomWebhookController.php
│   │   └── Middleware/
│   │       └── VerifyZoomWebhook.php
│   ├── Models/
│   │   ├── ZoomMeeting.php
│   │   ├── ZoomMeetingParticipant.php
│   │   └── ZoomRecording.php 
│   ├── Providers/
│   │   └── QuickZoomServiceProvider.php
│   ├── Services/
│   │   └── ZoomService.php
│   ├── config/
│   │   └── quickzoom.php
│   ├── database/
│   │   └── migrations/
│   │       └── 2023_01_01_000000_create_quickzoom_tables.php
│   └── routes/
│       └── api.php




{
    "name": "vendor/quickzoom",
    "description": "A complete Laravel Zoom integration package",
    "keywords": ["laravel", "zoom", "meetings", "api", "video", "conference"],
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Your Name",
            "email": "your.email@example.com"
        }
    ],
    "require": {
        "php": "^8.0",
        "guzzlehttp/guzzle": "^7.0",
        "firebase/php-jwt": "^6.0",
        "illuminate/support": "^9.0|^10.0",
        "illuminate/console": "^9.0|^10.0",
        "illuminate/database": "^9.0|^10.0",
        "illuminate/http": "^9.0|^10.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^9.0",
        "orchestra/testbench": "^7.0|^8.0"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\QuickZoom\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Vendor\\QuickZoom\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Vendor\\QuickZoom\\Providers\\QuickZoomServiceProvider"
            ],
            "aliases": {
                "QuickZoom": "Vendor\\QuickZoom\\Facades\\QuickZoom"
            }
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}



<?php

namespace Vendor\QuickZoom\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\QuickZoom\Console\Commands\InstallQuickZoomCommand;
use Vendor\QuickZoom\Contracts\ZoomServiceInterface;
use Vendor\QuickZoom\Services\ZoomService;

class QuickZoomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/quickzoom.php', 'quickzoom'
        );

        // Register the Zoom service as a singleton
        $this->app->singleton(ZoomServiceInterface::class, function ($app) {
            return new ZoomService();
        });

        // Register facade
        $this->app->alias(ZoomServiceInterface::class, 'quickzoom');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/quickzoom.php' => config_path('quickzoom.php'),
        ], 'quickzoom-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'quickzoom-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallQuickZoomCommand::class,
            ]);
        }
    }
}




<?php

namespace Vendor\QuickZoom\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\QuickZoom\Console\Commands\InstallQuickZoomCommand;
use Vendor\QuickZoom\Contracts\ZoomServiceInterface;
use Vendor\QuickZoom\Services\ZoomService;

class QuickZoomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/quickzoom.php', 'quickzoom'
        );

        // Register the Zoom service as a singleton
        $this->app->singleton(ZoomServiceInterface::class, function ($app) {
            return new ZoomService();
        });

        // Register facade
        $this->app->alias(ZoomServiceInterface::class, 'quickzoom');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/quickzoom.php' => config_path('quickzoom.php'),
        ], 'quickzoom-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'quickzoom-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallQuickZoomCommand::class,
            ]);
        }
    }
}


<?php

namespace Vendor\QuickZoom\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array listMeetings(string $userId = 'me')
 * @method static array getMeeting(string $meetingId)
 * @method static array createMeeting(string $userId = 'me', array $data = [])
 * @method static array updateMeeting(string $meetingId, array $data = [])
 * @method static array deleteMeeting(string $meetingId)
 * @method static array endMeeting(string $meetingId)
 * @method static array listParticipants(string $meetingId)
 * @method static array registerParticipant(string $meetingId, array $participantData)
 * @method static array listRecordings(string $meetingId)
 * @method static array getUser(string $userId = 'me')
 * @method static array listUsers()
 * @method static array createUser(array $userData)
 * @method static array createWebinar(string $userId, array $data)
 * @method static array listWebinars(string $userId)
 *
 * @see \Vendor\QuickZoom\Services\ZoomService
 */
class QuickZoom extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'quickzoom';
    }
}


<?php

namespace Vendor\QuickZoom\Contracts;

interface ZoomServiceInterface
{
    /**
     * List all meetings
     */
    public function listMeetings(string $userId = 'me'): array;

    /**
     * Get meeting details
     */
    public function getMeeting(string $meetingId): array;

    /**
     * Create a meeting
     */
    public function createMeeting(string $userId = 'me', array $data = []): array;

    /**
     * Update a meeting
     */
    public function updateMeeting(string $meetingId, array $data = []): array;

    /**
     * Delete a meeting
     */
    public function deleteMeeting(string $meetingId): array;

    /**
     * End a meeting
     */
    public function endMeeting(string $meetingId): array;

    /**
     * List all meeting participants
     */
    public function listParticipants(string $meetingId): array;

    /**
     * Create a meeting registration
     */
    public function registerParticipant(string $meetingId, array $participantData): array;

    /**
     * List meeting recordings
     */
    public function listRecordings(string $meetingId): array;

    /**
     * Get user details
     */
    public function getUser(string $userId = 'me'): array;

    /**
     * List all users
     */
    public function listUsers(): array;

    /**
     * Create a user
     */
    public function createUser(array $userData): array;

    /**
     * Schedule a webinar
     */
    public function createWebinar(string $userId, array $data): array;

    /**
     * List webinars
     */
    public function listWebinars(string $userId): array;
}


<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Zoom API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are required to access the Zoom API.
    |
    */
    'api_key' => env('ZOOM_API_KEY', ''),
    'api_secret' => env('ZOOM_API_SECRET', ''),
    
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to the Zoom API
    |
    */
    'base_url' => env('ZOOM_BASE_URL', 'https://api.zoom.us/v2/'),
    'token_lifespan' => env('ZOOM_TOKEN_LIFESPAN', 60 * 60), // 1 hour in seconds
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Used to verify incoming webhook requests from Zoom
    |
    */
    'webhook_secret' => env('ZOOM_WEBHOOK_SECRET', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Configure routes for the package
    |
    */
    'routes' => [
        'prefix' => 'api/zoom',
        'middleware' => ['api', 'auth:sanctum'], // Default middleware
        'webhook_path' => 'webhook',
        'webhook_middleware' => ['api'], // Middleware for webhook endpoint
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Default Meeting Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for your Zoom meetings.
    |
    */
    'default_settings' => [
        'host_video' => true,
        'participant_video' => true,
        'join_before_host' => false,
        'mute_upon_entry' => true,
        'waiting_room' => true,
        'approval_type' => 0, // Automatically approve
        'audio' => 'both',
        'auto_recording' => 'none',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | Default timezone for Zoom meetings.
    |
    */
    'timezone' => env('ZOOM_TIMEZONE', config('app.timezone')),
];




<?php

namespace Vendor\QuickZoom\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Vendor\QuickZoom\Contracts\ZoomServiceInterface;

class ZoomService implements ZoomServiceInterface
{
    protected $client;
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;
    protected $tokenLifespan;

    public function __construct()
    {
        $this->apiKey = config('quickzoom.api_key');
        $this->apiSecret = config('quickzoom.api_secret');
        $this->baseUrl = config('quickzoom.base_url');
        $this->tokenLifespan = config('quickzoom.token_lifespan');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * Get JWT Token for Zoom API
     */
    protected function getToken()
    {
        return Cache::remember('zoom_token', $this->tokenLifespan, function () {
            $payload = [
                'iss' => $this->apiKey,
                'exp' => time() + $this->tokenLifespan,
            ];
            
            return JWT::encode($payload, $this->apiSecret, 'HS256');
        });
    }

    /**
     * Send request to Zoom API
     */
    protected function request($method, $endpoint, $data = [])
    {
        try {
            $token = $this->getToken();
            
            $response = $this->client->request($method, $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('QuickZoom API Error: ' . $e->getMessage(), [
                'method' => $method,
                'endpoint' => $endpoint,
                'data' => $data,
                'exception' => $e
            ]);
            
            throw $e;
        }
    }

    /**
     * List all meetings
     */
    public function listMeetings($userId = 'me'): array
    {
        return $this->request('GET', "users/{$userId}/meetings");
    }

    /**
     * Get meeting details
     */
    public function getMeeting($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}");
    }

    /**
     * Create a meeting
     */
    public function createMeeting($userId = 'me', array $data = []): array
    {
        $defaultData = [
            'topic' => 'New Meeting',
            'type' => 2, // Scheduled meeting
            'start_time' => Carbon::now()->toDateTimeString(),
            'duration' => 60, // 60 minutes
            'timezone' => config('quickzoom.timezone'),
            'settings' => config('quickzoom.default_settings', []),
        ];

        return $this->request('POST', "users/{$userId}/meetings", array_merge($defaultData, $data));
    }

    /**
     * Update a meeting
     */
    public function updateMeeting($meetingId, array $data = []): array
    {
        return $this->request('PATCH', "meetings/{$meetingId}", $data);
    }

    /**
     * Delete a meeting
     */
    public function deleteMeeting($meetingId): array
    {
        return $this->request('DELETE', "meetings/{$meetingId}");
    }

    /**
     * End a meeting
     */
    public function endMeeting($meetingId): array
    {
        return $this->request('PUT', "meetings/{$meetingId}/status", [
            'action' => 'end'
        ]);
    }

    /**
     * List all meeting participants
     */
    public function listParticipants($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}/participants");
    }

    /**
     * Create a meeting registration
     */
    public function registerParticipant($meetingId, array $participantData): array
    {
        return $this->request('POST', "meetings/{$meetingId}/registrants", $participantData);
    }

    /**
     * List meeting recordings
     */
    public function listRecordings($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}/recordings");
    }

    /**
     * Get user details
     */
    public function getUser($userId = 'me'): array
    {
        return $this->request('GET', "users/{$userId}");
    }

    /**
     * List all users
     */
    public function listUsers(): array
    {
        return $this->request('GET', "users");
    }

    /**
     * Create a user
     */
    public function createUser(array $userData): array
    {
        return $this->request('POST', "users", $userData);
    }

    /**
     * Schedule a webinar
     */
    public function createWebinar($userId, array $data): array
    {
        return $this->request('POST', "users/{$userId}/webinars", $data);
    }

    /**
     * List webinars
     */
    public function listWebinars($userId): array
    {
        return $this->request('GET', "users/{$userId}/webinars");
    }
}

<?php

namespace Vendor\QuickZoom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Vendor\QuickZoom\Contracts\ZoomServiceInterface;

class ZoomController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomServiceInterface $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Display a listing of meetings.
     */
    public function index()
    {
        try {
            $meetings = $this->zoomService->listMeetings();
            return response()->json([
                'success' => true,
                'data' => $meetings
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Store a newly created meeting.
     */
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

    /**
     * Display the specified meeting.
     */
    public function show($id)
    {
        try {
            $meeting = $this->zoomService->getMeeting($id);
            return response()->json([
                'success' => true,
                'data' => $meeting
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Update the specified meeting.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'nullable|string|max:200',
            'start_time' => 'nullable|date',
            'duration' => 'nullable|integer|min:15|max:300',
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
            $meeting = $this->zoomService->updateMeeting($id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Meeting updated successfully',
                'data' => $meeting
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Remove the specified meeting.
     */
    public function destroy($id)
    {
        try {
            $this->zoomService->deleteMeeting($id);
            return response()->json([
                'success' => true,
                'message' => 'Meeting deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * End a meeting.
     */
    public function endMeeting($id)
    {
        try {
            $this->zoomService->endMeeting($id);
            return response()->json([
                'success' => true,
                'message' => 'Meeting ended successfully'
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get all participants of a meeting.
     */
    public function participants($id)
    {
        try {
            $participants = $this->zoomService->listParticipants($id);
            return response()->json([
                'success' => true,
                'data' => $participants
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get all recordings of a meeting.
     */
    public function recordings($id)
    {
        try {
            $recordings = $this->zoomService->listRecordings($id);
            return response()->json([
                'success' => true,
                'data' => $recordings
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Register a participant for a meeting.
     */
    public function registerParticipant(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration = $this->zoomService->registerParticipant($id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Participant registered successfully',
                'data' => $registration
            ], 201);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Handle API errors.
     */
    private function handleError(\Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Zoom API Error',
            'error' => $e->getMessage()
        ], 500);
    }
}


<?php

namespace Vendor\QuickZoom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Vendor\QuickZoom\Events\ZoomMeetingEnded;
use Vendor\QuickZoom\Events\ZoomMeetingStarted;
use Vendor\QuickZoom\Events\ZoomParticipantJoined;
use Vendor\QuickZoom\Events\ZoomParticipantLeft;

class ZoomWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? '';
        
        // Log the webhook for debugging
        Log::info('QuickZoom Webhook received', [
            'event' => $event,
            'payload' => $payload
        ]);
        
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
                // Handle other events or simply log them
                Log::info('Unhandled Zoom webhook event', [
                    'event' => $event
                ]);
                break;
        }
        
        return response()->json(['message' => 'Webhook processed successfully']);
    }
}



<?php

namespace Vendor\QuickZoom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyZoomWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If webhook verification is not enabled, allow the request
        if (!config('quickzoom.webhook_secret')) {
            return $next($request);
        }

        $signature = $request->header('x-zm-signature');
        $timestamp = $request->header('x-zm-request-timestamp');
        $webhookToken = config('quickzoom.webhook_secret');
        
        // If no signature or timestamp is provided, deny the request
        if (!$signature || !$timestamp) {
            Log::warning('QuickZoom webhook verification failed: Missing headers', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all()
            ]);
            
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // Calculate expected signature
        $message = 'v0:' . $timestamp . ':' . $request->getContent();
        $hashForVerify = 'v0=' . hash_hmac('sha256', $message, $webhookToken);
        
        // If signatures don't match, deny the request
        if (!hash_equals($signature, $hashForVerify)) {
            Log::warning('QuickZoom webhook verification failed: Invalid signature', [
                'ip' => $request->ip(),
                'received' => $signature,
                'expected' => $hashForVerify
            ]);
            
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}


<?php

namespace Vendor\QuickZoom\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZoomMeetingStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}

<?php

namespace Vendor\QuickZoom\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZoomMeetingEnded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}

<?php

namespace Vendor\QuickZoom\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZoomParticipantJoined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}

<?php

namespace Vendor\QuickZoom\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZoomParticipantLeft
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('zoom_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('topic');
            $table->text('agenda')->nullable();
            $table->string('start_url');
            $table->string('join_url');
            $table->string('password')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration');
            $table->string('status')->default('waiting');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('zoom_meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zoom_meeting_id')->constrained('zoom_meetings')->onDelete('cascade');
            $table->string('participant_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->dateTime('join_time')->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();
        });

        Schema::create('zoom_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zoom_meeting_id')->constrained('zoom_meetings')->onDelete('cascade');
            $table->string('recording_id')->unique();
            $table->string('meeting_id');
            $table->string('recording_type');
            $table->string('download_url');
            $table->string('password')->nullable();
            $table->dateTime('recording_start');
            $table->dateTime('recording_end');
            $table->integer('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_recordings');
        Schema::dropIfExists('zoom_meeting_participants');
        Schema::dropIfExists('zoom_meetings');
    }
};


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_id',
        'user_id',
        'topic',
        'agenda',
        'start_url',
        'join_url',
        'password',
        'start_time',
        'duration',
        'status',
        'settings',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participants()
    {
        return $this->hasMany(ZoomMeetingParticipant::class);
    }

    public function recordings()
    {
        return $this->hasMany(ZoomRecording::class);
    }
}

class ZoomMeetingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_meeting_id',
        'participant_id',
        'name',
        'email',
        'join_time',
        'leave_time',
        'duration',
    ];

    protected $casts = [
        'join_time' => 'datetime',
        'leave_time' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(ZoomMeeting::class, 'zoom_meeting_id');
    }
}

class ZoomRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_meeting_id',
        'recording_id',
        'meeting_id',
        'recording_type',
        'download_url',
        'password',
        'recording_start',
        'recording_end',
        'file_size',
        'file_type',
    ];

    protected $casts = [
        'recording_start' => 'datetime',
        'recording_end' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(ZoomMeeting::class, 'zoom_meeting_id');
    }
}


# Laravel Zoom Integration

This package provides a complete solution to integrate Zoom video conferencing with your Laravel application. It offers a comprehensive set of features to manage Zoom meetings, participants, recordings, and more.

## Features

- Create, read, update, and delete Zoom meetings
- List and manage meeting participants
- Access and manage meeting recordings
- Handle Zoom webhooks
- Store meeting data in your database
- Easy-to-use API endpoints

## Installation

### 1. Install the package via Composer:

```bash
composer require firebase/php-jwt
```

### 2. Add the Zoom Service Provider to your `config/app.php` file:

```php
'providers' => [
    // ...
    App\Providers\ZoomServiceProvider::class,
],
```

### 3. Publish the configuration file:

```bash
php artisan vendor:publish --tag=zoom-config
```

### 4. Add the following variables to your `.env` file:

```
ZOOM_API_KEY=your_zoom_api_key
ZOOM_API_SECRET=your_zoom_api_secret
ZOOM_WEBHOOK_SECRET=your_zoom_webhook_secret  # Optional, only if using webhooks
```

### 5. Run the migrations:

```bash
php artisan migrate
```

## Usage

### Setting Up Zoom API Credentials

1. Go to the [Zoom App Marketplace](https://marketplace.zoom.us/)
2. Click "Develop" in the upper right and select "Build App"
3. Choose "JWT" as the app type
4. Fill in the required information and create your app
5. Copy the API Key and API Secret to your `.env` file

### Basic Meeting Management

#### Creating a Meeting

```php
use App\Services\Zoom\ZoomService;

$zoomService = app(ZoomService::class);

$meeting = $zoomService->createMeeting('me', [
    'topic' => 'Team Meeting',
    'start_time' => '2025-03-25T10:00:00Z',
    'duration' => 60, // minutes
    'agenda' => 'Discuss project progress',
    'settings' => [
        'host_video' => true,
        'participant_video' => true,
        'join_before_host' => false,
        'mute_upon_entry' => true,
        'waiting_room' => true,
    ]
]);
```

#### Getting Meeting Details

```php
$meeting = $zoomService->getMeeting($meetingId);
```

#### Updating a Meeting

```php
$updatedMeeting = $zoomService->updateMeeting($meetingId, [
    'topic' => 'Updated Team Meeting',
    'duration' => 90
]);
```

#### Deleting a Meeting

```php
$zoomService->deleteMeeting($meetingId);
```

#### Ending a Meeting

```php
$zoomService->endMeeting($meetingId);
```

### Working with Participants

#### Listing Participants

```php
$participants = $zoomService->listParticipants($meetingId);
```

#### Registering a Participant

```php
$registration = $zoomService->registerParticipant($meetingId, [
    'email' => 'participant@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe'
]);
```

### Working with Recordings

```php
$recordings = $zoomService->listRecordings($meetingId);
```

### Using API Routes

This package includes a set of API routes that you can use to manage Zoom meetings:

- `GET /api/zoom/meetings` - List all meetings
- `POST /api/zoom/meetings` - Create a new meeting
- `GET /api/zoom/meetings/{id}` - Get a specific meeting
- `PUT /api/zoom/meetings/{id}` - Update a meeting
- `DELETE /api/zoom/meetings/{id}` - Delete a meeting
- `PUT /api/zoom/meetings/{id}/end` - End a meeting
- `GET /api/zoom/meetings/{id}/participants` - Get meeting participants
- `GET /api/zoom/meetings/{id}/recordings` - Get meeting recordings
- `POST /api/zoom/meetings/{id}/register` - Register a participant

### Working with Webhooks

To receive Zoom webhooks:

1. Add the webhook route to your `routes/api.php` file:

```php
Route::post('zoom/webhook', [App\Http\Controllers\ZoomWebhookController::class, 'handle']);
```

2. Set up the webhook in your Zoom app settings:
   - Log in to the [Zoom App Marketplace](https://marketplace.zoom.us/)
   - Navigate to your app
   - Go to the "Feature" tab
   - Click "Event Subscriptions" and add a subscription
   - Enter your webhook URL (e.g., `https://yourdomain.com/api/zoom/webhook`)
   - Select the events you want to subscribe to

3. Handle the webhook events in your application by listening to the dispatched events:

```php
// In your EventServiceProvider.php
protected $listen = [
    'App\Events\ZoomMeetingStarted' => [
        'App\Listeners\HandleZoomMeetingStarted',
    ],
    'App\Events\ZoomMeetingEnded' => [
        'App\Listeners\HandleZoomMeetingEnded',
    ],
    'App\Events\ZoomParticipantJoined' => [
        'App\Listeners\HandleZoomParticipantJoined',
    ],
    'App\Events\ZoomParticipantLeft' => [
        'App\Listeners\HandleZoomParticipantLeft',
    ],
];
```

## Database Models

The package includes the following database models:

- `ZoomMeeting` - Stores meeting information
- `ZoomMeetingParticipant` - Stores participant information
- `ZoomRecording` - Stores recording information

You can use these models to query and manipulate meeting data in your application.

## Logging

All API errors are logged to the Laravel log file. You can view these logs to troubleshoot any issues with the Zoom API integration.

## Security

This package uses JWT authentication to securely communicate with the Zoom API. The token is automatically generated and cached for optimal performance.

## License

This package is open-sourced software licensed under the MIT license.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

If you need any help, please open an issue on the GitHub repository.