@extends('default')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['url' => '/password/reset']) !!}
        {!! Form::hidden('token', $token) !!}
        <div class="form-group">
            {!! Form::label('email', 'E-Mail Address') !!}
            {!! Form::input('email','email', old('email'), ['class'=>"form-control",'required','placeholder'=>"email"]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', 'Password') !!}
            {!! Form::password('password', ['class'=>"form-control",'required','placeholder'=>"password"]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', 'Confirm Password') !!}
            {!! Form::password('password_confirmation', ['class'=>"form-control", 'placeholder'=>"Confirm Password"]) !!}
        </div>
        <div class="form-group">
            {!! Form::Submit('Reset Password', ['class'=>"btn btn-info form-control"]) !!}
        </div>
    {!! Form::close() !!}
@endsection
