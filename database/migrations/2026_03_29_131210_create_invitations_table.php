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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            
            // Template
            $table->foreignId('template_id')->constrained()->onDelete('restrict');
            $table->string('template_slug')->nullable();
            $table->json('template_settings')->nullable();
            
            // Groom Information
            $table->string('groom_full_name');
            $table->string('groom_nickname');
            $table->string('groom_father_name');
            $table->string('groom_mother_name');
            $table->text('groom_address');
            $table->string('groom_photo')->nullable();
            
            // Bride Information
            $table->string('bride_full_name');
            $table->string('bride_nickname');
            $table->string('bride_father_name');
            $table->string('bride_mother_name');
            $table->text('bride_address');
            $table->string('bride_photo')->nullable();
            
            // Akad Nikah
            $table->boolean('has_akad')->default(false);
            $table->date('akad_date')->nullable();
            $table->time('akad_time')->nullable();
            $table->string('akad_location')->nullable();
            
            // Reception
            $table->boolean('has_reception')->default(false);
            $table->json('receptions')->nullable(); // Store array of receptions
            
            // Maps
            $table->json('maps')->nullable(); // Store array of map links
            
            // Gift
            $table->boolean('has_gift')->default(true);
            $table->string('gift_image')->nullable();
            $table->json('bank_accounts')->nullable(); // Store array of bank accounts
            
            // Gallery
            $table->boolean('has_gallery')->default(true);
            $table->json('gallery_photos')->nullable();
            $table->json('gallery_videos')->nullable();
            
            // Features
            $table->boolean('is_wish_active')->default(true);
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            // Slug for unique URL
            $table->string('slug')->unique();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
