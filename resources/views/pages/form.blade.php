@extends('index')

@section('title', 'Form Input Transaksi')

@push('styles')

    <link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.theme.min.css') }} ">

    <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css') }} ">
    
@endpush

@push('scripts')

    <script src="{{ asset('jquery-ui/jquery-ui.min.js') }} "></script>

    <script src="{{ asset('datatables/datatables.min.js') }} "></script>

    <script>

        
        $(document).ready(function(){
            
            $('#btn_kode').click(function(){
                $('#custModal').modal('show')
            })

            $("#search").autocomplete({
                source: '{{ route("autocomplete_cust") }}',
                appendTo: "#custModal",
                select: function (event, ui) {

                    $('#search').val(ui.item.label); 
                    $('#kode_c').val(ui.item.kode);
                    $('#name').val(ui.item.name);
                    $('#telp').val(ui.item.telp);
                 
                }
            });

            $('#tableBarang').DataTable();

        
            var no = 0;

            $(document).on('click', '#btn_select', function(){

                no++;
                var kode = $(this).data('kode')
                var nama = $(this).data('nama')
                var harga = $(this).data('harga')
                let qty = 1;
                let diskon_pct = 0; 
                let diskon_nilai = 0;
                let harga_diskon = harga;
                let total = harga_diskon * qty ;

                var tr = '<tr class="'+kode+'">'
                    tr+='<td><button type="button" class="btn btn-outline-warning edit" data-kode="'+kode+'" data-nama="'+nama+'" data-qty="'+qty+'" data-harga="'+harga+'" data-diskon_pct="'+diskon_pct+'" data-diskon_nilai="'+diskon_nilai+'" data-harga_diskon="'+harga_diskon+'" data-total="'+total+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-pencil"> edit</i>'
                    tr+=' <button type="button" class="btn btn-outline-danger delete" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-trash"> delete</i></td>'
                    tr+= '<td>'+no+'.</td>'
                    tr+= '<td>'+kode+'</td>'
                    tr+= '<td>'+nama+'</td>'
                    tr+= '<td>'+qty+'</td>'
                    tr+='<td>'+rupiah(harga)+'</td>'
                    tr+='<td>'+ persen(diskon_pct) +'</td>'
                    tr+='<td>'+ (diskon_nilai==0?'-':rupiah(diskon_nilai)) +'</td>'
                    tr+='<td>'+rupiah(harga_diskon)+'</td>'
                    tr+='<td>'+rupiah(total)+'</td> </tr>';

                $('tbody.barangs').append(tr)

                console.log(no + kode + nama + rupiah(harga) + persen(diskon_pct))
            })

            $(document).on('click', '.edit', function(){

                var kode = $(this).data('kode')
                var nama = $(this).data('nama')
                var harga = $(this).data('harga')
                let qty = $(this).data('qty')
                let diskon_pct = $(this).data('diskon_pct')
                let diskon_nilai = $(this).data('diskon_nilai')
                let harga_diskon = $(this).data('harga_diskon')
                let total = $(this).data('total')

                $('#editBarangsModal').modal('show')

                $('input[name="kode_m"]').val(kode)
                $('input[name="nama_m"]').val(nama)
                $('input[name="harga_m"]').val(harga)
                $('input[name="qty_m"]').val(qty)
                $('input[name="diskon_pct_m"]').val(diskon_pct)
                $('input[name="diskon_nilai_m"]').val(diskon_nilai)
                $('input[name="harga_diskon_m"]').val(harga_diskon)
                $('input[name="total_m"]').val(total)


                console.log(kode+ ' ' +nama+ ' '+ harga+ ' '+ qty+ ' '+ diskon_pct +' '+ diskon_nilai + ' '+ harga_diskon + ' '+ total)
            })

            $(document).on('click', '.update', function(){

                var kode = $('#kode_m').val()
                var nama = $('#nama_m').val()
                var harga = $('#harga_m').val()
                let qty = $('#qty_m').val()
                let diskon_pct = $('#diskon_pct_m').val()
                let diskon_nilai = $('#diskon_nilai_m').val()
                let harga_diskon = $('#harga_diskon_m').val()
                let total = $('#total_m').val()

                $("tr."+kode).children().remove()

                var td = '<td><button type="button" class="btn btn-outline-warning edit" data-kode="'+kode+'" data-nama="'+nama+'" data-qty="'+qty+'" data-harga="'+harga+'" data-diskon_pct="'+diskon_pct+'" data-diskon_nilai="'+diskon_nilai+'" data-harga_diskon="'+harga_diskon+'" data-total="'+total+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-pencil"> edit</i>'
                    td +=' <button type="button" class="btn btn-outline-danger delete" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-trash"> delete</i></td>'
                    td += '<td>'+no+'.</td>'
                    td += '<td>'+kode+'</td>'
                    td += '<td>'+nama+'</td>'
                    td += '<td>'+qty+'</td>'
                    td +='<td>'+rupiah(harga)+'</td>'
                    td +='<td>'+ persen(diskon_pct) +'</td>'
                    td +='<td>'+ (diskon_nilai==0?'-':rupiah(diskon_nilai)) +'</td>'
                    td +='<td>'+rupiah(harga_diskon)+'</td>'
                    td +='<td>'+rupiah(total)+'</td>'

                $("tr."+kode).append(td)

                $('#editBarangsModal').modal('hide')

            })

        });
        
        function diskon_nilai(harga){
            var diskon_pct = this.value

            var diskon_nilai = (diskon_pct / 100) * harga


        }

       
        


    </script>
    
@endpush
    
@section('content')
    
    <h1>Transaksi Baru</h1>


    <div class="row">
        <div class="col-12">
            {{-- <label for="">Transaksi</label><br> --}}
            
            <form action="" method="post">
                @csrf

                {{-- <div class="box box-widget">
                    <div class="box-body">
                        bismillah cek box
                    </div>
                </div> --}}
                <h6><u>Transaksi:</u></h6>
                <div class="row">
                    <label class="col-1 col-form-label">No.</label>
                    <div class="col-3">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm" id="kode" name="kode" value="" placeholder="AUTOGENERATE" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="tgl" class="col-1 col-form-label">Date</label>
                    <div class="col-3">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm datepicker" id="tgl" name="tgl" value="{{date('m/d/Y')}}"/>
                        </div>
                    </div>
                </div>
                <h6><u>Customer:</u></h6>
                <div class="row">
                    <label for="kode_c" class="col-1 col-form-label">Kode</label>
                    <div class="col-5 pl-0 ml-0">
                        {{-- <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-flat"></button>
                        </span> --}}
                        <button class="btn btn-sm " type="button" id="btn_kode">
                            <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="kode_c" name="kode_c" placeholder="" autofocus/>
                            <span class="input-group-text" id=""><i class="fa fa-search"></i></span>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <label for="kode_c" class="col-1 col-form-label">Nama</label>
                    <div class="col-5">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="" readonly/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="telp" class="col-1 col-form-label">Telp.</label>
                    <div class="col-5">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm" id="telp" name="telp" placeholder="" readonly/>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary my-3" id="add_barang" data-bs-toggle="modal" data-bs-target="#modalBarang"> + Barang </button>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="5%">No.</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Harga Bandrol</th>
                            <th>Diskon (%)</th>
                            <th>Diskon (Rp)</th>
                            <th>Harga Diskon</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="barangs">
    
                        {{-- number_format($number, 2, ',', '.') --}}
                       
    
    
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th colspan="4" class="table-active text-center" style="align-item:right">Grand Total</th>
                            <th id="grand_total"></th>
                        </tr>
                    </tfoot>
                </table>
                {{-- <div class="text-gray-600 bg-secondary-50">
                    {{ $transaksi->links() }}
                </div> --}}

            </form>
            {{-- <table class="table table-borderless">
                <tr>
                    <th>No</th>
                    <td><input type="text" name="no" id="no" class="form-control form-control-sm"></td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td><input type="text" name="tgl" id="tgl" class="form-control form-control-sm"></td>
                </tr>

            </table> --}}
            {{-- <div class="form-group">
                <label for="">No.</label>
                <input type="text" name="no" id="no" class="form-control form-control-sm">
            </div> --}}
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="modalBarang">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pilih Barang</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="x" >
                    </button>
                </div>
                <div class="modal-body table-responsive p-2">
                    <table class="table table-bordered table-striped" id="tableBarang">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga(Rp)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($barangs)>0)

                                @foreach ($barangs as $item)

                                    <tr>
                                        <td>{{$item->kode}} </td>
                                        <td>{{$item->nama}} </td>
                                        <td class="text-end">{{ number_format($item->harga, 2, ',', '.' )}} </td>
                                        <td> 
                                            <button type="button" class="btn btn-info" id="btn_select" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"
                                                data-kode="{{ $item->kode }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}">
                                                <i class="fa fa-check"> Pilih</i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            
                            @else

                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                            @endif


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="custModal" >
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-6 form-group">
                        <label class="">Kode</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" >
                        <input type="hidden" name="kode" id="kode" class="form-control form-control-sm" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-sm btn-primary">Simpan</button> --}}
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="editBarangsModal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="">Kode</label>
                            <input type="text" name="kode_m" id="kode_m" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-8 form-group">
                            <label class="">Nama</label>
                            <input type="text" name="nama_m" id="nama_m" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-8 form-group">
                            <label class="">Harga Bandrol</label>
                            <input type="text" name="harga_m" id="harga_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="">Diskon (%)</label>
                            <input type="text" name="diskon_pct_m" onblur="diskon_nilai()" id="diskon_pct_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="">Diskon (Rp)</label>
                            <input type="text" name="diskon_nilai_m" onblur="diskon_pct()" id="diskon_nilai_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4 form-group mb-2">
                            <label class="">Qty</label>
                            <input type="number" name="qty_m" id="qty_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="">Harga Diskon</label>
                            <input type="text" name="harga_diskon_m" id="harga_diskon_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class=""><b>Total</b></label>
                            <input type="text" name="total_m" id="total_m" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary update">Simpan</button>
            </div>
            </div>
        </div>
    </div>
      
      
@endsection
    