@if(Session::has('value'))
    @foreach (Session::get('value') as $key=>$value)
        @if($key == "unpublished")
            <div class="alert alert-warning">
        @else
            <div class="alert alert-success">
        @endif
        <ul>
            <li>{{ $value }}</li>
        </ul>
        </div>
    @endforeach
@endif
