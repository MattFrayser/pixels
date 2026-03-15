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
        Schema::create('pixels', function (Blueprint $table) {
            $table->foreignId('canvas_id')->constrained('canvases')->cascadeOnDelete();
            $table->foreignId('user_id')->constained('users');
            $table->integer('x');
            $table->integer('y');
            $table->char('color', 7);
            $table->timestamps();

            $table->index(['canvas_id', 'x', 'y', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pixels');
    }
};
