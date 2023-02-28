<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShsGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shs_grade', function (Blueprint $table) {
            $table->id();
            $table->integer('school_year_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('grade')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->double('first_quarter')->default(0);
            $table->double('second_quarter')->default(0);
            $table->double('average')->nullable();
            $table->integer('term')->nullable();
            $table->integer('remark')->nullable();
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
        Schema::dropIfExists('shs_grade');
    }
}
