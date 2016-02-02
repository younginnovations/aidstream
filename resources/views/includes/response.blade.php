{{--*/
        $response = session('response');
    /*--}}
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
