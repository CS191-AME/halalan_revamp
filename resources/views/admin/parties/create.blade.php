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

        <x-field
            id="party_name"
            name="name"
            label="Party Name"
            :value="$isFailedCreate ? old('name') : ''"
            :show-error="$isFailedCreate"
            required
            maxlength="255"
        />

        <x-field
            id="party_alias"
            name="alias"
            label="Alias"
            :value="$isFailedCreate ? old('alias') : ''"
            :show-error="$isFailedCreate"
        />

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

        <x-field
            id="party_description"
            name="description"
            label="Description"
            :value="$isFailedCreate ? old('description') : ''"
            :show-error="$isFailedCreate"
            maxlength="255"
        />

        <button type="submit">Create Party</button>

    </form>

</x-modal>

{{-- Reopen creation popup only when failing in create party dialog --}}
@if ($errors->any() && $isFailedCreate)
    <script>
        document.getElementById('createPartyModal').showModal();
    </script>
@endif
