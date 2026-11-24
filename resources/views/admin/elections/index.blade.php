<x-app-layout title="Elections">

    <h1>Elections</h1>

    {{-- Success message --}}
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    {{-- Warning message --}}
    @if (session('warning'))
        <p>Warning: {{ session('warning') }}</p>
    @endif

    {{-- Error message --}}
    @if (session('error'))
        <p>Error: {{ session('error') }}</p>
    @endif

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

                        @if ($e->is_running)
                            <form
                                method="POST"
                                action="{{ route('admin.elections.stop', $e) }}"
                                onsubmit="return confirm('Stop this election and all of its child elections?')"
                                style="display: inline"
                            >
                                @csrf
                                <button type="submit">Stop</button>
                            </form>
                        @else
                            <form
                                method="POST"
                                action="{{ route('admin.elections.start', $e) }}"
                                style="display: inline"
                            >
                                @csrf
                                <button type="submit">
                                    {{ $e->isDraft() ? 'Start' : 'Restart' }}
                                </button>
                            </form>
                        @endif

                        {{-- Results: only for elections that were started and then stopped --}}
                        @if (!$e->isDraft() && !$e->is_running)
                            @if ($e->results_available)
                                <form
                                    method="POST"
                                    action="{{ route('admin.elections.results.hide', $e) }}"
                                    style="display: inline"
                                >
                                    @csrf
                                    <button type="submit">Hide Results</button>
                                </form>
                            @else
                                <form
                                    method="POST"
                                    action="{{ route('admin.elections.results.show', $e) }}"
                                    style="display: inline"
                                >
                                    @csrf
                                    <button type="submit">Show Results</button>
                                </form>
                            @endif
                        @endif
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
