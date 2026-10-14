<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('kind', ['SERVICE', 'PORTFOLIO', 'PAGE']);
            $table->string('title', 180);
            $table->string('slug', 120);
            $table->string('excerpt', 500);
            $table->text('body');
            $table->string('category', 100)->nullable();
            $table->string('art_style', 20)->default('orbit');
            $table->string('seo_title', 100)->nullable();
            $table->string('seo_description', 180)->nullable();
            $table->json('service_ids')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('is_example')->default(true);
            $table->unsignedInteger('lock_version')->default(1);
            $table->string('published_slug', 120)->nullable();
            $table->json('published_snapshot')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('published_version')->nullable();
            $table->unsignedInteger('published_position')->default(0);
            $table->boolean('published_featured')->default(false);
            $table->timestamps();
            $table->unique(['kind', 'slug']);
            $table->unique(['kind', 'published_slug']);
            $table->index(['kind', 'published_at']);
        });
        Schema::create('content_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_entry_id')->constrained()->restrictOnDelete();
            $table->string('path');
            $table->string('alt', 180);
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('content_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('content_entry_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('event', 60);
            $table->json('metadata');
            $table->timestamps();
        });
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->default('studio');
            $table->string('responsible_name', 120)->nullable();
            $table->string('contact_email', 180)->nullable();
            $table->string('contact_address', 250)->nullable();
            $table->boolean('provisional_legal')->default(true);
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->string('name', 120);
            $table->string('email', 180);
            $table->string('subject', 180);
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('content_changes');
        Schema::dropIfExists('content_media');
        Schema::dropIfExists('content_entries');
    }
};
