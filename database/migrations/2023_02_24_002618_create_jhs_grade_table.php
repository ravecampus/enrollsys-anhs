<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJhsGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jhs_grade', function (Blueprint $table) {
            $table->id();
            $table->integer('school_year_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('grade')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->double('first_grade')->default(0);
            $table->double('second_grade')->default(0);
            $table->double('third_grade')->default(0);
            $table->double('fourth_grade')->default(0);
            $table->double('average')->nullable();
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
        Schema::dropIfExists('jhs_grade');
    }
}
