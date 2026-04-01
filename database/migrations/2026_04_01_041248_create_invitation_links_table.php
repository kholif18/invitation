<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitation_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->enum('type', ['personalized', 'general'])->default('personalized');
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->integer('views')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['invitation_id', 'type']);
            $table->index('token');
        });

        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['whatsapp', 'email', 'sms'])->default('whatsapp');
            $table->text('message');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_links');
        Schema::dropIfExists('message_logs');
    }
};
