<x-app-layout title="Positions">

    <h1>Manage Positions</h1>

    {{-- Success message --}}
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    {{-- Election selection --}}
    <form method="GET" action="{{ route('admin.positions.index') }}">

        <label for="election_id">View:</label>

        <select
            name="election_id"
            id="election_id"
            onchange="this.form.submit()"
        >
            <option value="">Choose Election</option>

            @foreach ($elections as $election)
                <option
                    value="{{ $election->id }}"
                    @selected($selectedElection?->id == $election->id)
                >
                    {{ $election->name }}
                </option>
            @endforeach

        </select>

        <noscript>
            <button type="submit">View</button>
        </noscript>

    </form>

    {{-- No elections available --}}
    @if ($elections->isEmpty())
        <p>No elections have been created yet.</p>
    @endif

    {{-- Positions table --}}
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Position</th>
                <th>Description</th>
                <th>Max Selections</th>
                <th>Abstain Allowed</th>
                <th>Ordinality</th>
            </tr>
        </thead>

        <tbody>
            @if (!$selectedElection)
                <tr>
                    <td colspan="6">Please choose an election.</td>
                </tr>

            @elseif ($positions->isEmpty())
                <tr>
                    <td colspan="6">No positions found for this election.</td>
                </tr>

            @else
                @foreach ($positions as $position)
                    <tr>
                        <td>{{ $position->id }}</td>
                        <td>{{ $position->name }}</td>
                        <td>{{ $position->description ?? '—' }}</td>
                        <td>{{ $position->max_selections }}</td>
                        <td>{{ $position->can_abstain ? 'Yes' : 'No' }}</td>
                        <td>{{ $position->ordinality }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Add position form --}}
    @if ($selectedElection && $selectedElection->isDraft())
        @include('admin.positions.create')
    @endif

</x-app-layout>
