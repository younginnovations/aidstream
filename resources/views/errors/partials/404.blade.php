@if($message)
    <div class="panel panel-default error-page">
        <div class="error-msg-wrap">
            <div>
                <h2>
                    {{getVal($message,['heading'])}}
                </h2>
                <p>
                    {!! getVal($message,['message']) !!}
                </p>
                @if(auth()->check())
                    <a href="{{route($route)}}">Take me back to Dashboard</a>
                @else
                    <a href="/">Take me back to Homepage</a>
                @endif
            </div>
        </div>
    </div>
@endif