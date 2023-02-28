<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('school_year')->nullable();
            $table->integer('type')->nullable();
            $table->integer('strand')->nullable();
            $table->integer('adviser')->nullable();
            $table->integer('grade')->nullable();
            $table->integer('term')->nullable();
            $table->integer('section')->nullable();
            $table->integer('subject')->nullable();
            $table->string('day')->nullable();
            $table->time('t_from')->nullable();
            $table->time('t_to')->nullable();
            $table->integer('teacher')->nullable();
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
        Schema::dropIfExists('schedule');
    }
}
