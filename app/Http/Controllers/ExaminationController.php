<?php namespace app\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Examination;
use App\Services\ExaminationSrv;
use App\Services\AnswerSheetSrv;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $userId = Auth::user()->id;
        ExaminationSrv::buildQuestion($id, $userId);
        $answerSheets = AnswerSheetSrv::getByExam($id);
        $answerSheets->load('question');
        $examination = ExaminationSrv::getExam($id);
        $subject = $examination->subject()->first();
        if (ExaminationSrv::isPeriod($id)) {
            ExaminationSrv::finishExam($id);
            return Redirect::action('ExaminationController@result', [$id]);
        }

        return View::make('examinations.index', [
            'answerSheets'   => $answerSheets,
            'examination'    => $examination,
            'subject'        => $subject,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $subjectId = Input::get('subjectId');
        ExaminationSrv::generateExam($subjectId);

        return Redirect::action('HomeController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        if (!ExaminationSrv::checkFinish($id)) {
            ExaminationSrv::updateUserAnswer($id, Input::all());
            return Redirect::action('ExaminationController@index', [$id]);
        }

        return Redirect::action('ExaminationController@result', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function result($id)
    {
        Session::flash('flash_message', Config::get('examination.finish'));
        Session::flash('flash_type', 'alert-success');
        $answerSheets = AnswerSheetSrv::getByExam($id);
        $examination = ExaminationSrv::getExam($id);
        $subject = $examination->subject()->first();

        return View::make('examinations.result', [
            'answerSheets'   => $answerSheets,
            'examination'    => $examination,
            'subject'        => $subject,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
