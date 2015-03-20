<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerSheetTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('answer_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('question_id');
            $table->integer('examination_id');
            $table->integer('user_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('answer_sheets');
    }
}
