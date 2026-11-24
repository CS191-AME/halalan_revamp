{{-- True only when the failed submission came from this create form --}}
@php
    $isFailedCreate = (bool) old('_creating_position');
@endphp

{{-- Position creation popup --}}

{{-- Button to open the popup --}}
<button
    type="button"
    onclick="document.getElementById('createPositionModal').showModal()"
>
    + Add Position
</button>

<x-modal id="createPositionModal" title="Create Position">

    <form method="POST" action="{{ route('admin.positions.store') }}">

        @csrf

        {{-- Invisible input to indicate we are creating a NEW position --}}
        <input type="hidden" name="_creating_position" value="1">

        {{-- Selected election --}}
        <input
            type="hidden"
            name="election_id"
            value="{{ $selectedElection->id }}"
        >

        {{-- Position name --}}
        <x-field
            id="name"
            name="name"
            label="Position Name"
            :value="$isFailedCreate ? old('name') : ''"
            :show-error="$isFailedCreate"
            required
            maxlength="255"
        />

        {{-- Description --}}
        <div>
            <label for="description">Description</label>

            <textarea
                id="description"
                name="description"
                maxlength="2000"
            >{{ $isFailedCreate ? old('description') : '' }}</textarea>

            @if ($isFailedCreate)
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Maximum selections --}}
        <x-field
            id="max_selections"
            name="max_selections"
            label="Maximum Selections"
            type="number"
            :value="$isFailedCreate ? old('max_selections', 1) : 1"
            :show-error="$isFailedCreate"
            min="1"
            max="50"
            step="1"
            required
        />

        {{-- Ordinality --}}
        <x-field
            id="ordinality"
            name="ordinality"
            label="Ordinality"
            type="number"
            :value="$isFailedCreate ? old('ordinality', 1) : 1"
            :show-error="$isFailedCreate"
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
                    @checked($isFailedCreate ? old('can_abstain') : true)
                >

                Allow abstention
            </label>

            @if ($isFailedCreate)
                @error('can_abstain')
                    <p>{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- General state error --}}
        @if ($isFailedCreate)
            @error('state')
                <p>{{ $message }}</p>
            @enderror
        @endif

        {{-- Form buttons --}}
        <button type="submit">Create Position</button>

    </form>

</x-modal>

{{-- Reopen creation popup only when failing in create position dialog --}}
@if ($errors->any() && $isFailedCreate)
    <script>
        document.getElementById('createPositionModal').showModal();
    </script>
@endif
