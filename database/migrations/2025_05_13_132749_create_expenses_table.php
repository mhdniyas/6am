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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->date('date');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('receipt_path')->nullable(); // Optional path to an uploaded receipt
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Add foreign key constraint after the teams table is created
        Schema::table('expenses', function (Blueprint $table) {
            // Only add the foreign key if the teams table exists
            if (Schema::hasTable('teams')) {
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            }
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
