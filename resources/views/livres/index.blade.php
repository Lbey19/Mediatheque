@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="page-title">📚 Livres Disponibles</h1>

    <form action="{{ route('livres.index') }}" method="GET" class="search-bar">
        <input type="text" 
               name="search" 
               placeholder="Rechercher un livre, auteur ou genre..." 
               value="{{ request('search') }}"
               class="search-input">
        <button type="submit" class="search-button">
            <span>Rechercher</span>
            <i class="fas fa-search"></i>
        </button>
    </form>

    @foreach($livres as $livre)
        <div class="book-container">
            <div class="book-card">
                @if ($livre->image)
                    <img src="{{ asset('storage/' . $livre->image) }}" 
                         alt="{{ $livre->titre }}" 
                         class="book-image">
                @else
                    <div class="book-image-placeholder">
                        <i class="fas fa-book-open"></i>
                    </div>
                @endif

                <div class="book-content">
                    <h2 class="book-title">{{ $livre->titre }}</h2>
                    <h4 class="book-author">{{ $livre->auteur }}</h4>
                    <p class="book-genre">{{ ucfirst($livre->genre) }}</p>
                    <p class="book-description">{{ Str::limit($livre->description, 150) }}</p>

                    <div class="availability-badge {{ $livre->nb_exemplaires > 0 ? 'available' : 'unavailable' }}">
                        {{ $livre->nb_exemplaires > 0 ? 'Disponible' : 'Indisponible' }}
                    </div>

                    <a href="{{ route('livres.show', $livre->id) }}" class="cta-button">
                        Voir plus
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <div class="pagination-container">
        {{ $livres->links() }}
    </div>
</div>
@endsection

<style>
/* Améliorations principales */
.page-title {
    margin: 2rem 0 1.5rem;
    font-size: 2.5rem;
    text-align: center;
    color: #2c3e50;
    letter-spacing: -0.05em;
}

.search-bar {
    display: flex;
    justify-content: center;
    margin-bottom: 3rem;
    gap: 0.8rem;
}

.search-input {
    width: 400px;
    padding: 1rem 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    outline: none;
}

.search-button {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.search-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
}

.book-container {
    margin-bottom: 2rem;
}

.book-card {
    display: flex;
    gap: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.book-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.book-image {
    width: 180px;
    height: 260px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.book-image-placeholder {
    width: 180px;
    height: 260px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 2.5rem;
}

.book-content {
    flex: 1;
}

.book-title {
    margin: 0 0 0.5rem;
    font-size: 1.8rem;
    color: #2c3e50;
    line-height: 1.2;
}

.book-author {
    margin: 0 0 1rem;
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 500;
}

.book-genre {
    color: #0d6efd;
    font-weight: 500;
    margin-bottom: 1.2rem;
}

.book-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.availability-badge {
    display: inline-block;
    padding: 0.5rem 1.2rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.availability-badge.available {
    background: #e8f5e9;
    color: #2e7d32;
}

.availability-badge.unavailable {
    background: #ffebee;
    color: #c62828;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.8rem 1.5rem;
    background-color: #0d6efd;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cta-button:hover {
    background-color: #0b5ed7;
    transform: translateY(-1px);
    box-shadow: 0 3px 12px rgba(13, 110, 253, 0.2);
}

.pagination-container {
    margin-top: 3rem;
}

/* Responsive */
@media (max-width: 768px) {
    .book-card {
        flex-direction: column;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .book-image {
        width: 100%;
        height: 200px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .search-input {
        width: 100%;
    }
    
    .search-button span {
        display: none;
    }
    
    .search-button i {
        margin-left: 0;
    }
}
</style>