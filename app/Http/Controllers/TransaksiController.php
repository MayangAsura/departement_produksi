<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TransaksiDetail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    // public function index(){
        
    //     return view('pages.transaksi');
    // }

    public function index(Request $request){
        
        $pagination = 5;
        // $transaksi = Transaksi::when($request->search, function($query) use ($request) {
        //     $query->where('')
        // })
        $key = $request->search;
        $transaksi = Transaksi::select('t_sales.*', 'm_customers.kode as kode_c', 'm_customers.name', 'm_customers.telp')
                                ->join('m_customers', 'm_customers.id', '=', 't_sales.cust_id')
                                ->where('t_sales.kode', 'like','%'.$key. '%')
                                ->orWhere('tgl', 'like', '%'.$key. '%')
                                ->orWhere('name', 'like', '%'.$key. '%')
                                ->orWhere('jumlah', 'like', '%'.$key. '%')
                                ->orWhere('subtotal', 'like', '%'.$key. '%')
                                ->orWhere('diskon', 'like', '%'.$key. '%')
                                ->orWhere('ongkir', 'like', '%'.$key. '%')
                                ->orWhere('total_bayar', 'like', '%'.$key. '%')
                                ->get();
                                // ->paginate(5);

        // $transaksi->appends($request->only('search'));

        $grand_total = 0;
        if($transaksi){
            foreach ($transaksi as $key => $value) {
                $grand_total += $value->total_bayar;
            }
        }

        return view('pages.transaksi', [
            'title'    => 'Transaksi',
            'transaksi' => $transaksi,
            'grand_total' => $grand_total
        ]);
        // ->with('i', ($request->input('page', 1) - 1) * $pagination);


    }

    public function form_input(){
        
        $barangs = Barang::where('status', 1)->orderBy('kode', 'asc')->get();

        return view('pages.form', [
            'title' => 'Form Input Transaksi',
            'barangs' => $barangs
        ]);

    }

    public function save_transaksi(Request $request){

        // $validate = $this->validate($request,[
    	// 	'kode_c'    => 'required', //kode customer
        //     'name'      => 'required',
    	// 	'tgl'       => ['required', 'date_format:Y-m-d H:i:s'],
        //     'subtotal'  => ['required', 'numeric'],
        //     'ongkir'    => ['required', 'numeric'],
        //     'total_bayar' => ['required', 'numeric'],
    	// ]);

        // dd($validate);

        $validator = Validator::make($request->all(), [
            'kode_c'    => 'required', //kode customer
            'name'      => 'required',
    		'tgl'       => ['required', 'date_format:Y-m-d H:i:s'],
            'subtotal'  => ['required', 'numeric'],
            'ongkir'    => ['required', 'numeric'],
            'total_bayar' => ['required', 'numeric'],
         ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 200);

        }else{

            $data_sales = [
                'kode'      => TransaksiController::get_code(),
                'tgl'       => $request->tgl,
                'cust_id'   => Customer::where('kode', $request->kode_c)->first()->id,
                'jumlah'    => $request->jumlah,
                'subtotal'  => $request->subtotal,
                'diskon'    => $request->diskon?$request->diskon:0,
                'ongkir'    => $request->ongkir,
                'total_bayar' => $request->total_bayar
            ];
    
            $sales = Transaksi::create($data_sales);
            
            foreach ($request->barang as $key => $value) {
                
                $data_sales_det = [
                    'sales_id'  => $sales->id,
                    'barang_id' => Barang::where('kode', $value['kode'])->first()->id,
                    'harga_bandrol' => $value['harga'],
                    'qty' => $value['qty'],
                    'diskon_pct' => $value['diskon_pct'],
                    'diskon_nilai' => $value['diskon_nilai'],
                    'harga_diskon' => $value['harga_diskon'],
                    'total' => $value['total']
                ];
    
                TransaksiDetail::create($data_sales_det);
            }
    
    
            return response()->json(['code' => 200, 'msg' => 'Data berhasil ditambahkan!']);
        }
        
    }

    public function get_code(){

        // return '00001';

        $format = date('Ym-');
        $seq = "0000";

        $last_code = DB::select("SELECT MAX(kode) as last_code from t_sales where YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = " . date('m') . " AND kode LIKE '$format%' GROUP BY YEAR(created_at) order by created_at desc");

        $last_code = $last_code==[]? "" : $last_code[0]->last_code;
        // dd($last_code);
        // ->where("YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = " . date('m') . " AND kode LIKE '$format%'")
        $last = $last_code && strlen($last_code) == strlen($format . $seq) ? $last_code: $format . $seq;
        
        $start = strlen($last) - strlen($seq);
        $num = substr($last, $start) + 1;
        $new_seq = substr_replace($seq, $num, strlen($seq) - strlen($num));
        $code = substr_replace($last, $new_seq, $start);

        $check = Transaksi::where('kode', $code)->first();
        
        if ($check) {
        $code = TransaksiController::get_code();
        }
        return $code;
            
        
    }

    public function search2(Request $request){
        
        $key = $request->search;
        
        // dd($key);
        $transaksi = Transaksi::where(
            ['kode', 'like', '%'.$key. '%'],
            ['tgl', 'like', '%'.$key. '%'], 
            ['name', 'like', '%'.$key. '%'], 
            ['subtotal', 'like', '%'.$key. '%'], 
            ['diskon', 'like', '%'.$key. '%'], 
            ['ongkir', 'like', '%'.$key. '%'],
            ['total_bayar', 'like', '%'.$key. '%'])
        ->paginate(5);

        return view('pages.transaksi', compact('transaksi'));

    }
}
