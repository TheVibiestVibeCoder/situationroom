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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->string('category');      // 'bildung', 'social', 'individuell', 'politik', 'kreativ'
            $table->text('text');
            $table->boolean('visible')->default(true);
            $table->boolean('focused')->default(false);
            $table->timestamps();

            $table->index('workspace_id');
            $table->index(['workspace_id', 'category']);
            $table->index(['workspace_id', 'visible']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
