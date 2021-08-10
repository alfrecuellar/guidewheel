<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Guidewheel</title>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <header class="navbar navbar-dark sticky-top bg-info flex-md-nowrap p-0 shadow">
                <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3">Guidewheel</a>
                <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </header>
            <main id="app">
                <home></home>
            </main>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
