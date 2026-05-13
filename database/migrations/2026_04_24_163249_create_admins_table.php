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
    Schema::create('admins', function (Blueprint $table) {
        $table->id('admin_id');
        $table->string('full_name', 100);
        $table->string('email', 100)->unique();
        $table->string('password', 255);
        $table->dateTime('created_at')->useCurrent();
    });
}

public function down(): void
{
    Schema::dropIfExists('admins');
}
};
