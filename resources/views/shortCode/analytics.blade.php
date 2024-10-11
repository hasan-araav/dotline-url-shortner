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
            <div class="row">
                <div class="col-md-3 order-1 order-sm-1 order-md-0 order-lg-0 order-xl-0 mt-2 mt-md-0">
                    <div class="text-center"><!----></div>
                    <div class="card center text-center mx-auto mb-3 border">
                        <div class="card-body py-2">
                            <h1>{{ $analytics['total_visits'] }}</h1>
                            Total Visits
                        </div>
                    </div>
                    <div class="card center text-center mx-auto mb-3 border">
                        <div class="card-body py-2">
                            <h1>{{ $analytics['unique_visits'] }}</h1>
                            Unique Visits
                        </div>
                    </div>
                </div>
                <div class="col-md-9 order-0 order-sm-0 order-md-1 order-lg-1 order-xl-1">
                    <ul class="list-group">
                        <li class="list-group-item bg-light d-flex justify-content-between align-items-center"><a
                                href="{{ url('t/'. $url->short_code) }}" target="_blank"
                                class="lead d-lg-none d-xl-none">{{ url('t/'. $url->short_code) }}</a> <a
                                href="{{ url('t/'. $url->short_code) }}" target="_blank"
                                class="lead d-none d-lg-inline">{{ url('t/'.$url->short_code) }}</a>
                            <div> <button type="button" class="btn btn-primary"><i aria-hidden="true"
                                        class="fa fa-copy"></i> <span
                                        class="d-none d-sm-none d-md-inline">Copy</span></button></div>
                        </li>
                        <li class="list-group-item break-word"><a href="{{ url($url->original_url) }}"
                                rel="noopener nofollow noreferrer" target="_blank">
                                {{ $url->original_url }}</a></li> <!---->
                        <li class="list-group-item">Created: {{ $url->created_at }}</li> <!---->
                    </ul>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="card center text-center mx-auto">
                        <div class="card-header text-white bg-primary">Average Daily</div>
                        <div class="card-body">
                            <h1 class="card-title">{{ $analytics["avg_daily_visits"] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card center text-center mx-auto">
                        <div class="card-header text-white bg-primary">Average Weekly</div>
                        <div class="card-body">
                            <h1 class="card-title">{{ $analytics["avg_weekly_visits"] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card center text-center mx-auto">
                        <div class="card-header text-white bg-primary">Average Monthly</div>
                        <div class="card-body">
                            <h1 class="card-title">{{ $analytics["avg_monthly_visits"] }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="pt-5 my-5 text-muted border-top">
            Created by Hasan Uj Jaman with Love.
        </footer>
    </div>
</body>

</html>
