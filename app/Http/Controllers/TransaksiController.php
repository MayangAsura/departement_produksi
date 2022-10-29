<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;

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
        $transaksi = Transaksi::where(
            ['kode'=>'like%'.$key. '%'],
            ['tgl'=>'like%'.$key. '%']
            // ['name', 'like', '%'.$key. '%'], 
            // ['subtotal', 'like', '%'.$key. '%'], 
            // ['diskon', 'like', '%'.$key. '%'], 
            // ['ongkir', 'like', '%'.$key. '%'],
            // ['total_bayar', 'like', '%'.$key. '%']
            )
        ->paginate(5);

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
