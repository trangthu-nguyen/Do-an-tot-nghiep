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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id('booking_id');

        $table->unsignedBigInteger('customer_id');
        $table->unsignedBigInteger('staff_id')->nullable();
        $table->unsignedBigInteger('service_id');

        $table->date('booking_date');
        $table->time('booking_time');

        $table->string('address', 255)->nullable();
        $table->string('note', 255)->nullable();
        $table->string('status', 50)->default('pending');

        $table->foreign('customer_id')
              ->references('customer_id')
              ->on('customers')
              ->onDelete('cascade')
              ->onUpdate('cascade');

        $table->foreign('staff_id')
              ->references('staff_id')
              ->on('staffs')
              ->onDelete('set null')
              ->onUpdate('cascade');

        $table->foreign('service_id')
              ->references('service_id')
              ->on('services')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('bookings');
}
};
