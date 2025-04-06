# QuickZoom

QuickZoom is a Laravel package that provides easy integration with the Zoom API for creating, managing, and interacting with Zoom meetings.

## Installation

You can install the package via composer:

```bash
composer require yourvendor/quickzoom
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=quickzoom-config
```

Add your Zoom API credentials to your `.env` file:

```
ZOOM_API_KEY=your_zoom_api_key
ZOOM_API_SECRET=your_zoom_api_secret
ZOOM_BASE_URL=https://api.zoom.us/v2
```

## Usage

### Creating a meeting

```php
use YourVendor\QuickZoom\Facades\QuickZoom;

$meeting = QuickZoom::createMeeting([
    'topic' => 'Test Meeting',
    'type' => 2, // Scheduled meeting
    'start_time' => '2025-04-08T14:00:00Z',
    'duration' => 30, // 30 minutes
    'timezone' => 'UTC',
    'password' => 'securepass',
    'settings' => [
        'host_video' => true,
        'participant_video' => true,
        'waiting_room' => true,
    ]
]);
```

### Get meeting information

```php
$meeting = QuickZoom::getMeeting('meeting_id');
```

### Update a meeting

```php
$meeting = QuickZoom::updateMeeting('meeting_id', [
    'topic' => 'Updated Meeting Topic',
    'duration' => 45,
]);
```

### Delete a meeting

```php
QuickZoom::deleteMeeting('meeting_id');
```

### List meetings

```php
$meetings = QuickZoom::listMeetings([
    'type' => 'scheduled',
    'page_size' => 30,
]);
```

### Get meeting participants

```php
$participants = QuickZoom::getMeetingParticipants('meeting_id');
```

## Contributing

Contributions are welcome and will be fully credited.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.