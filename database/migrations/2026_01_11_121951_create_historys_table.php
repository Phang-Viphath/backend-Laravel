<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('historys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->unsignedInteger('total_stays')->default(0);
            $table->enum('status', ['current', 'upcoming', 'past']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historys');
    }
};
