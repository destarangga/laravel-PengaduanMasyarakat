<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <main style="display: flex; justify-content: center; margin: 8%;">
        <div class="card form-card">
            <h2 style="text-align: center; margin-bottom: 20px;">Login Administrator</h2>
            @if ($errors->any())
        <div>
            <ul style="width: 100%; background: red; padding: 10px">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
             @endif

             @if (Session::get('eror'))
                <div style="width : 100%; background: red; padding: 5px">
                {{ Session::get('eror') }}
                </div>
            @endif

            <form action="{{route('auth')}}" method="post">
                @csrf
                <div class="input-card">
                    <label for="email">Username :</label>
                    <input type="email" name="email" id="">
                </div>
                <div class="input-card">
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="">
                </div>
                <button type="submit">Masuk</button>
                <a href="index.html" class="back-btn">Batal</a>
            </form>
        </div>
    </main>
</body>
</html>