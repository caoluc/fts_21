<?php namespace app\Services;

use App\Models\Examination;
use App\Models\Question;
use App\Models\Subject;
use App\Models\AnswerSheet;
use App\Models\Answer;
use App\Services\SubjectSrv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ExaminationSrv
{
    public static function getExam($id)
    {
        $examination = Examination::find($id);
        $examination->load('subject');

        return $examination;
    }

    public static function checkTesting($id)
    {
        $examination = ExaminationSrv::getExam($id);
        if ($examination->status == Config::get('examination.exam_not_yet_finish')) {
            return true;
        }

        return false;
    }

    public static function findSubject($id)
    {
        $examination = ExaminationSrv::getExam($id);
        $subjectId = $examination->subject_id;
        $subject = Subject::find($subjectId);

        return $subject;
    }

    public static function initAnswerSheet($examinationId, $userId, $questionId)
    {
        $answerSheet = new AnswerSheet();
        $answerSheet->user_id = $userId;
        $answerSheet->question_id = $questionId;
        $answerSheet->examination_id = $examinationId;
        $answerSheet->save();
    }

    public static function buildQuestion($examinationId, $userId)
    {
        $examination = Examination::find($examinationId);
        $subject = $examination->subject()->first();
        $currentTime = time();
        $startTime = date('Y-m-d H:i:s', $currentTime);
        $examination->start_time = $startTime;
        $examination->end_time = date('Y-m-d H:i:s', $currentTime + ExaminationSrv::convertTimeToSecond($subject->time_limit));
        $examination->save();
        $userAnswer = AnswerSheet::where('examination_id', $examinationId)->first();
        $questionList = $examination->question_srlz;
        if (!$userAnswer) {
            $subject = Subject::find($examination->subject_id);
            $questionList = unserialize($examination->question_srlz);
            for ($i = 0; $i < count($questionList); $i++) {
                ExaminationSrv::initAnswerSheet($examination->id, $userId, $questionList[$i]);
            }
        }
    }

    public static function isPeriod($examinationId)
    {
        $examination = Examination::find($examinationId);
        if (($examination->start_time < time()) && (time() < $examination->end_time)) {
            return true;
        }

        return false;
    }

    public static function showQuestion($questionId)
    {
        $question = Question::find($questionId);

        return $question->content;
    }

    public static function getAnswer($questionId)
    {
        $answers = Answer::where('question_id', $questionId)->get();

        return $answers;
    }

    public static function convertTimeToSecond($time)
    {
        $strTime = $time;
        $strTime = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $strTime);
        sscanf($strTime, "%d:%d:%d", $hours, $minutes, $seconds);
        $timeSeconds = $hours * 3600 + $minutes * 60 + $seconds;

        return $timeSeconds;
    }

    public static function all()
    {
        return Examination::orderBy('id', 'desc')->paginate(Config::get('examination.paginate'));
    }

    public static function getBlankModel()
    {
        return new Examination();
    }

    public static function generateExam($subjectId)
    {
        $userId = Auth::user()->id;
        $subject = SubjectSrv::get($subjectId);
        $questions = Question::where('subject_id', $subjectId)
            ->take($subject->question_num)
            ->orderByRaw("RAND()")
            ->get();
        if (empty($questions)) {
            return;
        }
        $sentence = [];
        foreach ($questions as $question) {
            array_push($sentence, $question->id);
        }
        $examination = new Examination();
        $examination->user_id = $userId;
        $examination->subject_id = $subjectId;
        $examination->question_srlz = serialize($sentence);
        $examination->save();
    }

    public static function save($examination)
    {
        $examination->save();
    }

    public static function checkAnswered($answerSheetId, $answerId)
    {
        $answerSheet = AnswerSheet::find($answerSheetId);
        if ($answerSheet->user_answer == $answerId) {
            return true;
        }

        return false;
    }

    public static function getAnswerSheet($examinationId)
    {
        $answerSheets = AnswerSheet::where('examination_id', $examinationId)->get();

        return $answerSheets;
    }

    public static function getTimeCountDown($examinationId)
    {
        $examination = ExaminationSrv::getExam($examinationId);
        $subject = $examination->subject()->first();
        $answerSheetFirst = AnswerSheet::where('examination_id', $examinationId)->first();
        $timeCountDown =  date('H:i:s', strtotime($subject->time_limit) - (time() - strtotime($answerSheetFirst->created_at)));

        return $timeCountDown;
    }

    public static function getCorrectAnswer($questionId)
    {
        $answer = Answer::where('question_id', $questionId)->where('is_correct', Config::get('examination.answer_is_correct'))->first();

        return $answer->id;
    }

    public static function finishExam($examinationId)
    {
        $examination = ExaminationSrv::getExam($examinationId);
        $subject = $examination->subject()->first();
        $examination->status = Config::get('examination.exam_status_finish');
        $examination->save();
    }

    public static function checkFinish($examinationId)
    {
        $examination = ExaminationSrv::getExam($examinationId);
        $subject = $examination->subject()->first();
        $startTime = strtotime($examination->start_time) - strtotime('00:00:00');
        $timeLimit = strtotime($subject->time_limit) - strtotime('00:00:00');
        $timeCompare = time() - strtotime('00:00:00') - $startTime;
        if ($timeCompare >= $timeLimit) {
            return true;
        }

        return false;
    }

    public static function updateUserAnswer($examinationId, $input)
    {
        $answerSheets = ExaminationSrv::getAnswerSheet($examinationId);
        $answerCorrect = 0;
        foreach ($answerSheets as $answerSheet) {
            $userAnswer = array_get($input, 'answer_'.$answerSheet->question_id);
            if ($userAnswer) {
                $answerSheet->user_answer = $userAnswer;
                $answerSheet->save();
            }
            if ($answerSheet->user_answer == ExaminationSrv::getCorrectAnswer($answerSheet->question_id)) {
                $answerCorrect += 1;
            }
        }
        $examination = ExaminationSrv::getExam($examinationId);
        $examination->time_left = date('H:i:s', time() - strtotime($examination->created_at));
        $examination->correct_num = $answerCorrect;
        $examination->save();
    }
}
