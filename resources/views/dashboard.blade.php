@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <div class="text-center">
        <h1 class="display-5 mb-4">Bienvenue sur votre tableau de bord, {{ Auth::user()->name }} !</h1>
        <p class="lead">Utilisez le menu pour consulter ou réserver des documents.</p>
    </div>
@endsection
