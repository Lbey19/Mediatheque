{{-- filepath: c:\wamp64\www\mediatheque\resources\views\layouts\app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Médiathèque Municipale')</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Breeze Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #2c5f2d;
            --secondary-color: #97bc62;
            --accent-color: #f4ca44;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
        }
        .navbar-brand {
            font-family: 'Georgia', serif;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .navbar-brand i {
            color: var(--accent-color);
            margin-right: 12px;
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover i {
            transform: rotate(-15deg);
        }
        .nav-link {
            position: relative;
            margin: 0 1rem;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0 1.5rem;
            /* margin-top: 5rem; */ /* Retiré car flexbox gère l'espacement */
            border-top: 3px solid var(--accent-color);
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding-bottom: 2rem;
        }
        .footer-section h5 {
            color: var(--accent-color);
            border-bottom: 2px solid;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: var(--accent-color);
        }
        .social-links {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .social-links a {
            font-size: 1.5rem;
            color: white;
            transition: transform 0.3s ease;
        }
        .social-links a:hover {
            transform: translateY(-3px);
            color: var(--accent-color);
        }
        .copyright {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--accent-color);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            opacity: 0; /* Caché par défaut */
            z-index: 1000; /* Assure qu'il est au-dessus */
        }
        .back-to-top:hover {
            transform: translateY(-3px);
        }

        /* --- STYLES POUR FOOTER FIXE --- */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Assure que le body prend au moins toute la hauteur de la vue */
        }

        main {
            flex-grow: 1; /* Fait en sorte que le contenu principal pousse le footer vers le bas */
        }
        /* --- FIN STYLES POUR FOOTER FIXE --- */

    </style>
    @stack('styles') {{-- Ajouté pour les styles spécifiques à la page --}}
</head>
<body> {{-- La balise body est maintenant un conteneur flex --}}
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book-open"></i>Médiathèque
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('livres.*') ? 'active' : '' }}" href="{{ route('livres.index') }}">Livres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cds.*') ? 'active' : '' }}" href="{{ route('cds.index') }}">CDs</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Mon Profil</a> {{-- Ajout de la classe active --}}
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Déconnexion</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
                {{-- Barre de recherche globale (optionnelle, peut être retirée si gérée par page) --}}
                {{-- <form class="d-flex ms-3">
                    <input class="form-control me-2" type="search" placeholder="Rechercher...">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form> --}}
            </div>
        </div>
    </nav>
</header>

{{-- L'élément main prendra l'espace restant grâce à flex-grow: 1 --}}
<main class="container my-5">
    @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @yield('content')
</main>

{{-- Le footer sera poussé en bas --}}
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i>240, rue de l'Acropole - 34000 Montpellier</li>
                    <li><i class="fas fa-phone me-2"></i>04 67 34 87 00</li>
                    <li><i class="fas fa-envelope me-2"></i>contact@mediatheque.fr</li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Horaires</h5>
                <ul class="list-unstyled">
                    <li>Mardi–Jeudi : 10h–18h</li>
                    <li>Vendredi : 14h–18h</li>
                    <li>Samedi : 10h–18h</li>
                    <li>Fermé lundi et dimanche</li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Réseaux sociaux</h5>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright text-center">
            &copy; {{ date('Y') }} Médiathèque Municipale - Tous droits réservés
        </div>
    </div>
</footer>

{{-- Bouton Back to Top --}}
<div class="back-to-top">
    <i class="fas fa-arrow-up text-dark"></i>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) { // Vérifie si l'élément existe
        window.addEventListener('scroll', () => {
            backToTop.style.opacity = window.scrollY > 300 ? '1' : '0';
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
</script>
@stack('scripts') {{-- Ajouté pour les scripts spécifiques à la page --}}
</body>
</html>