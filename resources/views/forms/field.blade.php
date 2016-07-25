{{--*/
$messages = $errors->get($validationName);
/*--}}
<div class="form-group {{ $parent }}{{ $messages ? ' has-error' : '' }}">
    @if($label)
        {{ Form::label($name, $label, ['class' => sprintf('control-label %s', ($required ? 'required' : ''))]) }}
    @endif
    <div class="col-xs-12 col-md-12">
        @include(sprintf('forms.%s', $field))
        {!! $html !!}
        <label class="text-danger" for="{{ $name }}">
            @foreach($messages as $message)
                {{ $message }} <br/>
            @endforeach
        </label>
    </div>
</div>
