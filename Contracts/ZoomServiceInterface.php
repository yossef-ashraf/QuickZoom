<?php
namespace QuickZoom\Contracts;

interface ZoomServiceInterface
{
    public function listMeetings(string $userId = 'me'): array;
    public function getMeeting(string $meetingId): array;
    public function createMeeting(string $userId = 'me', array $data = []): array;
    public function updateMeeting(string $meetingId, array $data = []): array;
    public function deleteMeeting(string $meetingId): array;
    public function endMeeting(string $meetingId): array;
    public function listParticipants(string $meetingId): array;
    public function registerParticipant(string $meetingId, array $participantData): array;
    public function listRecordings(string $meetingId): array;
    public function getUser(string $userId = 'me'): array;
    public function listUsers(): array;
    public function createUser(array $userData): array;
    public function createWebinar(string $userId, array $data): array;
    public function listWebinars(string $userId): array;
}