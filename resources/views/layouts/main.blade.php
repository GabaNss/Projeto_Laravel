<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @php
        $stylePath = public_path('css/styles.css');
        $styleVersion = file_exists($stylePath) ? filemtime($stylePath) : time();
    @endphp
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ $styleVersion }}">
</head>
<body>

<header>
    <nav class="navbar">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.svg') }}" alt="Logo HDC Events">
            <span>HDC Events</span>
        </a>

        <div class="nav-links">
            <a href="{{ url('/') }}">Eventos</a>

            @auth
                <a href="{{ route('events.create') }}">Criar Eventos</a>
                <a href="{{ route('dashboard') }}">Meus Eventos</a>
                <a href="{{ route('profile.show') }}">Perfil</a>
                <form action="{{ route('logout') }}" method="POST" class="nav-form">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}">Entrar</a>
                <a href="{{ route('register') }}">Cadastrar</a>
            @endauth

            <a href="{{ url('/contact') }}">Contato</a>
            <a href="{{ url('/products') }}">Produtos</a>
        </div>
    </nav>
</header>

<main>
    @if(session('msg'))
        <p class="msg">{{ session('msg') }}</p>
    @endif

    @if(session('status'))
        <p class="msg">{{ session('status') }}</p>
    @endif

    @yield('content')
</main>

<footer>
    <p>HDC Events &copy; 2026</p>
</footer>

</body>
</html>
