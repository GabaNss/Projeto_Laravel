@extends('layouts.main')

@section('title', 'Produtos')

@section('content')

<section class="simple-page">
    <h1>Tela de produtos</h1>

    @if($search)
        <p>O usuário está buscando por: <strong>{{ $search }}</strong></p>
    @endif

    <a href="{{ url('/') }}" class="btn-secondary">Voltar para home</a>
</section>

@endsection
