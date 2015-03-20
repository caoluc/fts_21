<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Answer extends Eloquent
{
    protected $table = 'answers';
    public $incrementing = true;
    protected $guarded = ['id'];
}
