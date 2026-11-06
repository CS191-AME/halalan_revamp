<!DOCTYPE html>
<head>
    <title>Admin Elections</title>
</head>
<body>
    <h1>Elections</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent Election</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($elections as $e)
                <tr>
                    <td>{{ $e->name }}</td>
                    <td>{{ $e->parent?->name ?? '—' }}</td>
                    <td>{{ $e->status() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
