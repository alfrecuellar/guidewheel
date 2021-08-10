<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('metric_id');
            $table->unsignedBigInteger('device_id');
            $table->datetime('timestamp');
            $table->string('recvalue')->nullable();
            $table->string('calcvalue')->nullable();
            $table->string('excthreshold')->nullable();
            $table->string('excthlimit')->nullable();
            $table->string('deviation')->nullable();
            $table->timestamps();

            $table->foreign('metric_id')->references('id')->on('metrics');
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
