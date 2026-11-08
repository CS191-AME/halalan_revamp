{{-- Create Position button --}}
<button
    type="button"
    onclick="document.getElementById('createPositionModal').showModal()"
>
    + Add Position
</button>

{{-- Create Position modal --}}
<dialog id="createPositionModal">

    <div class="modal-content">

        <h2>Create Position</h2>

        {{-- Close button --}}
        <button
            type="button"
            onclick="document.getElementById('createPositionModal').close()"
        >
            ✕
        </button>

        {{-- Position form --}}
        <form
            method="POST"
            action="{{ route('admin.positions.store') }}"
        >

            @csrf

            {{-- Selected election --}}
            <input
                type="hidden"
                name="election_id"
                value="{{ $selectedElection->id }}"
            >

            {{-- Position name --}}
            <div>
                <label for="name">Position Name</label>

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

            {{-- Description --}}
            <div>
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                    maxlength="2000"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            {{-- Maximum selections --}}
            <div>
                <label for="max_selections">Maximum Selections</label>

                <input
                    type="number"
                    id="max_selections"
                    name="max_selections"
                    min="1"
                    max="50"
                    step="1"
                    value="{{ old('max_selections', 1) }}"
                    required
                >

                @error('max_selections')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            {{-- Ordinality --}}
            <div>
                <label for="ordinality">Ordinality</label>

                <input
                    type="number"
                    id="ordinality"
                    name="ordinality"
                    min="1"
                    max="65535"
                    step="1"
                    value="{{ old('ordinality', 1) }}"
                    required
                >

                @error('ordinality')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            {{-- Abstention setting --}}
            <div>
                <label>
                    <input
                        type="checkbox"
                        name="can_abstain"
                        value="1"
                        @checked(old('can_abstain', true))
                    >

                    Allow abstention
                </label>

                @error('can_abstain')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            {{-- General state error --}}
            @error('state')
                <p>{{ $message }}</p>
            @enderror

            {{-- Buttons --}}
            <button type="submit">
                Create Position
            </button>

        </form>

    </div>

</dialog>

{{-- Reopen modal when validation fails --}}
@if ($errors->any())
    <script>
        document.getElementById('createPositionModal').showModal();
    </script>
@endif