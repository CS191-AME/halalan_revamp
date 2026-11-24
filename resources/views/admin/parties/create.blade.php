{{-- True only when the failed submission came from this create form --}}
@php
    $isFailedCreate = (bool) old('_creating_party');
@endphp

{{-- Party creation popup --}}

{{-- Button to open the popup --}}
<button
    type="button"
    onclick="document.getElementById('createPartyModal').showModal()"
>
    +
</button>

<x-modal id="createPartyModal" title="Create Party">

    <form method="POST" action="{{ route('admin.parties.store') }}">

        @csrf

        {{-- Invisible input to indicate we are creating a NEW party --}}
        <input type="hidden" name="_creating_party" value="1">

        <div>
            <label for="party_name">Party Name</label>
            <input
                type="text"
                id="party_name"
                name="name"
                value="{{ $isFailedCreate ? old('name') : '' }}"
                required
                maxlength="255"
            >
            @if ($isFailedCreate)
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label for="party_alias">Alias</label>
            <input
                type="text"
                id="party_alias"
                name="alias"
                value="{{ $isFailedCreate ? old('alias') : '' }}"
            >
            @if ($isFailedCreate)
                @error('alias')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label for="party_election_id">Election</label>
            <select id="party_election_id" name="election_id" required>
                <option value="">Select an election</option>
                @foreach ($elections as $election)
                    <option
                        value="{{ $election->id }}"
                        @selected($isFailedCreate && old('election_id') == $election->id)
                    >
                        {{ $election->name }}
                    </option>
                @endforeach
            </select>
            @if ($isFailedCreate)
                @error('election_id')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label for="party_description">Description</label>
            <input
                type="text"
                id="party_description"
                name="description"
                value="{{ $isFailedCreate ? old('description') : '' }}"
                maxlength="255"
            >
            @if ($isFailedCreate)
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        <button type="submit">Create Party</button>

    </form>

</x-modal>

{{-- Reopen creation popup only when failing in create party dialog --}}
@if ($errors->any() && $isFailedCreate)
    <script>
        document.getElementById('createPartyModal').showModal();
    </script>
@endif
