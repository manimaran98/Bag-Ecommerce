<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('guest_name', 100)->nullable();
            $table->string('guest_email', 100)->nullable();
            $table->text('body');
            $table->string('status', 20)->default('open');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
