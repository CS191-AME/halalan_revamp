<x-app-layout title="Elections">

    <h1>Manage Elections</h1>

    {{-- Success message --}}
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    {{-- Warning message --}}
    @if (session('warning'))
        <p class="flash-warning">Warning: {{ session('warning') }}</p>
    @endif

    {{-- Error message --}}
    @if (session('error'))
        <p class="flash-error">Error: {{ session('error') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Election Name</th>
                <th>Status</th>
                <th>Results</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($elections as $e)
                {{-- Results can only be toggled after the election was started and then stopped --}}
                @php
                    $canToggleResults = !$e->isDraft() && !$e->is_running;
                @endphp

                <tr>
                    <td>{{ $e->id }}</td>

                    {{-- Name, indented by depth (0 = root, 1 = child, 2 = grandchild...) --}}
                    <td>
                        <div
                            class="tree-name {{ $e->depth > 0 ? 'tree-name-child' : '' }}"
                            style="margin-left: {{ $e->depth * 1.5 }}rem"
                        >
                            {{ $e->name }}
                        </div>
                    </td>

                    {{-- Status pill: click to stop (if running) or start/restart (if not) --}}
                    <td>
                        @if ($e->is_running)
                            <form
                                method="POST"
                                action="{{ route('admin.elections.stop', $e) }}"
                                onsubmit="return confirm('Stop this election and all of its child elections?')"
                            >
                                @csrf
                                <button type="submit" class="status-pill is-running" title="Click to stop">
                                    <span class="status-dot"></span>
                                    Running
                                </button>
                            </form>
                        @else
                            <form
                                method="POST"
                                action="{{ route('admin.elections.start', $e) }}"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="status-pill"
                                    title="{{ $e->isDraft() ? 'Click to start' : 'Click to restart' }}"
                                >
                                    <span class="status-dot"></span>
                                    Not Running
                                </button>
                            </form>
                        @endif
                    </td>

                    {{-- Results toggle: Show or Hide, disabled unless started and stopped --}}
                    <td>
                        <form
                            method="POST"
                            action="{{ $e->results_available
                                ? route('admin.elections.results.hide', $e)
                                : route('admin.elections.results.show', $e) }}"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="results-button"
                                @disabled(!$canToggleResults)
                            >
                                {{ $e->results_available ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <button
                            type="button"
                            onclick="document.getElementById('editElectionModal-{{ $e->id }}').showModal()"
                        > Edit </button>

                        {{-- Delete: draft elections only --}}
                        @if ($e->isDraft())
                            <form
                                method="POST"
                                action="{{ route('admin.elections.destroy', $e) }}"
                                onsubmit="return confirm('Delete this election?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
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
