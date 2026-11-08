{{-- Determine whether this is the election whose validation failed --}}
@php
    $isFailedEdit = (string) old('_editing_election_id') === (string) $election->id;
@endphp

{{-- Edit popup --}}
<dialog id="editElectionModal-{{ $election->id }}">

    <div class="modal-content">

        <h2>Edit Election</h2>

        <button
            type="button"
            onclick="document.getElementById('editElectionModal-{{ $election->id }}').close()"
        >
            ✕
        </button>

        <form
            method="POST"
            action="{{ route('admin.elections.update', $election) }}"
        >

            @csrf
            @method('PUT')

            {{-- Used to reopen the correct popup after validation fails --}}
            <input
                type="hidden"
                name="_editing_election_id"
                value="{{ $election->id }}"
            >

            {{-- Election name (always editable) --}}
            <div>
                <label for="name-{{ $election->id }}">Election Name</label>

                <input
                    type="text"
                    id="name-{{ $election->id }}"
                    name="name"
                    value="{{ $isFailedEdit ? old('name', $election->name) : $election->name }}"
                    required
                    maxlength="255"
                >

                @if ($isFailedEdit)
                    @error('name')
                        <p>{{ $message }}</p>
                    @enderror
                @endif
            </div>

            {{-- Additional fields are editable only for drafts --}}
            @if ($election->isDraft())

                {{-- Parent election --}}
                <div>
                    <label for="parent_id-{{ $election->id }}">
                        Parent Election
                    </label>

                    <select
                        id="parent_id-{{ $election->id }}"
                        name="parent_id"
                    >

                        <option value="">None (root election)</option>

                        @foreach ($parents as $parent)
                            @if ($parent->id !== $election->id)

                                <option
                                    value="{{ $parent->id }}"
                                    @selected(
                                        (string) ($isFailedEdit
                                            ? old('parent_id', $election->parent_id)
                                            : $election->parent_id)
                                        === (string) $parent->id
                                    )
                                >
                                    {{ $parent->name }}
                                </option>

                            @endif
                        @endforeach

                    </select>

                    @if ($isFailedEdit)
                        @error('parent_id')
                            <p>{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                {{-- Ballot setting --}}
                <div>
                    <label>
                        <input
                            type="checkbox"
                            name="ballots_enabled"
                            value="1"
                            @checked(
                                $isFailedEdit
                                    ? old('ballots_enabled', false)
                                    : $election->ballots_enabled
                            )
                        >

                        Enable ballots
                    </label>

                    @if ($isFailedEdit)
                        @error('ballots_enabled')
                            <p>{{ $message }}</p>
                        @enderror
                    @endif
                </div>

            @else

                <p>
                    This election has already started.
                    Only its name can be changed.
                </p>

            @endif

            {{-- Form buttons --}}
            <button type="submit">Save Changes</button>

            <button
                type="button"
                onclick="document.getElementById('editElectionModal-{{ $election->id }}').close()"
            >
                Cancel
            </button>

        </form>

    </div>

</dialog>

{{-- Reopen this particular popup if its validation fails --}}
@if ($errors->any() && $isFailedEdit)
    <script>
        document.getElementById('editElectionModal-{{ $election->id }}').showModal();
    </script>
@endif