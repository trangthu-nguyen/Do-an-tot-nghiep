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
    Schema::create('notifications', function (Blueprint $table) {
        $table->id('notification_id');
        $table->string('user_type', 20);  // customer, staff, admin
        $table->integer('user_id');
        $table->string('title', 100);
        $table->text('content');
        $table->tinyInteger('is_read')->default(0);
        $table->dateTime('created_at')->useCurrent();
    });
}

public function down(): void
{
    Schema::dropIfExists('notifications');
}
};
