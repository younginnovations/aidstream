{{--*/
$messages = $errors->get($validationName);
/*--}}
<div class="form-group {{ $parent }}{{ $messages ? ' has-error' : '' }}">
    @if($label)
        <label for="{{ $name }}" class="{{ sprintf('control-label %s', ($required ? 'required' : '')) }}">
            {{ $label }}
            @if(isset($help))
                <span class="help-text" title="{{ help('activity_defaults-default_language') }}" data-toggle="tooltip" data-placement="top"></span>
            @endif
        </label>
    @endif
    <div class="col-xs-12 col-md-12">
        <div>
            @include(sprintf('forms.%s', $field))
            <label class="text-danger" for="{{ $name }}">
                @foreach($messages as $message)
                    {{ $message }} <br/>
                @endforeach
            </label>
        </div>
        {!! $html !!}
    </div>
</div>
