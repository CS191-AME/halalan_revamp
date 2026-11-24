{{-- Party creation popup --}}

{{-- Button to open the popup --}}
<button
    type="button"
    onclick="document.getElementById('createPartyModal').showModal()"
>
    +
</button>

<dialog id="createPartyModal">

    <div class="modal-content">

        <h2>Create Party</h2>

        <button
            type="button"
            onclick="document.getElementById('createPartyModal').close()"
        >
            ✕
        </button>

        <form method="POST" action="{{ route('admin.parties.store') }}">

            @csrf

            {{-- Invisible input to indicate currently creating NEW party  --}}
            <input type="hidden" name="_creating_party" value="1">

            <div>
                <label for="party_name">Party Name</label>
                <input
                    type="text"
                    id="party_name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                >
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="party_alias">Alias</label>
                <input
                    type="text"
                    id="party_alias"
                    name="alias"
                    value="{{ old('alias') }}"
                >
                @error('alias')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="party_election_id">Election</label>
                <select id="party_election_id" name="election_id" required>
                    <option value="">Select an election</option>
                    @foreach ($elections as $election)
                        <option
                            value="{{ $election->id }}"
                            @selected(old('election_id') == $election->id)
                        >
                            {{ $election->name }}
                        </option>
                    @endforeach
                </select>
                @error('election_id')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="party_description">Description</label>
                <input
                    type="text"
                    id="party_description"
                    name="description"
                    value="{{ old('description') }}"
                    maxlength="255"
                >
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Create Party</button>

        </form>

    </div>
</dialog>


{{-- Condition prevents opening create dialog box on failed edits --}}
@if ($errors->any() && old('_creating_party'))
    <script>
        document.getElementById('createPartyModal').showModal();
    </script>
@endif
