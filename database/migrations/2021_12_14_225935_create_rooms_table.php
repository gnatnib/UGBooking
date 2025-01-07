<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('bkg_room_id');
            $table->string('room_type');
            $table->integer('capacity');
            $table->string('fileupload')->nullable();
            $table->string('status')->nullable();
            // Adding room facilities as boolean fields
            $table->boolean('has_projector')->default(false);
            $table->boolean('has_sound_system')->default(false);
            $table->boolean('has_tv')->default(false);
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
        Schema::dropIfExists('rooms');
    }
}
