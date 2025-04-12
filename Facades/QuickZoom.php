<?php
namespace QuickZoom\Facades;

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
 */
class QuickZoom extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quickzoom';
    }
}