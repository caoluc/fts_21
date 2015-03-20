<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AnswerSheet extends Eloquent
{
    protected $table = 'answer_sheets';
    public $incrementing = true;
    protected $guarded = ['id'];
}
