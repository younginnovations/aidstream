<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<ul>
    @forelse($messages as $message)
        <li>{{ $message }}</li>
    @empty
        <li>Validated!!</li>
    @endforelse
</ul>
<br/>

<div>
    {{ $tempXmlContent }}
</div>
</body>
</html>