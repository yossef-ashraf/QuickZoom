<?php
namespace QuickZoom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'file_type'
    ];

    protected $casts = [
        'recording_start' => 'datetime',
        'recording_end' => 'datetime'
    ];

    public function meeting()
    {
        return $this->belongsTo(ZoomMeeting::class);
    }
}