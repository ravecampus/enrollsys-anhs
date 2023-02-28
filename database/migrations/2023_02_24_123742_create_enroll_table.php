<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enroll', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('student_type')->nullable();
            $table->integer('school_year_id')->nullable();
            $table->integer('grade')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('term')->nullable();
            $table->integer('strand')->nullable();
            $table->integer('status')->nullable();
            $table->integer('deleted')->default(0);
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
        Schema::dropIfExists('enroll');
    }
}
