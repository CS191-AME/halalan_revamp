<!DOCTYPE html>
<head>
    <title>Halalan Revamp!</title>
</head>
<body>

<h1>Parties</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Alias</th>
                <th>Election</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($parties as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->alias ?? '—' }}</td>
                    <td>{{ $p->election->name }}</td>
                    <td>{{ $p->description ?? '—' }}</td>
                    <td>
                        <button type="button"
                            onclick="document.getElementById('editPartyModal-{{ $p->id }}').showModal()">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@foreach ($parties as $p)
    @include('admin.parties.edit', ['party' => $p, 'elections' => $elections])
@endforeach

@include('admin.parties.create')

</body>
