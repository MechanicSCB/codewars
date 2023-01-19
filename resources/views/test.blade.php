<!DOCTYPE html>
<head>
    @vite('resources/js/app.js')
</head>
<html>

<body>
<h1>Tests</h1>
@foreach($testCases as $kataId => $testCaseHtml)
    {{ $kataId }}
    <pre class="mx-8 my-2 p-2 bg-gray-200 rounded-lg text-xs overflow-x-auto">
        {{ $testCaseHtml }}
    </pre>
@endforeach
</body>
</html>
