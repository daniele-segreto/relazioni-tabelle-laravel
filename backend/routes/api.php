<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API per ottenere tutti gli utenti con i relativi post
Route::get('/users', function () {
    return User::with('posts')->get();
});

// API per ottenere un singolo utente con i relativi post
Route::get('/users/{id}', function ($id) {
    return User::with('posts')->findOrFail($id);
});

// API per creare un nuovo post per un utente specifico
Route::post('/users/{userId}/posts', function (Request $request, $userId) {
    $user = User::findOrFail($userId);
    $post = new Post([
        'title' => $request->input('title'),
        'content' => $request->input('content')
    ]);
    $user->posts()->save($post);
    return $post;
});

// API per aggiornare un post esistente di un utente
Route::put('/users/{userId}/posts/{postId}', function (Request $request, $userId, $postId) {
    $post = Post::findOrFail($postId);
    $post->update($request->only(['title', 'content']));
    return $post;
});

// API per eliminare un post di un utente
Route::delete('/users/{userId}/posts/{postId}', function ($userId, $postId) {
    Post::where('user_id', $userId)->findOrFail($postId)->delete();
    return 204;
});
