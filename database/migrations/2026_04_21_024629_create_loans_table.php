<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('borrower_id')->constrained()->onDelete('cascade');
        $table->decimal('principle_amount', 15, 2);
        $table->decimal('interest_rate', 5, 2);
        $table->date('release_date');
        $table->date('due_date');
        $table->decimal('outstanding_balance', 15, 2);
        $table->string('status')->default('Active'); // Active, Overdue, Completed
        $table->foreignId('admin_id')->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
