<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
<p>index</p>
<form action="/" method="POST">
    <select type="text" name="lang">
        <option>php</option>
        <option>javascript</option>
        <option>python</option>
    </select>
    <textarea name="code" id="code" cols="30" rows="10"></textarea>
    <button type="submit">Submit</button>
</form>


</body>
</html>
