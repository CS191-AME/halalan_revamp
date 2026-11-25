<x-app-layout title="Parties">

    <h1>Manage Parties</h1>

    {{-- Success message --}}
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    {{-- Error message --}}
    @if (session('error'))
        <p class="flash-error">Error: {{ session('error') }}</p>
    @endif

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

                        <form
                            method="POST"
                            action="{{ route('admin.parties.destroy', $p) }}"
                            onsubmit="return confirm('Delete this party?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach ($parties as $p)
        @include('admin.parties.edit', ['party' => $p, 'elections' => $elections])
    @endforeach

    @include('admin.parties.create')

</x-app-layout>
