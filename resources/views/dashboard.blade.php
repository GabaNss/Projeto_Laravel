@extends('layouts.main')

@section('title', 'Meus Eventos')

@section('content')

<section class="dashboard-page">
    <div class="dashboard-header">
        <h1>Meus eventos</h1>
        <a href="{{ route('events.create') }}" class="btn-primary">Criar Evento</a>
    </div>

    <section class="dashboard-section">
        <h2>Eventos criados por mim</h2>

        @if($events->count() > 0)
            <div class="table-wrapper">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data</th>
                            <th>Participantes</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                                </td>
                                <td>{{ $event->date ? $event->date->format('d/m/Y') : 'Data a definir' }}</td>
                                <td>{{ $event->users_count }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn-secondary">Editar</a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="empty-events">Você ainda não criou nenhum evento.</p>
        @endif
    </section>

    <section class="dashboard-section">
        <h2>Eventos que estou participando</h2>

        @if($eventsAsParticipant->count() > 0)
            <div class="table-wrapper">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Dono</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventsAsParticipant as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                                </td>
                                <td>{{ $event->user ? $event->user->name : 'Não informado' }}</td>
                                <td>{{ $event->date ? $event->date->format('d/m/Y') : 'Data a definir' }}</td>
                                <td>
                                    <form action="{{ route('events.leave', $event->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja sair deste evento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Sair do evento</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="empty-events">Você ainda não está participando de nenhum evento.</p>
        @endif
    </section>
</section>

@endsection
