@extends('app')

<?php use App\Services\ExaminationSrv;

?>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div>
                @if (Session::has('flash_message'))
                    <div class="alert {{ Session::get('flash_type') }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>{{ Session::get('flash_message') }}</h4>
                    </div>
                @endif
                <div>
                    {!! $examination->created_at !!}
                </div>
                <div class="timer">
                    Time Left: 00:00:00
                </div>
            </div>
            <div>
                <h3 class="home-title">{!! $subject->name !!}</h3>
                <h4 class="home-title">{!! $examination->correct_num !!} / {!! $subject->question_num !!}</h4>
            </div>
            <div>
                @foreach ($answerSheets as $key => $answerSheet)
                    <div class="question">
                    {!! $key + 1 . " . " . ExaminationSrv::showQuestion($answerSheet->question_id) !!}
                    </div>
                    @foreach (ExaminationSrv::getAnswer($answerSheet->question_id) as $answer)
                        <div>
                            {!! Form::radio('answer_' . $answerSheet->question_id, $answer->id, ExaminationSrv::checkAnswered($answerSheet->id, $answer->id)) !!}
                            {!! $answer->content !!}
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
