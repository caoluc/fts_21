<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Question extends Eloquent
{
    protected $table = 'questions';
    public $incrementing = true;
    protected $guarded = ['id'];
}
