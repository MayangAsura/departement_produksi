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
                                ->paginate(5);

        // $transaksi->tgl->format('d-M-Y');
        // dd($transaksi);


        $transaksi->appends($request->only('search'));

        return view('pages.transaksi', [
            'title'    => 'Transaksi',
            'transaksi' => $transaksi,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);


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
        }

        // dd($this->code());

        $data_sales = [
            'kode'      => $this->get_code(),
            'tgl'       => $request->tgl,
            'cust_id'   => Customer::where('kode', $request->kode_c)->first()->id,
            // 'name'      => $request->name,
            'jumlah'    => $request->jumlah,
            'subtotal'  => $request->subtotal,
            'diskon'    => $request->diskon?$request->diskon:0,
            'ongkir'    => $request->ongkir,
            'total_bayar' => $request->total_bayar
        ];

        $sales = Transaksi::create($data_sales);
        
        foreach ($request->barang as $key => $value) {
            // dd($value. ' - '. $key);
            $data_sales_det = [
                'sales_id'  => $sales->id,
                'barang_id' => $request->barang['kode'],
                'harga_bandrol' => $request->barang['harga'],
                'qty' => $request->barang['qty'],
                'diskon_pct' => $request->barang['diskon_pct'],
                'diskon_nilai' => $request->barang['diskon_nilai'],
                'harga_diskon' => $request->barang['harga_diskon'],
                'total' => $request->barang['total']
            ];

            TransaksiDetail::create($data_sales_det);
        }


        return back();

        // return response()->json([
        //     'kode' => $request->kode,
        //     'tgl' => $request->tgl,
        //     'name' => $request->name,
        //     'barang' => $request->barang
        // ]);
        
    }

    public function get_code(){

        // return '00001';

        $format = date('Ym-');
        $seq = "0000";
        // $last_code = Transaksi::selectRaw('kode')
        $last_code = Transaksi::select(DB::raw('MAX(kode) as last_code'))
        ->havingRaw("YEAR('created_at') = " . date('Y') . " AND MONTH('created_at') = " . date('m') . " AND 'kode' LIKE '$format%'")
        ->orderBy('created_at','desc')
        ->groupByRaw('YEAR("created_at")')
        ->first();
        // $last_code = DB::select("SELECT MAX(kode) as last_code, id from t_sales where YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = " . date('m') . " AND kode LIKE ".$format."% group by id order by created_at desc ");
        
        // dd($last_code);
        // ->where("YEAR(created_at) = " . date('Y') . " AND MONTH(created_at) = " . date('m') . " AND kode LIKE '$format%'")
        $last = $last_code && strlen($last_code) == strlen($format . $seq) ? $last_code->last_code: $format . $seq;
        
        $start = strlen($last) - strlen($seq);
        $num = substr($last, $start) + 1;
        $new_seq = substr_replace($seq, $num, strlen($seq) - strlen($num));
        $code = substr_replace($last, $new_seq, $start);

        $check = Transaksi::where('kode', $code)->first();
        dd($last .' ' .$code . ' '.$check);
        
        if ($check) {
        $code = $this->get_code();
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
