@extends('layouts.main')

@section('title', $event->title)

@section('content')

<section id="event-show-container">
    <img
        class="event-show-image"
        src="{{ $event->image ? asset('img/events/' . $event->image) : asset('img/banner.svg') }}"
        alt="Imagem do evento {{ $event->title }}"
    >

    <div class="event-show-info">
        <h1>{{ $event->title }}</h1>

        <p class="event-location">{{ $event->city }}</p>
        <p class="event-date-detail">
            {{ $event->date ? $event->date->format('d/m/Y') : 'Data a definir' }}
        </p>
        <p class="event-owner">
            Dono do evento: {{ $event->user ? $event->user->name : 'Não informado' }}
        </p>
        <p class="event-participants-detail">
            {{ $event->users_count }} {{ $event->users_count === 1 ? 'participante confirmado' : 'participantes confirmados' }}
        </p>
        <p class="event-privacy">
            {{ $event->private ? 'Evento privado' : 'Evento público' }}
        </p>

        <div class="event-join-actions">
            @auth
                @if(! $hasUserJoined)
                    <form action="{{ route('events.join', $event->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">Confirmar presença</button>
                    </form>
                @else
                    <p class="joined-message">Você já está participando deste evento.</p>
                    <form action="{{ route('events.leave', $event->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary">Sair do evento</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-primary">Entrar para participar</a>
            @endauth
        </div>

        <h3>O evento conta com:</h3>

        <ul class="items-list">
            @forelse($event->items ?? [] as $item)
                <li>{{ $item }}</li>
            @empty
                <li>Nenhum item cadastrado.</li>
            @endforelse
        </ul>

        <h3>Sobre o evento</h3>
        <p class="event-description">{{ $event->description }}</p>

        <a href="{{ url('/') }}" class="btn-secondary">Voltar para eventos</a>
    </div>
</section>

@endsection
