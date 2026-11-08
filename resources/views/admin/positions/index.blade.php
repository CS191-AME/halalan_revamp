<!DOCTYPE html>
<html lang="en">
<head>
    <title>Halalan Revamp!</title>
</head>
<body>

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
            @forelse ($positions as $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td>{{ $position->name }}</td>
                    <td>{{ $position->description ?? '—' }}</td>
                    <td>{{ $position->max_selections }}</td>
                    <td>{{ $position->can_abstain ? 'Yes' : 'No' }}</td>
                    <td>{{ $position->ordinality }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No positions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Add position button --}}
    @if ($selectedElection?->isDraft() || !$selectedElection?->exists)
        <button type="button">
            + Add Position
        </button>
    @endif

</body>