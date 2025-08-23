<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased bg-neutral-50 dark:bg-neutral-950">
    {{ $slot }}
</body>

</html>
