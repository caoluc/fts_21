<?php namespace app\Http\Controllers;

use App\Services\SubjectSrv;
use App\Services\ExaminationSrv;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $subjectDatas = SubjectSrv::getData();
        $examinations = ExaminationSrv::all();

        return View::make('home', [
            'subjectDatas' => $subjectDatas,
            'examinations' => $examinations,
        ]);
    }
}
