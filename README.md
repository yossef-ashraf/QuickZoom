```markdown
# QuickZoom - Laravel Zoom Integration Package

[![Latest Version](https://img.shields.io/packagist/v/quickhelper/quickzoom.svg?style=flat-square)](https://packagist.org/packages/quickhelper/quickzoom)
[![Total Downloads](https://img.shields.io/packagist/dt/quickhelper/quickzoom.svg?style=flat-square)](https://packagist.org/packages/quickhelper/quickzoom)
[![License](https://img.shields.io/packagist/l/quickhelper/quickzoom.svg?style=flat-square)](https://packagist.org/packages/quickhelper/quickzoom)

A complete solution for integrating Zoom video conferencing into Laravel applications. Manage meetings, webinars, participants, and recordings with an elegant API.

## Features

✅ **Complete Meeting Management** - Create, update, delete, and list Zoom meetings  
✅ **Webinar Support** - Schedule and manage webinars  
✅ **Participant Tracking** - Track meeting participants and attendance  
✅ **Recording Management** - Access and manage meeting recordings  
✅ **Webhook Integration** - Real-time event notifications  
✅ **Database Storage** - Store meeting data locally  
✅ **Laravel Integration** - Native Laravel service provider, facades, and events  

## Requirements

- PHP 8.0+
- Laravel 9.x or 10.x
- Zoom JWT App Credentials

## Installation

1. Install via Composer:

```bash
composer require quickhelper/quickzoom
```

2. Publish config and migrations:

```bash
php artisan vendor:publish --provider="QuickZoom\Providers\QuickZoomServiceProvider"
```

3. Add Zoom credentials to your `.env`:

```ini
ZOOM_API_KEY=your_zoom_api_key
ZOOM_API_SECRET=your_zoom_api_secret
ZOOM_WEBHOOK_SECRET=your_webhook_secret  # Optional
```

4. Run migrations:

```bash
php artisan migrate
```

## Usage

### Basic Meeting Management

```php
use QuickZoom\Facades\QuickZoom;

// Create a meeting
$meeting = QuickZoom::createMeeting('me', [
    'topic' => 'Team Meeting',
    'start_time' => now()->addDay()->toIso8601String(),
    'duration' => 60,
    'agenda' => 'Quarterly planning session'
]);

// List meetings
$meetings = QuickZoom::listMeetings();

// Get meeting details
$meeting = QuickZoom::getMeeting($meetingId);

// End a meeting
QuickZoom::endMeeting($meetingId);
```

### Webhook Setup

1. Configure your Zoom app webhook in the Zoom Marketplace
2. Add the webhook URL to your Zoom app settings (typically `https://yourdomain.com/api/zoom/webhook`)
3. Listen for events in your Laravel application:

```php
// In your EventServiceProvider
protected $listen = [
    \QuickZoom\Events\ZoomMeetingStarted::class => [
        \App\Listeners\HandleMeetingStarted::class,
    ],
    \QuickZoom\Events\ZoomParticipantJoined::class => [
        \App\Listeners\HandleParticipantJoined::class,
    ],
    // Other events...
];
```

### Available Methods

| Method | Description |
|--------|-------------|
| `listMeetings()` | List all meetings |
| `getMeeting()` | Get meeting details |
| `createMeeting()` | Schedule a new meeting |
| `updateMeeting()` | Update meeting details |
| `deleteMeeting()` | Delete a meeting |
| `endMeeting()` | End an ongoing meeting |
| `listParticipants()` | List meeting participants |
| `registerParticipant()` | Register participant for meeting |
| `listRecordings()` | Get meeting recordings |
| `createWebinar()` | Schedule a webinar |
| `listWebinars()` | List scheduled webinars |

## Configuration

Publish the configuration file to customize:

```bash
php artisan vendor:publish --tag=quickzoom-config
```

Available configuration options:

```php
return [
    'api_key' => env('ZOOM_API_KEY'),
    'api_secret' => env('ZOOM_API_SECRET'),
    'base_url' => 'https://api.zoom.us/v2/',
    'webhook_secret' => env('ZOOM_WEBHOOK_SECRET'),
    
    'default_settings' => [
        'host_video' => true,
        'participant_video' => true,
        // ... other meeting defaults
    ],
    
    'routes' => [
        'prefix' => 'api/zoom',
        'middleware' => ['api', 'auth:sanctum'],
        'webhook_path' => 'webhook',
    ]
];
```

## Security

- Uses JWT authentication with Zoom API
- Webhook signature verification
- Encrypted API communication
- Token rotation and caching

## Testing

Run the tests with:

```bash
composer test
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for recent changes.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md) for more information.

## Support

For issues and feature requests, please [open an issue](https://github.com/vendor/quickzoom/issues).
```

