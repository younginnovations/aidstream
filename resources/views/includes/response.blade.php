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
            @if(array_key_exists('schema', $response))
                <a target="_blank" href="{{route('errors.organizationXml', ['organizationId' => request()->route('organization'), 'true' => true])}}" class="view-error">View error in organization xml</a>
            @endif
        </div>
    @else
        <div class="alert alert-{{$response['type']}}">
            <span>{!! message($response) !!}</span>
        </div>
    @endif
@endif
