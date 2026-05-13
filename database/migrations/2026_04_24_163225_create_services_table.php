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
    Schema::create('services', function (Blueprint $table) {
        $table->id('service_id');
        $table->unsignedBigInteger('category_id');
        $table->string('service_name', 100);
        $table->decimal('price', 10, 2);
        $table->integer('duration');
        $table->text('description')->nullable();
        $table->string('image', 255)->nullable();
        $table->tinyInteger('status')->default(1);

        $table->foreign('category_id')
              ->references('category_id')
              ->on('categories')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });
   }

    public function down(): void
    {
    Schema::dropIfExists('services');
    }
};
