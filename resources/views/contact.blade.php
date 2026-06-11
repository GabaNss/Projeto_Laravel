@extends('layouts.main')

@section('title', 'Contato')

@section('content')

<section class="simple-page">
    <h1>Contato</h1>
    <p>Esta é a página de contato.</p>

    <a href="{{ url('/') }}" class="btn-secondary">Voltar para home</a>
</section>

@endsection
