<?php namespace app\Services;

use App\Models\Examination;
use App\Models\Subject;
use App\Models\AnswerSheet;

class AnswerSheetSrv
{
    public static function getByExam($examinationId)
    {
        $answerSheets = AnswerSheet::where('examination_id', $examinationId)->get();

        return $answerSheets;
    }

    public static function getBlankModel()
    {
        return new AnswerSheet();
    }
}
