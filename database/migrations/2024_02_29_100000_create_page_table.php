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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('description', 400)->nullable()->default('');
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('author_id');
            $table->string('status', 60)->default('published');
            $table->string('view_layout')->nullable();
            $table->datetime('published_at')->nullable();
            $table->string('lock_password')->nullable();
            $table->string('layout', 255)->nullable();
            $table->string('app_before', 500)->nullable();
            $table->string('app_after', 500)->nullable();
            $table->longText('data')->nullable();
            $table->text('js')->nullable();
            $table->text('css')->nullable();
            $table->text('custom_js')->nullable();
            $table->text('custom_css')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
