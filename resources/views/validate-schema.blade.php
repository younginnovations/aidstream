<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<ul>
    @forelse($messages as $message)
        <li>{!! $message !!}</li>
    @empty
        <li>Validated!!</li>
    @endforelse
</ul>
<br/>

<div style="word-break: break-all;">
@foreach($xmlLines as $key => $line)
    {{--*/ $number = $key + 1; /*--}}
    <div id="{{ $number }}"><strong style="{{ array_key_exists($number, $messages) ? 'color:red': ''  }}">{{ $number }}</strong>{{ $line }}</div>
@endforeach
</div>
</body>
</html>