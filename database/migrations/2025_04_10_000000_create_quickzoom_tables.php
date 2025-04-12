<?php
// quickzoom/src/database/migrations/2023_01_01_000000_create_quickzoom_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
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

    public function down()
    {
        Schema::dropIfExists('zoom_recordings');
        Schema::dropIfExists('zoom_meeting_participants');
        Schema::dropIfExists('zoom_meetings');
    }
};