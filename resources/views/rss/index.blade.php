<!DOCTYPE html>
<html>
<head>
    <title>RSS Analyzer</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        mark { background-color: yellow; }
    </style>
</head>
<body>
    <h1>Total "Google" Occurrences: {{ $count }}</h1>

    <h2>Occurrences in Context:</h2>
    @foreach ($occurrences as $entry)
        <div style="margin-bottom: 20px;">
            <h3>{!! $entry['title'] !!}</h3>
            <p>{!! $entry['description'] !!}</p>
        </div>
    @endforeach
</body>
</html>

