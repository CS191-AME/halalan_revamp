<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Halalan Revamp' }}</title>

    <style>
        body {
            margin: 0;
            padding: 2rem;
            background: #f3f4f6;
            font-family: system-ui, sans-serif;
            color: #1f2937;
        }

        /* top bar with the home link, same width as the card so it lines up on the left */
        .top-bar {
            max-width: 64rem;
            margin: 0 auto 1rem;
        }

        .home-link {
            color: #7f1d1d;
            font-weight: 600;
            text-decoration: none;
        }

        .home-link:hover {
            text-decoration: underline;
        }

        .card {
            max-width: 64rem;
            margin: 0 auto;
            padding: 1.5rem;
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            color: #7f1d1d;
        }

        h2 {
            margin-top: 0;
        }

        /* flash messages are bare p tags directly inside the card */
        .card > p {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background: #dcfce7;
            color: #166534;
        }

        .card > p.flash-warning {
            background: #fef9c3;
            color: #854d0e;
        }

        .card > p.flash-error {
            background: #fee2e2;
            color: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th,
        td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        th {
            color: #6b7280;
            font-size: 0.875rem;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        /* forms inside table cells sit on one line */
        td form {
            display: inline;
        }

        td button {
            margin-right: 0.25rem;
        }

        button {
            padding: 0.4rem 0.9rem;
            border: none;
            border-radius: 0.375rem;
            background: #e5e7eb;
            color: #1f2937;
            font-size: 0.9rem;
            cursor: pointer;
        }

        button:hover {
            background: #d1d5db;
        }

        /* green submit buttons (Save changes, Create, Filter) */
        button[type="submit"] {
            background: #16a34a;
            color: #fff;
        }

        button[type="submit"]:hover {
            background: #15803d;
        }

        /* delete button: any button in a form that has a DELETE method input */
        form:has(input[name="_method"][value="DELETE"]) button {
            background: #8b0000;
            color: #fff;
        }

        form:has(input[name="_method"][value="DELETE"]) button:hover {
            background: #6b0000;
        }

        /* status pill */
        button.status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.9rem;
            border-radius: 999px;
            background: #fff;
            color: #1f2937;
            border: 1px solid #d1d5db;
        }

        button.status-pill:hover {
            background: #f3f4f6;
        }

        .status-dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background: #9ca3af;
        }

        .status-pill.is-running .status-dot {
            background: #22c55e;
        }

        /* results button: white, grey when disabled */
        button.results-button {
            background: #fff;
            color: #1f2937;
            border: 1px solid #d1d5db;
        }

        button.results-button:hover {
            background: #f3f4f6;
        }

        button.results-button:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* indent for child elections */
        .tree-name {
            padding-left: 0.75rem;
        }

        .tree-name-child {
            border-left: 2px solid #d1d5db;
        }

        label {
            display: block;
            margin-top: 0.75rem;
            font-weight: 600;
        }

        label:has(input[type="checkbox"]) {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: normal;
        }

        input:not([type="checkbox"]):not([type="hidden"]),
        select,
        textarea {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin-top: 0.25rem;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font: inherit;
        }

        /* hide the up and down arrows on number fields */
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* validation messages inside forms */
        form p {
            margin: 0.25rem 0 0;
            color: #dc2626;
            font-size: 0.875rem;
        }

        /* space above the submit button, only inside modals */
        .modal-content form button[type="submit"] {
            margin-top: 1rem;
        }

        /* GET filter form: fields side by side */
        form[method="GET"] {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        form[method="GET"] input:not([type="checkbox"]):not([type="hidden"]),
        form[method="GET"] select {
            width: auto;
            margin-top: 0;
        }

        form[method="GET"] button[type="submit"] {
            margin-top: 0;
        }

        .modal {
            width: 32rem;
            max-width: 90vw;
            padding: 0;
            border: none;
            border-radius: 0.75rem;
        }

        .modal::backdrop {
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            padding: 1.5rem;
        }

        /* x close button: only the direct child button of the modal */
        .modal-content > button {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.1rem 0.6rem;
            background: transparent;
            font-size: 1.5rem;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a class="home-link" href="{{ url('/') }}">&larr; Home</a>
    </div>

    <div class="card">
        {{ $slot }}
    </div>
</body>
</html>
