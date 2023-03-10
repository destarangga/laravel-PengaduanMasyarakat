<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;
use App\Models\Response;

class ReportController extends Controller
{

    public function exportPDF() { 
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        $data = Report::with('response')->get()->toArray(); 
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        view()->share('reports',$data); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf'); 
        }

        public function downPDF($id) { 
            // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
            $data = Report::with('response')->where('id', $id)->get()->toArray(); 
            // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
            view()->share('reports',$data); 
            // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
            $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
            // download PDF file dengan nama tertentu
            return $pdf->download('data_pengaduan_keseluruhan.pdf'); 
            }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        //ambil input bagian email & password
        $user = $request->only('email', 'password');
        //simpan data tersebut ke fitur auth sebagai identitas
        if (Auth::attempt($user)) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('data');
            }elseif(Auth::user()->role == 'petugas'){
                return redirect()->route('data.petugas');

            }
        }else {
            return redirect()->back()->with('eror', 'Gagal Login!');
        }
    }

    public function exportExcel()
    {
        //nama file yang akan terdownload 
        $file_name =
        'data_keseluruhan_pengaduan.xlsx';
        //memanggil file ReportsExport dan mendownloadnya dengan nama seperti $file name
        return Excel::download(new ReportsExport, $file_name);
    }

    public function index()
    {
        //orderby : mengurutkan data
        //ASC : terbesar - terkecil (1-100)
        //DESC : terkecil - terbesar (100-1)
        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('index', compact ('reports'));
    }
    // Request $ request ditambahkan karna pada halaman data ada fitur search nya, dan akan mengambil teks yang di input search
    public function data(Request $request)
    {
        $search = $request->search;
        $reports = Report::with('response')->where('nama', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }

    public function detailPetugas(Request $request)
    {
        $search = $request->search;
        $reports = Report::with('response')->where('nama', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()
        ->route('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("data");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required',
            'no_telp' => 'required|max:13',
            'pengaduan' => 'required',
            'foto' => 'required|image|mimes:jpeg: jpeg,jpg,png,svg',
        ]);

         $image = $request->file('foto');
         $imgName = rand() . '.' . $image->extension();
         $path = public_path('assets/image/');
         $image->move($path, $imgName);
 


        Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan, 
            'foto' => $imgName, 
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahakan pengaduan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cari data yang dimaksud
        $data = Report::where('id', $id)->firstOrFail();
        //data isinya -> nik sampe foto dari pengaduan
        //bikin variable yang isinya ngarah ke file foto terkait
        //public_path nyari file di folder public yang namnya sama kaya $data bagian foto
        $image = public_path('assets/image/'. $data['foto']);
        //uda nemu posisi fotonya , tinggal dihps fotonya pake unlink 
        unlink($image);
        //hapus $data yang isinya data nik-foto tadi, hapusnya di database
        $data->delete();
        Response::where('report_id', $id)->delete();
        //setelahnya dikembalikan lagi kehalaman awal
        return redirect()->back();    
    }
}
