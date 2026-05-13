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
    Schema::create('payments', function (Blueprint $table) {
        $table->id('payment_id');
        $table->unsignedBigInteger('booking_id');

        $table->string('payment_method', 50);
        $table->decimal('amount', 10, 2);
        $table->string('payment_status', 50)->default('unpaid');
        $table->dateTime('payment_date')->useCurrent();

        $table->foreign('booking_id')
              ->references('booking_id')
              ->on('bookings')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
