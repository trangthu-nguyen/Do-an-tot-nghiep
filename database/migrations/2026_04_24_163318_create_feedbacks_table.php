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
    Schema::create('feedbacks', function (Blueprint $table) {
        $table->id('feedback_id');

        $table->unsignedBigInteger('booking_id');
        $table->unsignedBigInteger('customer_id');

        $table->integer('rating');
        $table->text('comment')->nullable();
        $table->dateTime('created_at')->useCurrent();

        $table->foreign('booking_id')
              ->references('booking_id')
              ->on('bookings')
              ->onDelete('cascade')
              ->onUpdate('cascade');

        $table->foreign('customer_id')
              ->references('customer_id')
              ->on('customers')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('feedbacks');
}
};
