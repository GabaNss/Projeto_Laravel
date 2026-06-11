@extends('layouts.main')

@section('title', 'Editar Evento')

@section('content')

<section id="event-create-container">
    <h1>Editando: {{ $event->title }}</h1>

    @if($errors->any())
        <div class="form-errors">
            <p>Verifique os campos abaixo e tente novamente.</p>
        </div>
    @endif

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="image">Imagem do evento:</label>
            @if($event->image)
                <img class="current-event-image" src="{{ asset('img/events/' . $event->image) }}" alt="Imagem atual do evento {{ $event->title }}">
            @endif
            <input type="file" id="image" name="image" accept="image/*">
            @error('image')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" id="title" name="title" placeholder="Nome do evento" value="{{ old('title', $event->title) }}">
            @error('title')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Data do evento:</label>
            <input type="date" id="date" name="date" value="{{ old('date', $event->date ? $event->date->format('Y-m-d') : '') }}">
            @error('date')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">Cidade:</label>
            <input type="text" id="city" name="city" placeholder="Local do evento" value="{{ old('city', $event->city) }}">
            @error('city')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="private">O evento é privado?</label>
            <select id="private" name="private">
                <option value="0" @selected((string) old('private', (int) $event->private) === '0')>Não</option>
                <option value="1" @selected((string) old('private', (int) $event->private) === '1')>Sim</option>
            </select>
            @error('private')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea id="description" name="description" placeholder="O que vai acontecer no evento?">{{ old('description', $event->description) }}</textarea>
            @error('description')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <fieldset class="form-group checkbox-group">
            <legend>Adicione itens de infraestrutura:</legend>

            @php
                $selectedItems = old('items', $event->items ?? []);
                $items = ['Cadeiras', 'Palco', 'Cerveja grátis', 'Open food', 'Brindes'];
            @endphp

            @foreach($items as $item)
                <label>
                    <input
                        type="checkbox"
                        name="items[]"
                        value="{{ $item }}"
                        @checked(in_array($item, $selectedItems))
                    >
                    {{ $item }}
                </label>
            @endforeach

            @error('items')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </fieldset>

        <div class="form-actions">
            <input type="submit" class="btn-primary" value="Salvar alterações">
            <a href="{{ route('dashboard') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</section>

@endsection
