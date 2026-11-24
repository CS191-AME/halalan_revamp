{{-- Election creation popup --}}

{{-- Button to open the popup --}}
<button
    type="button"
    onclick="document.getElementById('createElectionModal').showModal()"
>
    +
</button>

<x-modal id="createElectionModal" title="Create Election">

    <form method="POST" action="{{ route('admin.elections.store') }}">

        @csrf

        {{-- Invisible input to indicate we are creating a NEW election --}}
        <input type="hidden" name="_creating_election" value="1">

        {{-- Election name --}}
        <div>
            <label for="name">Election Name</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="255"
            >

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        {{-- Parent election --}}
        <div>
            <label for="parent_id">Parent Election</label>

            <select id="parent_id" name="parent_id">

                <option value="">None (root election)</option>

                @foreach ($parents as $parent)
                    <option
                        value="{{ $parent->id }}"
                        @selected(old('parent_id') == $parent->id)
                    >
                        {{ $parent->name }}
                    </option>
                @endforeach

            </select>

            @error('parent_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        {{-- Enable ballots --}}
        <div>
            <label>
                <input
                    type="checkbox"
                    name="ballots_enabled"
                    value="1"
                    @checked(old('ballots_enabled'))
                >

                Enable ballots
            </label>

            @error('ballots_enabled')
                <p>{{ $message }}</p>
            @enderror
        </div>

        {{-- Form buttons --}}
        <button type="submit">Create Election</button>

    </form>

</x-modal>

{{-- Reopen creation popup only when failing in create election dialog --}}
@if ($errors->any() && old('_creating_election'))
    <script>
        document.getElementById('createElectionModal').showModal();
    </script>
@endif
