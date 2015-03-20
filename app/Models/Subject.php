<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Models\Subject;

class Subject extends Eloquent
{
    protected $table = 'subjects';
    public $incrementing = true;
    protected $guarded = ['id'];

    public function examinations()
    {
        return $this->hasMany('Examination', 'subject_id', 'id');
    }
}
