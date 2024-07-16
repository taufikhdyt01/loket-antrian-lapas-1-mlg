<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrian</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        h1 {
            font-size: 3.5em;
            margin-top: 20px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            color: white;
            background-color: red;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        #calendar,
        #clock {
            font-size: 2em;
            color: black;
        }

        .divider {
            width: 80%;
            height: 2px;
            background: navy;
            margin: 10px auto;
        }

        .loket {
            background-color: lightcyan;
            border: 2px solid navy;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            flex: 1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .loket span.name {
            font-size: 3em;
            color: black;
        }

        .loket .divider {
            width: 80%;
            height: 2px;
            background: navy;
            margin: 10px auto;
        }

        .loket .label {
            font-size: 2em;
            color: black;
        }

        .loket .number {
            font-size: 10em;
            font-weight: bold;
            display: block;
            color: #ff0000;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 0;
        }

        .footer {
            font-size: 1em;
            color: navy;
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: white;
            padding: 10px 20px;
        }
    </style>
    <script>
        setTimeout(function() {
            location.reload();
        }, 3000);
    </script>
</head>

<body>
    <div class="container">
        <h1>LAYANGAN KUNJUNGAN TATAP MUKA OFFLINE LEMBAGA PEMASYARAKATAN KELAS I MALANG</h1>
        <div class="divider"></div>
        <div id="calendar"></div>
        <div id="clock"></div>
        <div class="divider"></div>
        <div class="row">
            @foreach ($lokets as $loket)
                <div
                    class="loket col-lg-{{ 12 / min(3, count($lokets)) }} col-md-{{ 12 / min(2, count($lokets)) }} col-sm-12 bg-blue-400">
                    <span class="name">{{ $loket->name }}</span>
                    <div class="divider"></div>
                    <span class="label">Nomor Antrian</span>
                    <span class="number" id="loket{{ $loket->id }}-number">{{ $loket->last_called_number }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="footer">KKN POLTEKIP ANGKATAN 56</div>
    <script>
        function showTime() {
            let now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            let time = hours + ':' + minutes + ':' + seconds;
            document.getElementById('clock').textContent = time;
            setTimeout(showTime, 1000); // memperbarui setiap detik
        }

        function showDate() {
            let now = new Date();
            let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            let months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                'November', 'Desember'
            ];
            let day = days[now.getDay()];
            let date = now.getDate();
            let month = months[now.getMonth()];
            let year = now.getFullYear();
            let dateString = day + ', ' + date + ' ' + month + ' ' + year;
            document.getElementById('calendar').textContent = dateString;
        }

        showTime();
        showDate();
    </script>
</body>

</html>
