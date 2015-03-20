@extends('app')

<?php use App\Services\ExaminationSrv;

?>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1 class="home-title">All examinations</h1>
            {!! Form::open(['action' => 'ExaminationController@store', 'role' => 'form', 'id' => 'add-exam']) !!}
                <div class="home-submit">
                    {!! Form::select('subjectDatas', $subjectDatas, null, ['multiple' => 'multiple', 'name' => 'subjectId', 'id' => 'subject-id']) !!}
                    {!! Form::submit('Start New', ['class' => 'btn btn-large btn-primary btn-start']) !!}
                </div>
            {!! Form::close() !!}
            {!! $examinations->setPath('/') !!}
            @foreach ($examinations as $examination)
                <hr>
                <div class="examination">
                    <span class="exam-time">{!! $examination->created_at !!}</span>
                    <span class="exam-testing">
                        @if (ExaminationSrv::checkTesting($examination->id)) Testing
                        @else Finish
                        @endif
                    </span>
                </div>
                <div class="examination">
                    <span class="exam-time">
                        {!! ExaminationSrv::findSubject($examination->id)->name !!}
                    </span>
                    <span class="exam-testing">
                        @if (ExaminationSrv::getExam($examination->id))
                            {!! $examination->time_left !!}
                        @endif
                    </span>
                    <span class="exam-testing">
                        @if (ExaminationSrv::checkTesting($examination->id))
                            {!! link_to_action('ExaminationController@index', 'Start', $examination->id) !!}
                        @else
                            {!! link_to_action('ExaminationController@result', 'View', $examination->id) !!}
                        @endif
                    </span>
                </div>
            @endforeach
            {!! $examinations->setPath('/') !!}
        </div>
    </div>
</div>
@endsection
