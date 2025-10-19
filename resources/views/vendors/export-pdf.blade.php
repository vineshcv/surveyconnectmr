<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendors PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Vendor List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Vendor Name</th>
                <th>Vendor ID</th>
                <th>Email</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendors as $index => $vendor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vendor->vendor_name }}</td>
                    <td>{{ $vendor->vendor_id }}</td>
                    <td>{{ $vendor->email }}</td>
                    <td>{{ $vendor->contact_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
