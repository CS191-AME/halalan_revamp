@props(['id', 'title'])

<dialog id="{{ $id }}" class="modal">
    <div class="modal-content">

        <h2>{{ $title }}</h2>

        <button
            type="button"
            onclick="document.getElementById('{{ $id }}').close()"
        >
            &times;
        </button>

        {{ $slot }}

    </div>
</dialog>
