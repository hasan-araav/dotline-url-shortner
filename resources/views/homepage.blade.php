<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Dotline URL Shortner</title>

    <!-- Bootstrap core CSS -->
    <!-- Scripts -->
    @vite(['resources/css/bootstrap.min.css', 'resources/js/bootstrap.bundle.min.js'])
</head>

<body>

    <div class="container">
        <header class="d-flex w-full align-items-center pb-3 mb-5 border-bottom">
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded" aria-label="Eleventh navbar example">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('index') }}">Dotline URL Shortener</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarsExample09">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @if (Route::has('login'))
                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a href="{{ route('login') }}" class="nav-link">
                                            Log in
                                        </a>
                                    </li>

                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a href="{{ route('register') }}" class="nav-link">
                                                Register
                                            </a>
                                        </li>
                                    @endif
                                @endauth
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <div class="container">
                <div class="h6 mt-5 mb-5">
                    <div class="container mt-4">
                        <h2 class="mb-3">URL <strong><span class="text-primary">Shortener</span></strong></h2>
                        <form action="{{ route('url.shorten') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group input-group-lg">
                                <label class="input-group-text" for="url">Enter URL to shorten:</label>
                                <input type="url" class="form-control flex-grow-1" id="url" name="url"
                                    required="">
                                <button type="submit" class="btn btn-primary">Shorten URL</button>
                            </div>
                        </form>
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Validation Error</h4>
                                <p class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </p>
                            </div>
                        @endif
                        @if (Session::has('short_url'))
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Shortened URL:</h4>
                                <p class="mb-0"><a
                                        href="{{ Session::get('short_url') }}" target="_blank">{{ Session::get('short_url') }}</a>
                                </p>
                                <a href="{{ route('url.analytics', ['shortCode' => Session::get('short_code')])}}" class="btn btn-primary mt-4" target="_blank">View Analytics</a>
                            </div>
                        @endif
                        <p class="lead mt-3">Use this tool to create short, easy-to-share links.</p>
                    </div>
                </div>

                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
            </div>
        </main>
        <footer class="pt-5 my-5 text-muted border-top">
            Created by Hasan Uj Jaman with Love.
        </footer>
    </div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
