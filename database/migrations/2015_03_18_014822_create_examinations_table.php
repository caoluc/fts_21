<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('subject_id');
            $table->time('time_left');
            $table->integer('correct_num')->default(0);
            $table->text('question_srlz');
            $table->tinyInteger('status')->default(0);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('examinations');
    }
}
