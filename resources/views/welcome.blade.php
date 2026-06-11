@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')

<section id="search-container">
    <h1>Busque um evento</h1>
    <form action="{{ url('/') }}" method="GET">
        <input type="text" name="search" id="search" placeholder="Procurar..." value="{{ $search ?? '' }}">
    </form>
</section>

<section id="events-container">
    @if(! empty($search))
        <h2>Buscando por: {{ $search }}</h2>
    @else
        <h2>Próximos Eventos</h2>
    @endif

    <p class="subtitle">Veja os eventos dos próximos dias</p>

    <div id="cards-container">
        @forelse($events as $event)
            <article class="event-card">
                <img
                    src="{{ $event->image ? asset('img/events/' . $event->image) : asset('img/banner.svg') }}"
                    alt="Imagem do evento {{ $event->title }}"
                >

                <div class="card-body">
                    <p class="event-date">
                        {{ $event->date ? $event->date->format('d/m/Y') : 'Data a definir' }}
                    </p>
                    <h3>{{ $event->title }}</h3>
                    <p class="event-city">{{ $event->city }}</p>
                    <p class="event-participants">
                        {{ $event->users_count }} {{ $event->users_count === 1 ? 'participante' : 'participantes' }}
                    </p>
                    <a href="{{ route('events.show', $event->id) }}" class="btn-primary">Saber mais</a>
                </div>
            </article>
        @empty
            @if(! empty($search))
                <p class="empty-events">Não foi possível encontrar nenhum evento com {{ $search }}.</p>
            @else
                <p class="empty-events">Não há eventos disponíveis no momento.</p>
            @endif
        @endforelse
    </div>
</section>

@endsection
