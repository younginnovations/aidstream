{{--*/
        $response = session('response');
    /*--}}
@if($errors->count())
    <div class="alert alert-danger">
        <span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
    </div>
@else
    @if($response)
        @if(isset($response['messages']) && (array) $response['messages'])
            <div class="alert alert-{{$response['type']}}">
                <ul>
                    @foreach($response['messages'] as $message)
                        <li>- {!! $message !!}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-{{$response['type']}}">
                <span>{!! message($response) !!}</span>
            </div>
        @endif
    @endif
@endif
