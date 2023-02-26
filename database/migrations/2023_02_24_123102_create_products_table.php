<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('discount');
            $table->integer('price');
            $table->unsignedBigInteger('merk_id');
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['EMPTY', 'AVAILABLE']);
            $table->string('img_name');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            // foreign key section
            $table->foreign('merk_id')->references('id')->on('merks');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
