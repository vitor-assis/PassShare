<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretController;

// Rota da Página Inicial (Formulário)
Route::get('/', [SecretController::class, 'create'])->name('secret.create');

// Rota que recebe o envio do formulário (POST)
Route::post('/store', [SecretController::class, 'store'])->name('secret.store');

// 1. Tela de confirmação (quando clica no link)
Route::get('/secret/{hash}', [SecretController::class, 'confirm'])->name('secret.confirm');

// 2. Ação de revelar (quando aperta o botão)
Route::post('/secret/reveal', [SecretController::class, 'reveal'])->name('secret.reveal');

Route::get('/secret/download/{hash}', [SecretController::class, 'downloadSecret'])->name('secret.download');