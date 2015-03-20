<?php namespace app\Services;

use App\Models\Subject;

class SubjectSrv
{
    public static function getAll()
    {
        return Subject::all();
    }

    public static function getData()
    {
        $subjects = SubjectSrv::getAll();
        $names = [];
        foreach ($subjects as $subject) {
            $names[$subject->id] = $subject->name;
        }

        return $names;
    }

    public static function get($id)
    {
        $subject = Subject::find($id);

        return $subject;
    }
}
