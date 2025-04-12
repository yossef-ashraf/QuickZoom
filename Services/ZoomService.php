<?php
namespace QuickZoom\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use QuickZoom\Contracts\ZoomServiceInterface;

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

    protected function request($method, $endpoint, $data = [])
    {
        try {
            $token = $this->getToken();
            
            $response = $this->client->request($method, $endpoint, [
                'headers' => ['Authorization' => 'Bearer ' . $token],
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

    public function listMeetings($userId = 'me'): array
    {
        return $this->request('GET', "users/{$userId}/meetings");
    }

    public function getMeeting($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}");
    }

    public function createMeeting($userId = 'me', array $data = []): array
    {
        $defaultData = [
            'topic' => 'New Meeting',
            'type' => 2,
            'start_time' => Carbon::now()->toDateTimeString(),
            'duration' => 60,
            'timezone' => config('quickzoom.timezone'),
            'settings' => config('quickzoom.default_settings', []),
        ];

        return $this->request('POST', "users/{$userId}/meetings", array_merge($defaultData, $data));
    }

    public function updateMeeting($meetingId, array $data = []): array
    {
        return $this->request('PATCH', "meetings/{$meetingId}", $data);
    }

    public function deleteMeeting($meetingId): array
    {
        return $this->request('DELETE', "meetings/{$meetingId}");
    }

    public function endMeeting($meetingId): array
    {
        return $this->request('PUT', "meetings/{$meetingId}/status", ['action' => 'end']);
    }

    public function listParticipants($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}/participants");
    }

    public function registerParticipant($meetingId, array $participantData): array
    {
        return $this->request('POST', "meetings/{$meetingId}/registrants", $participantData);
    }

    public function listRecordings($meetingId): array
    {
        return $this->request('GET', "meetings/{$meetingId}/recordings");
    }

    public function getUser($userId = 'me'): array
    {
        return $this->request('GET', "users/{$userId}");
    }

    public function listUsers(): array
    {
        return $this->request('GET', "users");
    }

    public function createUser(array $userData): array
    {
        return $this->request('POST', "users", $userData);
    }

    public function createWebinar($userId, array $data): array
    {
        return $this->request('POST', "users/{$userId}/webinars", $data);
    }

    public function listWebinars($userId): array
    {
        return $this->request('GET', "users/{$userId}/webinars");
    }
}