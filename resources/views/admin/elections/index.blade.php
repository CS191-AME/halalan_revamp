<!DOCTYPE html>
<head>
    <title>Admin Elections</title>
</head>
<body>

<h1>Elections</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Parent Election</th>
                <th>Status</th>
                <th>Ballots</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($elections as $e)
                <tr>
                    <td>{{ $e->id }}</td>
                    <td>{{ $e->name }}</td>
                    <td>{{ $e->parent?->name ?? '—' }}</td>
                    <td>{{ $e->status() }}</td>
                    <td>{{ $e->ballots_enabled ? 'Enabled' : 'Disabled' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@include('admin.elections.create')

</body>
