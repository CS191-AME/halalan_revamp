@php
    $isFailedEdit = (string) old('_editing_party_id') === (string) $party->id;
@endphp

<x-modal :id="'editPartyModal-' . $party->id" title="Edit Party">
    <form method="POST" action="{{ route('admin.parties.update', $party) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="_editing_party_id" value="{{ $party->id }}">

        <div>
            <label for="edit-name-{{ $party->id }}">Name</label>
            <input type="text" id="edit-name-{{ $party->id }}" name="name"
                value="{{ $isFailedEdit ? old('name') : $party->name }}">
            @if ($isFailedEdit)
                @error('name') <p>{{ $message }}</p> @enderror
            @endif
        </div>

        <div>
            <label for="edit-alias-{{ $party->id }}">Alias</label>
            <input type="text" id="edit-alias-{{ $party->id }}" name="alias"
                value="{{ $isFailedEdit ? old('alias') : $party->alias }}">
            @if ($isFailedEdit)
                @error('alias') <p>{{ $message }}</p> @enderror
            @endif
        </div>

        <div>
            <label for="edit-election-{{ $party->id }}">Election</label>
            @if ($party->election->isDraft())
                <select id="edit-election-{{ $party->id }}" name="election_id">
                    @foreach ($elections as $election)
                        <option value="{{ $election->id }}"
                            @selected((int) ($isFailedEdit ? old('election_id') : $party->election_id) === $election->id)>
                            {{ $election->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <span>{{ $party->election->name }}</span>
                <input type="hidden" name="election_id" value="{{ $party->election_id }}">
            @endif
            @if ($isFailedEdit)
                @error('election_id') <p>{{ $message }}</p> @enderror
            @endif
        </div>

        <div>
            <label for="edit-description-{{ $party->id }}">Description</label>
            <textarea id="edit-description-{{ $party->id }}" name="description">{{ $isFailedEdit ? old('description') : $party->description }}</textarea>
            @if ($isFailedEdit)
                @error('description') <p>{{ $message }}</p> @enderror
            @endif
        </div>

        <button type="submit">Save</button>
        <button type="button" onclick="this.closest('dialog').close()">Cancel</button>
    </form>
</x-modal>

@if ($errors->any() && $isFailedEdit)
    <script>
        document.getElementById('editPartyModal-{{ $party->id }}').showModal();
    </script>
@endif
