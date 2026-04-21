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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_id')->constrained()->onDelete('cascade');
        $table->date('payment_date');
        $table->decimal('amount_paid', 15, 2);
        $table->decimal('interest_added', 15, 2)->default(0);
        $table->decimal('new_balance', 15, 2);
        $table->foreignId('admin_id')->constrained('users');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
