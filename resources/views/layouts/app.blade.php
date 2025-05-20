@php use Illuminate\Support\Facades\Auth; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gametopia')</title>
    <link href="https://bootswatch.com/5/sketchy/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body, .navbar-nav {
            font-family: 'Arial', sans-serif;
        }
        h1, h2, h3, h4, h5 {
            font-weight: bold;
        }
        h1 { font-size: 2.5rem; }
        h2 { font-size: 2rem; }
        h3 { font-size: 1.75rem; }
        h4 { font-size: 1.5rem; }
        h5 { font-size: 1.25rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark sticky-top bg-body-tertiary" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-light" href="/"><span class="fas fa-brain me-1"> </span>Gametopia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="/games">Browse Games</a>
                </li>
                    @auth
                <li class="nav-item">
                    <a class="nav-link" href="/favorites">Favorite Games</a>
                </li>
                    @endauth
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">{{ Auth::user()->name }}</a>
                    </li>
                @endauth

                @auth
                    @php
                        $cartOrder = Auth::user()
                            ->orders()
                            ->where('status', 'pending')
                            ->withCount('items')
                            ->first();
                        $cartCount = $cartOrder?->items_count ?? 0;
                    @endphp

                    <li class="nav-item">
                        <a href="{{ route('cart.view') }}" class="nav-link d-flex align-items-center gap-1">
                            Basket
                            @if ($cartCount > 0)
                                <span class="badge bg-dark text-light">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                        <li class="nav-item">
                            <a href="{{ route('user.keys') }}" class="nav-link">My Keys</a>
                        </li>

                @endauth
                    @auth
                    <li class="nav-item">
                        <a href="{{ route('support.index') }}" class="nav-link">
                            <i class="fas fa-life-ring"></i> Support
                        </a>
                    </li>
                    @endauth
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer class="bg-dark text-light mt-5 py-4 border-top border-light">
    <div class="container">
        <div class="row g-4">

            <!-- Apie -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-3">About Gametopia</h5>
                <p class="small">
                    Join Gametopia – a place where gamers unite to discover, rate, and review top games.
                    Whether you love RPGs or shooters, we have something for everyone!
                </p>
            </div>

            <!-- Nuorodos -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-3">Quick Links</h5>
                <ul class="list-unstyled small">
                    <li><a href="/register" class="text-light text-decoration-none">Register</a></li>
                    <li><a href="/login" class="text-light text-decoration-none">Login</a></li>
                    <li><a href="/" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="/games" class="text-light text-decoration-none">Browse Games</a></li>
                    <li><a href="/favorites" class="text-light text-decoration-none">Favorites</a></li>
                    <li><a href="/profile" class="text-light text-decoration-none">Profile</a></li>
                </ul>
            </div>

            <!-- Kontaktai -->
            <div class="col-md-4">
                <h5 class="text-uppercase mb-3">Connect with Me</h5>
                <ul class="list-unstyled small">
                    <li><a href="https://www.facebook.com/giedrius.dauknys.1/" class="text-light text-decoration-none">
                            <i class="fab fa-facebook me-2"></i>Facebook</a></li>
                    <li><a href="https://www.reddit.com/user/Ponas_Giedrius1/" class="text-light text-decoration-none">
                            <i class="fab fa-reddit me-2"></i>Reddit</a></li>
                    <li><a href="https://www.instagram.com/giedriusdauknys/" class="text-light text-decoration-none">
                            <i class="fab fa-instagram me-2"></i>Instagram</a></li>
                </ul>
            </div>

        </div>

        <hr class="border-light my-4">

        <div class="text-center small">
            &copy; {{ date('Y') }} Gametopia. Built by Giedrius.
        </div>
    </div>
</footer>
