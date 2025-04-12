<?php
namespace QuickZoom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'duration'
    ];

    protected $casts = [
        'join_time' => 'datetime',
        'leave_time' => 'datetime'
    ];

    public function meeting()
    {
        return $this->belongsTo(ZoomMeeting::class);
    }
}