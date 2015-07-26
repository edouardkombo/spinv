<div class="row">
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('client_no', 'Client No. *') !!}
        {!! Form::text('client_no', isset($client_num) ? $client_num : null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('name', 'Name *') !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email', 'Email *') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('mobile', 'Mobile') !!}
        {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('address1', 'Address 1') !!}
        {!! Form::text('address1', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('address2', 'Address 2') !!}
        {!! Form::text('address2', null, ['class' => 'form-control']) !!}
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('city', 'City') !!}
            {!! Form::text('city', null, ['class' => 'form-control']) !!}
        </div>
    <div class="form-group">
        {!! Form::label('state', 'State/Province') !!}
        {!! Form::text('state', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('postal_code', 'Zip/Postal Code') !!}
        {!! Form::text('postal_code', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('country', 'Country') !!}
        {!! Form::text('country', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('website', 'Website') !!}
        {!! Form::text('website', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('notes', 'Notes') !!}
        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '5']) !!}
    </div>
</div>
</div>