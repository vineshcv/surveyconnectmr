<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Questions List</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Questions</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $index => $question)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $question->question }}</td>
                    <td>{{ ucfirst($question->type) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
