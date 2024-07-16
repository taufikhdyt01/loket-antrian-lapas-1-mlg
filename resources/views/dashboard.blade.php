<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manajemen Loket Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    LAPAS KELAS I MALANG
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">


            <div class="container d-flex flex-column align-items-center justify-content-center">
                <div id="loket-container" class="row justify-content-center">
                    @foreach ($lokets as $loket)
                        <div
                            class="col-lg-{{ 12 / min(3, count($lokets)) }} col-md-{{ 12 / min(2, count($lokets)) }} col-sm-12 mb-4">
                            <div class="card" style="background-color: white;">
                                <div class="card-body text-center">
                                    <h2>{{ $loket->name }}</h2>
                                    <hr>
                                    <h2>Nomor Antrian</h2>
                                    <p class="display-1 text-dark" id="loket{{ $loket->id }}-number">
                                        {{ $loket->last_called_number }}</p>
                                    <button class="btn btn-primary btn-lg mb-2"
                                        onclick="nextQueue({{ $loket->id }})">Antrian
                                        Selanjutnya</button>
                                    <div class="mb-2 row justify-content-center">
                                        <div class="col-md-6 text-center">
                                            <button class="btn btn-secondary" style="width: 150px"
                                                onclick="repeatQueue({{ $loket->id }})">Panggil
                                                Lagi</button>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <form action="{{ route('remove.loket', ['id' => $loket->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin hapus loket?');"
                                                    class="btn btn-danger" style="width: 150px">Hapus Loket</button>
                                            </form>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="input-group mb-2 d-flex justify-content-center align-items-center">
                                        <input type="number" class="form-control" style="max-width: 100px;"
                                            id="recall-number-loket{{ $loket->id }}" min="1" value="1"
                                            aria-label="Panggil Ulang Nomor">
                                        <button class="btn btn-warning ml-2"
                                            onclick="recallQueue({{ $loket->id }})">Panggil
                                            Ulang</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-4 justify-content-center">
                    <div class="col-md-6 text-center mb-2">
                        <form action="{{ route('queues.reset') }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin reset antrian?');"
                                class="btn btn-danger btn-lg" style="width: 150px; height:80px">Reset</button>
                        </form>
                    </div>
                    <div class="col-md-6 text-center mb-2">
                        <form action="{{ route('add.loket') }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin tambah loket?');"
                                class="btn btn-success btn-lg" style="width: 150px; height:80px">Tambah Loket</button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function nextQueue(loketId) {
            fetch('{{ route('queues.next') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        loket: loketId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.number) {
                        document.getElementById('loket' + loketId + '-number').textContent = data.number;
                    }
                    playAudio(data.audioFiles);
                });
        }

        function repeatQueue(loketId) {
            fetch('{{ route('queues.repeat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        loket: loketId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.number) {
                        document.getElementById('loket' + loketId + '-number').textContent = data.number;
                    }
                    playAudio(data.audioFiles);
                });
        }

        function recallQueue(loketId) {
            let recallNumber = document.getElementById('recall-number-loket' + loketId).value;

            fetch('{{ route('queues.recall') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        number: recallNumber,
                        loket: loketId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.number) {
                        document.getElementById('loket' + loketId + '-number').textContent = data.number;
                    }
                    playAudio(data.audioFiles);
                });
        }

        function playAudio(audioFiles) {
            let index = 0;

            function playNext() {
                if (index < audioFiles.length) {
                    let audio = new Audio(audioFiles[index]);
                    audio.play();
                    audio.onended = playNext;
                    index++;
                }
            }

            playNext();
        }
    </script>

</body>

</html>
