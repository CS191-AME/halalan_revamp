@props(['id', 'name', 'label', 'value' => '', 'type' => 'text', 'showError' => true])

<div>
    <label for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes }}>
    @if ($showError)
        @error($name)
            <p>{{ $message }}</p>
        @enderror
    @endif
</div>
