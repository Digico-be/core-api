@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Réinitialiser le mot de passe</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Champ caché pour le token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required autofocus class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Réinitialiser</button>
        </form>
    </div>
@endsection
