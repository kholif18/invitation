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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('category')->default('classic');
            $table->text('description')->nullable();
            
            // Template files
            $table->string('blade_file')->nullable(); // Path to blade template
            $table->string('css_file')->nullable(); // Path to CSS file
            $table->string('js_file')->nullable(); // Path to JS file
            
            // Template configuration
            $table->json('config')->nullable(); // JSON configuration for template options
            $table->json('settings')->nullable(); // Default settings
            
            // Version and status
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
