<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('bkg_id');
            $table->string('name')->nullable();
            $table->string('room_type')->nullable();
            $table->string('total_numbers')->nullable();
            $table->string('date')->nullable();
            $table->string('time_start')->nullable();
            $table->string('time_end')->nullable();
            $table->string('email')->nullable();
            $table->string('ph_number')->nullable();
            $table->string('message')->nullable();
            $table->string('approval')->nullable();
            $table->string('status_meet')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
