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
    <h2 class="title-table">Laporan Keluhan (Petugas)</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="{{route('logout')}}" style="text-align: center">Logout</a> 
    <div style="margin: 0 10px"> | </div>
    <a href="{{route('home')}}" style="text-align: center">Home</a>
</div>  
<div style="display: flex; justify-content: flex-end; align-items: center;">
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="Cari berdasarkan nama...">
        <button type="submit" class="btn-login" style="margin-top: -1px; background-color: silver;">Cari</button>
    </form>
    <a href="{{route('data')}}"><button type="submit" class="btn-login" style="margin-right: 10px; margin-top: -18px">Refresh</button></a>
</div>
<div style="padding: 0 30px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>    
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status</th>
            <th>Pesan</th>
            <th>Aksi</th>
            
        </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp

            @foreach ($reports as $report)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>
                <td>{{$report['no_telp']}}</td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                <img src="{{asset('assets/image/' . $report->foto)}}" width="120">
                <td>
                    @if ($report->response)
                    {{$report->response['status']}}
                    @else
                     -
                    @endif
                </td>
                <td>
                    @if ($report->response)
                    {{$report->response['pesan'] }}
                    @else
                    -
                    @endif
                </td>
                <td style="display: flex; justify-content: center;">
                    <a href="{{route('response.edit', $report->id)}}" class="back-btn">send respon</a>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>