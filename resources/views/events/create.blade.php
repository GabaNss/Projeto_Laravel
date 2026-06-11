@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

<section id="event-create-container">
    <h1>Crie o seu evento</h1>

    @if($errors->any())
        <div class="form-errors">
            <p>Verifique os campos abaixo e tente novamente.</p>
        </div>
    @endif

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="image">Imagem do evento:</label>
            <input type="file" id="image" name="image" accept="image/*">
            @error('image')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" id="title" name="title" placeholder="Nome do evento" value="{{ old('title') }}">
            @error('title')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Data do evento:</label>
            <input type="date" id="date" name="date" value="{{ old('date') }}">
            @error('date')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">Cidade:</label>
            <input type="text" id="city" name="city" placeholder="Local do evento" value="{{ old('city') }}">
            @error('city')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="private">O evento é privado?</label>
            <select id="private" name="private">
                <option value="0" @selected(old('private', '0') === '0')>Não</option>
                <option value="1" @selected(old('private', '0') === '1')>Sim</option>
            </select>
            @error('private')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea id="description" name="description" placeholder="O que vai acontecer no evento?">{{ old('description') }}</textarea>
            @error('description')
                <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <fieldset class="form-group checkbox-group">
            <legend>Adicione itens de infraestrutura:</legend>

            @php
                $selectedItems = old('items', []);
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

        <input type="submit" class="btn-primary" value="Criar Evento">
    </form>
</section>

@endsection
