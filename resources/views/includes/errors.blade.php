@if (count($errors) > 0)
    <div class="alert alert-danger">
        <span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
    </div>
@endif