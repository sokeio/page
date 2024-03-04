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
        if (Schema::hasColumn('pages', 'views')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('views');
            });
        }
        if (Schema::hasColumn('pages', 'hide_header')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('hide_header');
            });
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->string('view_layout')->nullable();
            $table->boolean('is_container')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        if (Schema::hasColumn('pages', 'view_layout')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('view_layout');
            });
        }
        if (Schema::hasColumn('pages', 'is_container')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('is_container');
            });
        }
        if (!Schema::hasColumn('pages', 'views')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->integer('views')->default(0);
            });
        }
    }
};
