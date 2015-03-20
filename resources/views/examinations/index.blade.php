@extends('app')

<?php use App\Services\ExaminationSrv;

?>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div>
                <div>
                    {!! $examination->created_at !!}
                </div>
                <div class="timer">
                    Time Left:
                    <span id="countdown"></span>
                    {!! Form::hidden('time limit', ExaminationSrv::convertTimeToSecond(ExaminationSrv::getTimeCountDown($examination->id)), ['id' => 'time-limit']) !!}
                </div>
            </div>
            <div>
                <h3 class="home-title">{!! $subject->name !!}</h3>
            </div>
            {!! Form::open(['action' => ['ExaminationController@update', $examination->id], 'role' => 'form', 'id' => 'submit-exam']) !!}
                <div>
                    @foreach ($answerSheets as $key => $answerSheet)
                        <div class="question">
                        {!! $key + 1 . " . " . $answerSheet->question()->first()->content !!}
                        </div>
                        @foreach (ExaminationSrv::getAnswer($answerSheet->question_id) as $answer)
                            <div>
                                {!! Form::radio('answer_' . $answerSheet->question_id, $answer->id, ExaminationSrv::checkAnswered($answerSheet->id, $answer->id)) !!}
                                {!! $answer->content !!}
                            </div>
                        @endforeach
                    @endforeach
                </div>
                {!! Form::submit('Submit', ['class' => 'btn btn-large btn-primary btn-submit', 'id' => 'finish-exam']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script src="/js/jquery-2.1.1.min.js"></script>
<script src="/js/custom.js"></script>
@endsection
