<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Clients List</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Client List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $index => $client)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $client->client_name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->mobile_number }}</td>
                    <td>{{ $client->company_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
