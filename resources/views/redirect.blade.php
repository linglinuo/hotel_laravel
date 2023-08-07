<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <input id="url" type="text" value="{{ env('APP_URL') }}" hidden>
    <input id="query" type="text" value="{{ $query }}" hidden />
    <script>
        const url = document.getElementById('url').value;
        const query = document.getElementById('query').value;
        window.location.href = `${url}?${query}`;
    </script>
</body>

</html>