{{--- Election creation popup ---}} 

{{-- Reopen popup when validation fails --}}
@if ($errors->any())
    <script>
        document.getElementById('createElectionModal').showModal();
    </script>
@endif

{{-- Button to open the popup --}}
<button
    type="button"
    onclick="document.getElementById('createElectionModal').showModal()"
>
    +
</button>

{{-- Creation popup --}}
<dialog id="createElectionModal">

    <div class="modal-content">

        <h2>Create Election</h2>

        {{-- Close popup --}}
        <button
            type="button"
            onclick="document.getElementById('createElectionModal').close()"
        >
            ✕
        </button>

        {{-- Creation form --}}
        <form method="POST" action="{{ route('admin.elections.store') }}">

            @csrf

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

    </div>
</dialog>