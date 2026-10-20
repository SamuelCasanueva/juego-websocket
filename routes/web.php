<?php

use App\Http\Controllers\RemoteStyleController;
use App\Http\Controllers\RobcoTerminalController;
use Illuminate\Support\Facades\Route;

// Rutas originales de la cátedra UTN
Route::get('/emisor', [RemoteStyleController::class, 'emisor'])->name('emisor');
Route::post('/emitir', [RemoteStyleController::class, 'emitir'])->name('emitir');
Route::get('/receptor', [RemoteStyleController::class, 'receptor'])->name('receptor');

// Rutas del juego Hackeo de Terminal RobCo (Fallout)
Route::get('/', fn() => redirect()->route('terminal.lobby'));
Route::get('/terminal', [RobcoTerminalController::class, 'index'])->name('terminal.lobby');
Route::post('/terminal/identificador', [RobcoTerminalController::class, 'setOperatorId'])->name('terminal.operator');
Route::post('/terminal/crear', [RobcoTerminalController::class, 'createRoom'])->name('terminal.create');
Route::post('/terminal/unirse/{code}', [RobcoTerminalController::class, 'joinRoom'])->name('terminal.join');
Route::get('/terminal/sala/{code}', [RobcoTerminalController::class, 'showRoom'])->name('terminal.room');
Route::post('/terminal/sala/{code}/probar', [RobcoTerminalController::class, 'guessWord'])->name('terminal.guess');
Route::post('/terminal/sala/{code}/corchete', [RobcoTerminalController::class, 'useBracket'])->name('terminal.bracket');
Route::post('/terminal/sala/{code}/revancha', [RobcoTerminalController::class, 'rematch'])->name('terminal.rematch');
Route::get('/terminal/sala/{code}/espectador', [RobcoTerminalController::class, 'spectateRoom'])->name('terminal.spectate');
Route::post('/terminal/crear-ia', [RobcoTerminalController::class, 'createAiRoom'])->name('terminal.create.ai');
Route::post('/terminal/sala/{code}/interferencia', [RobcoTerminalController::class, 'sendInterference'])->name('terminal.interference');
Route::post('/terminal/chat/global', [RobcoTerminalController::class, 'sendLobbyMessage'])->name('terminal.chat.global');
Route::post('/terminal/sala/{code}/chat', [RobcoTerminalController::class, 'sendRoomMessage'])->name('terminal.chat.room');
Route::post('/terminal/salas/purgar', [RobcoTerminalController::class, 'purgeRooms'])->name('terminal.rooms.purge');

// Rutas de demostración para previsualizar páginas de error RobCo
if (app()->environment('local')) {
    Route::get('/demo/500', fn() => response()->view('errors.500', [], 500));
    Route::get('/demo/404', fn() => response()->view('errors.404', [], 404));
}


