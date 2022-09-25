<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('page_number');
            $table->string('publishing_house');
            $table->date('publishing_year');
            $table->integer('copies_number');
            $table->double('price');
            $table->text('cover');
            $table->string('classification')->nullable();
            $table->boolean('state');
            $table->text('PDF')->nullable();
            $table->text('audio_book')->nullable();
            $table->boolean('amateur')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
