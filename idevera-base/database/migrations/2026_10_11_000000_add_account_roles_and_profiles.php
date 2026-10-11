<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['CLIENT', 'STAFF', 'OWNER'])->default('CLIENT');
        });

        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->enum('status', ['PENDING_VERIFICATION', 'ACTIVE', 'SUSPENDED', 'DEACTIVATED']);
            $table->timestamps();
        });

        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED']);
            $table->json('specialties');
            $table->timestamps();
        });

        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('target_user_id')->constrained('users')->restrictOnDelete();
            $table->string('event', 80);
            $table->json('metadata');
            $table->timestamps();
            $table->index(['target_user_id', 'created_at']);
        });

        // Existing accounts remain clients; verification is preserved.
        DB::table('users')->orderBy('id')->chunkById(200, function ($users) {
            foreach ($users as $user) {
                DB::table('client_profiles')->insert([
                    'user_id' => $user->id,
                    'status' => $user->email_verified_at ? 'ACTIVE' : 'PENDING_VERIFICATION',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('client_profiles');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
