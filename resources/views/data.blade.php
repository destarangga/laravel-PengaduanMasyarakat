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
    <h2 class="title-table">Laporan Keluhan</h2>
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
    <a href="{{route('data')}}"><button type="submit" class="btn-login" style="margin-right: -150px; margin-top: -60px">Refresh</button></a>
    <a href="{{route('export-pdf')}}"><button type="submit" class="btn-login" style="margin-right: -7px; margin-top: -15px">Cetak PDF</button></a>
    <a href="{{route('export.excel')}}"><button type="submit" class="btn-login" style="margin-left: 10px; margin-top: -15px;">Cetak Excel</button></a>
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
                {{-- mengubaha format yang 08 mmenjadi 628 --}}
                @php
                    //
                $telp = substr_replace($report->no_telp, "62", 0, 1)

                @endphp
                
                @php
                if($report->response){
                    $pesanWA = 'Hallo' . $report->nama . '! Pengaduan anda di '. $report->response['status'] . ' berikut pesan untuk anda :' .
                    $report->response['pesan'];
                }
                else {
                    $pesanWA = 'Belum ada data response!';
                }
                @endphp
                <td><a href="https://wa.me/{{$telp}}?text={{$pesanWA }} " target="_blank">{{$telp}}</a></td>                
                <td>{{$report['pengaduan']}}</td>
                <td>
                <a href="../assets/image/{{$report->foto}}" target="_blank">
                        <img src="{{asset('assets/image/'. $report->foto)}}" width="120">
                </a>
                </td>
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
                <td>
                <form action="/hapus/{{$report->id}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" style="background-color: #ffce66">Delete</button>
                </form> 
                <form action="{{route('down.pdf', $report->id)}}" method="GET" class="btn-login" style="margin-top: -15px;">
                    @csrf
                    <button class="submit" style="background-color:  #9c9494;">Print</button>
                </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>