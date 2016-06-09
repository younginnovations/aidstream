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
            @if(array_key_exists('organization', $response))
                <a target="_blank" href="{{route('errors.organizationXml', ['organizationId' => request()->route('organization'), 'true' => true])}}" class="view-error">View error in organization
                    xml</a>
            @elseif(array_key_exists('activity', $response))
                <a target="_blank" href="{{route('errors.activityXml', ['activityId' => request()->route('activity'), 'true' => true])}}" class="view-error">View error in activity xml</a>
            @endif
        </div>
    @else
        <div class="alert alert-{{$response['type']}}">
            <span>{!! message($response) !!}</span>
        </div>
    @endif
@endif
