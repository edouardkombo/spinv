@extends('default')

@section('content')

@if (count($errors) > 0)
{!! form_errors($errors) !!}
@endif

{!! Form::open(['url' => '/auth/login']) !!}
<div class="form-group">
    {!! Form::label('email', 'Email') !!}
    {!! Form::input('email','email', null, ['class'  =>"form-control", 'required'=>'required', 'placeholder'=>"email"]) !!}
</div>
<div class="form-group">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['class'=>"form-control", 'placeholder'=>"password", 'required']) !!}
</div>
<div class="form-group">
    {!! Form::Submit('Login', ['class'=>"btn btn-info form-control"]) !!}
</div>
{!! Form::close() !!}

<div class="form-group">
    <a href="{{ url('password/email') }}" class="pull-right">Forgot password?</a>
    <div class="clearfix" />
</div>
@endsection
