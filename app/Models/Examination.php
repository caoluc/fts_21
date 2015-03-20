<?php namespace app\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Examination extends Eloquent
{
    protected $table = 'examinations';
    public $incrementing = true;
    protected $guarded = ['id'];

    public function subject()
    {
        return $this->belongsTo('Examination', 'subject_id', 'id');
    }
}
