<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 

class ReportsExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
    * @return \Illuminate\Support\Collection
    */

    //mengambil data dari database diambil dari FromCollection
    public function collection()
    {
        //didalam sini boleh menyertakan perintah eloquent lain seperti where, all, dll
        return Report::with('response')->orderBy('created_at', 'DESC')->get();
    }

    //mengatur nama-nama column headers : diambil dari withHeadings
    public function headings(): array
    {
        return [
            'ID',
            'NIK Pelapor',
            'Nama Pelapor',
            'No Telp Pelapor',
            'Tanggal Pelaporan',
            'Pengaduan',
            'Status Response',
            'Pesan Response',
        ];
    }

    public function map($item): array
    {
        return[
            $item->id,
            $item->nik,
            $item->nama,
            $item->no_telp,
            \Carbon\Carbon::parse($item->created_at)->format('j-F-Y'),
            $item->pengaduan,
            $item->response ? $item->response['status'] : '-',
            $item->response ? $item->response['pesan'] : '-',
        ];
        
    }
}
