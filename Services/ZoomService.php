<?php

namespace YourVendor\QuickZoom\Services;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Zoom API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Zoom API Secret
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Zoom API Base URL
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * HTTP Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $baseUrl
     */
    public function __construct($apiKey, $apiSecret, $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;
        $this->client = new Client();
    }

    /**
     * Generate JWT Token for Zoom API
     *
     * @return string
     */
    protected function generateToken()
    {
        $token = [
            'iss' => $this->apiKey,
            'exp' => time() + config('quickzoom.token_expiration', 60 * 60),
        ];

        return JWT::encode($token, $this->apiSecret, 'HS256');
    }

    /**
     * Make a request to the Zoom API
     *
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    protected function request($method, $endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;
        $token = $this->generateToken();

        try {
            $response = $this->client->request($method, $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $params,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('QuickZoom API Error: ' . $e->getMessage());
            throw new \Exception('Zoom API Error: ' . $e->getMessage());
        }
    }

    /**
     * Create a new Zoom meeting
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createMeeting(array $data)
    {
        return $this->request('POST', '/users/me/meetings', $data);
    }

    /**
     * Get a specific Zoom meeting
     *
     * @param string $meetingId
     * @return array
     * @throws \Exception
     */
    public function getMeeting($meetingId)
    {
        return $this->request('GET', '/meetings/' . $meetingId);
    }

    /**
     * Update a Zoom meeting
     *
     * @param string $meetingId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateMeeting($meetingId, array $data)
    {
        return $this->request('PATCH', '/meetings/' . $meetingId, $data);
    }

    /**
     * Delete a Zoom meeting
     *
     * @param string $meetingId
     * @return array
     * @throws \Exception
     */
    public function deleteMeeting($meetingId)
    {
        return $this->request('DELETE', '/meetings/' . $meetingId);
    }

    /**
     * List all meetings for the current user
     *
     * @param array $query
     * @return array
     * @throws \Exception
     */
    public function listMeetings(array $query = [])
    {
        return $this->request('GET', '/users/me/meetings?' . http_build_query($query));
    }

    /**
     * Get meeting participants
     *
     * @param string $meetingId
     * @return array
     * @throws \Exception
     */
    public function getMeetingParticipants($meetingId)
    {
        return $this->request('GET', '/report/meetings/' . $meetingId . '/participants');
    }
}