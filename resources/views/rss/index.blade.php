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
    <form method="GET" action="{{ route('rss.index') }}">
    <label for="keyword">Keyword:</label>
    <input type="text" id="keyword" name="keyword" value="{{request('keyword','Google')}}">
    
    <label for="date_from">From:</label>
    <input type="date" id="date_from" name="dateFrom" value="{{request('dateFrom')}}">
    <label for="date_to">To:</label>
    <input type="date" id="date_to" name="dateTo" value="{{request('dateTo')}}">

    <button type="submit">Refresh</button>
</form>
    <h1>Total "{{$keyword}}" Occurrences: {{ $count }}</h1>

    <h2>Occurrences in Context:</h2>
    @foreach ($occurrences as $entry)
        <div style="margin-bottom: 20px;">
            <h3>{{ $entry['pubDate'] }} - {!! $entry['title'] !!}</h3>
            <p>{!! $entry['description'] !!}</p>
        </div>
    @endforeach
</body>
</html>

