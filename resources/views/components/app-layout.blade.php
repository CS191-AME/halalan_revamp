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
        }

        .card {
            max-width: 64rem;
            margin: 0 auto;
            padding: 1.5rem;
            background: #fff;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="card">
        {{ $slot }}
    </div>
</body>
</html>
