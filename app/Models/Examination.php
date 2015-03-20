<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use app\Models\Subject;

class Examination extends Eloquent
{
    protected $table = 'examinations';
    public $incrementing = true;
    protected $guarded = ['id'];

    public function subject()
    {
        $subject = new Subject;

        return $this->belongsTo($subject, 'subject_id', 'id');
    }
}
