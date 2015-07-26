@extends('default')

@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

@if (count($errors) > 0)
{!! form_errors($errors) !!}
@endif

{!! Form::open(['url' => '/password/email']) !!}
<div class="form-group">
    {!! Form::label('email', 'E-Mail Address') !!}
    {!! Form::input('email','email', null, ['class'=>"form-control",'required', 'placeholder'=>"email"]) !!}
</div>
<div class="form-group">
    {!! Form::Submit('Reset Password', ['class'=>"btn btn-info form-control"]) !!}
</div>
{!! Form::close() !!}

<div class="form-group">
    <a href="{{ url('/auth/login') }}" class="pull-right">Go to login</a>
    <div class="clearfix" />
</div>
@endsection
