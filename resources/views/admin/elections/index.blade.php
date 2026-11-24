<x-app-layout title="Elections">


    <h1>Elections</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Ballots</th>
                <th>Actions</th>
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
                    <td>
                        <button
                            type="button"
                            onclick="document.getElementById('editElectionModal-{{ $e->id }}').showModal()"
                        > Edit </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@include('admin.elections.create')

{{-- Edit popups --}}
@foreach ($elections as $e)
    @include('admin.elections.edit', [
        'election' => $e,
        'parents' => $parents
    ])
@endforeach

</x-app-layout>

