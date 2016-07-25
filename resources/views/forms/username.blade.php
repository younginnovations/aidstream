{{--*/
$messages = $errors->get($validationName);
/*--}}
<div class="form-group {{ $parent }}{{ $messages ? ' has-error' : '' }}">
    {{ Form::label($name, $label, ['class' => sprintf('control-label %s', ($required ? 'required' : ''))]) }}
    <div class="col-xs-12 col-md-12">
        <div class="input-group">
            <div class="input-group-addon"></div>
            {{ Form::text($name, $value, $attr) }}
            {{ Form::hidden($hiddenName, null, ['class' => 'form-control login_username']) }}
        </div>
        {!! $html !!}
        @foreach($messages as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
    </div>
</div>
