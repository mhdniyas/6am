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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->string('assigned_to');
            $table->date('due_date');
            $table->boolean('done')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('sponsor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Add foreign key constraints after other tables are created
        Schema::table('tasks', function (Blueprint $table) {
            // Only add the foreign keys if the referenced tables exist
            if (Schema::hasTable('teams')) {
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            }
            
            if (Schema::hasTable('sponsors')) {
                $table->foreign('sponsor_id')->references('id')->on('sponsors')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
