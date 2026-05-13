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
        // 1. Sửa bảng bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
            $table->decimal('total_amount', 15, 2)->default(0)->after('staff_id');
        });

        // 2. Tạo bảng booking_details
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('service_id');
            $table->decimal('price', 15, 2);

            $table->foreign('booking_id')
                  ->references('booking_id')
                  ->on('bookings')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('service_id')
                  ->on('services')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->after('staff_id');
            $table->dropColumn('total_amount');
            $table->foreign('service_id')
                  ->references('service_id')
                  ->on('services')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
};
