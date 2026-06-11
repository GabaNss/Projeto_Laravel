<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:hdc', function () {
    $this->info('HDC Events pronto para a Aula 13.');
})->purpose('Mostra uma mensagem do projeto HDC Events');
