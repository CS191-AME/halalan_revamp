{{-- True only when the failed submission came from this position's edit form --}}
@php
    $isFailedEdit = (string) old('_editing_position_id') === (string) $position->id;
@endphp

<x-modal :id="'editPositionModal-' . $position->id" title="Edit Position">

    <form method="POST" action="{{ route('admin.positions.update', $position) }}">

        @csrf
        @method('PUT')

        {{-- Used to reopen the correct popup after validation fails --}}
        <input type="hidden" name="_editing_position_id" value="{{ $position->id }}">

        {{-- Position name --}}
        <x-field
            :id="'edit-position-name-' . $position->id"
            name="name"
            label="Position Name"
            :value="$isFailedEdit ? old('name') : $position->name"
            :show-error="$isFailedEdit"
            required
            maxlength="255"
        />

        {{-- Description --}}
        <div>
            <label for="edit-position-description-{{ $position->id }}">Description</label>

            <textarea
                id="edit-position-description-{{ $position->id }}"
                name="description"
                maxlength="2000"
            >{{ $isFailedEdit ? old('description') : $position->description }}</textarea>

            @if ($isFailedEdit)
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Maximum selections --}}
        <x-field
            :id="'edit-position-max-selections-' . $position->id"
            name="max_selections"
            label="Maximum Selections"
            type="number"
            :value="$isFailedEdit ? old('max_selections') : $position->max_selections"
            :show-error="$isFailedEdit"
            min="1"
            max="50"
            step="1"
            required
        />

        {{-- Ordinality --}}
        <x-field
            :id="'edit-position-ordinality-' . $position->id"
            name="ordinality"
            label="Ordinality"
            type="number"
            :value="$isFailedEdit ? old('ordinality') : $position->ordinality"
            :show-error="$isFailedEdit"
            min="1"
            max="65535"
            step="1"
            required
        />

        {{-- Abstention setting --}}
        <div>
            <label>
                <input
                    type="checkbox"
                    name="can_abstain"
                    value="1"
                    @checked($isFailedEdit ? old('can_abstain') : $position->can_abstain)
                >

                Allow abstention
            </label>

            @if ($isFailedEdit)
                @error('can_abstain')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- General state error --}}
        @if ($isFailedEdit)
            @error('state')
                <p>{{ $message }}</p>
            @enderror
        @endif

        {{-- Form buttons --}}
        <button type="submit">Save Changes</button>

        <button
            type="button"
            onclick="document.getElementById('editPositionModal-{{ $position->id }}').close()"
        >
            Cancel
        </button>

    </form>

</x-modal>

{{-- Reopen this particular popup if its validation fails --}}
@if ($errors->any() && $isFailedEdit)
    <script>
        document.getElementById('editPositionModal-{{ $position->id }}').showModal();
    </script>
@endif
