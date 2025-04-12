<?php
namespace QuickZoom\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_id', 'user_id', 'topic', 'agenda', 'start_url', 'join_url',
        'password', 'start_time', 'duration', 'status', 'settings'
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