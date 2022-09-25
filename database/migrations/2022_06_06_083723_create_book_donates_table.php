<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookDonatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_donates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('photo');
            $table->boolean('acceptance')->default(0);
            $table->boolean('state')->default(0);
            $table->foreignId('donate_id')->constrained('donates')->cascadeOnDelete();
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
        Schema::dropIfExists('book_donates');
    }
}
