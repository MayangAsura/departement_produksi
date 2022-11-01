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

        
        let jumlah = 0
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
            let data = []


            $(document).on('click', '#btn_select', function(){

                var kode = $(this).data('kode')
                var nama = $(this).data('nama')
                let harga = $(this).data('harga')
                let qty = 0
                let diskon_pct = 0;
                let harga_diskon
                let total
                
                
                if($('tr.' + kode).length==0){
                    
                    qty = 1;
                    diskon_pct = 0; 
                    diskon_nilai = 0;
                    harga_diskon = harga;
                    total = harga_diskon * qty ;
                    
                    no++;

                    // datas[kode] = [{kode: kode,nama: nama, qty: qty, harga: harga, diskon_pct: diskon_pct, diskon_nilai: diskon_nilai, harga_diskon: harga_diskon , total: total}]
                    var temp = {"kode": kode,"nama": nama, "qty": qty, "harga": harga, "diskon_pct": diskon_pct, "diskon_nilai": diskon_nilai, "harga_diskon": harga_diskon , "total": total}
                    
                    data.push(temp)

                    calculate(data)
                    
    
                    var tr = '<tr class="'+kode+'">'
                        tr+='<td><button type="button" class="btn btn-outline-warning edit" data-kode="'+kode+'" data-nama="'+nama+'" data-qty="'+qty+'" data-harga="'+harga+'" data-diskon_pct="'+diskon_pct+'" data-diskon_nilai="'+diskon_nilai+'" data-harga_diskon="'+harga_diskon+'" data-total="'+total+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-pencil"> edit</i>'
                        tr+=' <button type="button" class="btn btn-outline-danger delete" data-kode="'+kode+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-trash"> delete</i></td>'
                        // tr+= '<td>'+no+'.</td>'
                        tr+= '<td>'+kode+'</td>'
                        tr+= '<td>'+nama+'</td>'
                        tr+= '<td class="text-end">'+qty+'</td>'
                        tr+='<td class="text-end">'+rupiah(harga)+'</td>'
                        tr+='<td class="text-end">'+ persen(diskon_pct) +'</td>'
                        tr+='<td class="text-end">'+ (diskon_nilai==0?'-':rupiah(diskon_nilai)) +'</td>'
                        tr+='<td class="text-end">'+rupiah(harga_diskon)+'</td>'
                        tr+='<td class="text-end">'+rupiah(total)+'</td> </tr>';
    
                    $('tbody.barangs').append(tr)



                }else{
                    // alert(' ada')

                    var barang = $('tr.' + kode+ '> td > button.edit')

                    // no = barang.data('no')
                    kode = barang.data('kode')
                    nama = barang.data('nama')
                    qty = barang.data('qty')
                    harga = barang.data('harga')
                    diskon_pct = barang.data('diskon_pct')
                    diskon_nilai = barang.data('diskon_nilai')
                    harga_diskon = barang.data('harga_diskon')
                    total = barang.data('total')

                    //Tambahkan QTY
                    qty++
                    //hitung Total
                    total = qty * harga_diskon

                    // console.log(datas)

                    var temp = {"kode": kode,"nama": nama, "qty": qty, "harga": harga, "diskon_pct": diskon_pct, "diskon_nilai": diskon_nilai, "harga_diskon": harga_diskon , "total": total}

                    if(data.find(d => d.kode === kode)){
                        data.map(d => {

                            if(d.kode == kode){
                                d.qty = qty;
                                d.diskon_pct = diskon_pct
                                d.diskon_nilai = diskon_nilai
                                d.harga_diskon = harga_diskon
                                d.total = total
                            }

                        })
                    }

                    calculate(data)


                    $("tr."+kode).children().remove()

                    var td = '<td><button type="button" class="btn btn-outline-warning edit" data-kode="'+kode+'" data-nama="'+nama+'" data-qty="'+qty+'" data-harga="'+harga+'" data-diskon_pct="'+diskon_pct+'" data-diskon_nilai="'+diskon_nilai+'" data-harga_diskon="'+harga_diskon+'" data-total="'+total+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-pencil"> edit</i>'
                        td +=' <button type="button" class="btn btn-outline-danger delete" data-kode="'+kode+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-trash"> delete</i></td>'
                        // td += '<td>'+no+'.</td>'
                        td += '<td>'+kode+'</td>'
                        td += '<td>'+nama+'</td>'
                        td += '<td class="text-end">'+qty+'</td>'
                        td +='<td class="text-end">'+rupiah(harga)+'</td>'
                        td +='<td class="text-end">'+ diskon_pct +'%</td>'
                        td +='<td class="text-end">'+ (diskon_nilai==0?'-':rupiah(diskon_nilai)) +'</td>'
                        td +='<td class="text-end">'+rupiah(harga_diskon)+'</td>'
                        td +='<td class="text-end">'+rupiah(total)+'</td>'

                    $("tr."+kode).append(td)

                    $('#modalBarang').modal('hide')

                }
                
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
                $('input[name="harga_m"]').val(rupiah(harga))
                $('input[name="qty_m"]').val(qty)
                $('input[name="diskon_pct_m"]').val(diskon_pct)
                $('input[name="diskon_nilai_m"]').val(rupiah(diskon_nilai))
                $('input[name="harga_diskon_m"]').val(rupiah(harga_diskon))
                $('input[name="total_m"]').val(rupiah(total))


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

                //Cleaning
                harga = cleaning(harga)
                
                diskon_nilai = cleaning(diskon_nilai)

                harga_diskon = cleaning(harga_diskon)

                total = cleaning(total)

                
                if(diskon_nilai){
                    harga_diskon = harga - diskon_nilai
                    total = qty * harga_diskon
                }
                console.log(kode +'-'+ nama +'-'+ qty +'-'+ harga+'-' +diskon_pct+'-' + diskon_nilai+'-' +harga_diskon+'-'+total)

                var temp = {"kode": kode,"nama": nama, "qty": qty, "harga": harga, "diskon_pct": diskon_pct, "diskon_nilai": diskon_nilai, "harga_diskon": harga_diskon , "total": total}

                if(data.find(d => d.kode === kode)){
                    data.map(d => {

                        if(d.kode == kode){
                            d.qty = qty;
                            d.diskon_pct = diskon_pct
                            d.diskon_nilai = diskon_nilai
                            d.harga_diskon = harga_diskon
                            d.total = total
                        }

                    })
                }else{
                    data.push(temp)
                }

                calculate(data)

                var td = '<td><button type="button" class="btn btn-outline-warning edit" data-kode="'+kode+'" data-nama="'+nama+'" data-qty="'+qty+'" data-harga="'+harga+'" data-diskon_pct="'+diskon_pct+'" data-diskon_nilai="'+diskon_nilai+'" data-harga_diskon="'+harga_diskon+'" data-total="'+total+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-pencil"> edit</i>'
                    td += '<button type="button" class="btn btn-outline-danger delete" data-kode="'+kode+'" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .50rem; --bs-btn-font-size: .65rem;"><i class="fa fa-trash"> delete</i></td>'
                    // td += '<td>'+no+'.</td>'
                    td += '<td>'+kode+'</td>'
                    td += '<td>'+nama+'</td>'
                    td += '<td class="text-end">'+qty+'</td>'
                    td +='<td class="text-end">'+rupiah(harga)+'</td>'
                    td +='<td class="text-end">'+ diskon_pct +'%</td>'
                    td +='<td class="text-end">'+ (diskon_nilai==0?'-':rupiah(diskon_nilai)) +'</td>'
                    td +='<td class="text-end">'+rupiah(harga_diskon)+'</td>'
                    td +='<td class="text-end">'+rupiah(total)+'</td>'

                $("tr."+kode).append(td)

                $('#editBarangsModal').modal('hide')

            })

            $(document).on('click', '.delete', function(){
    
                var kode = $(this).data('kode')

                //DELETE
                data.splice(data.indexOf(data.find(d => d.kode === kode)), 1)


                calculate(data)

                console.log(data)

                $("tr."+kode).remove()
    
            })

          
            $(document).on('click', '#simpan', function(){
                console.log(data)

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{ url('saveTransaksi') }}",
                    data : {
                        'barang' : data, 
                        'jumlah' : jumlah,
                        'tgl' : $('#tgl').val(), 
                        'kode_c' : $('#kode_c').val(),
                        'name' : $('#name').val(),
                        'subtotal' : cleaning($('#subtotal').val()),
                        'diskon' : cleaning($('#diskon').val()),
                        'ongkir' : cleaning($('#ongkir').val()),
                        'total_bayar' : cleaning($('#total_bayar').val()),
                    },
                    type : 'POST',
                    dataType : 'json',
                    success : function(result){

                        var ul = '<ul>'

                        for (let msg in result) {
                            // console.log(result + ": "+ result[msg][0])
                            ul+='<li>'+result[msg][0]+'</li>'
                        }
                        
                        ul+= '</ul>'
                        $('#error-message').addClass('alert').addClass('alert-danger')
                        $('#error-message').html(ul);
                        
                    }
                    
                });

                console.log(data)

            })

        });
        
        function calculate(data){

            let subtotal_ = 0
            let diskon = $('#diskon').val() ==""? 0 : $('#diskon').val()
            let ongkir = $('#ongkir').val() ==""? 0 : $('#ongkir').val()

            data.map(d => {
                subtotal_ += d.total
                jumlah += d.qty
            });

            let total_bayar_ = (subtotal_ - diskon) + parseInt(ongkir)

            $('#subtotal').val(rupiah(subtotal_))
            $('#total_bayar').val(rupiah(total_bayar_))
        }
        
        $('#diskon_pct_m').on('keyup', function(){
            
            let diskon_pct = this.value
            let harga = cleaning($('#harga_m').val())
            let qty = $('#qty_m').val()
    
            let diskon_nilai = (diskon_pct / 100) * harga
            let harga_diskon = harga - diskon_nilai
            let total = qty * harga_diskon

            $('#diskon_nilai_m').val(rupiah(diskon_nilai))
            $('#harga_diskon_m').val(rupiah(harga_diskon))
            $('#total_m').val(rupiah(total))
        })

        $('#qty_m').on('keyup', function(){

            let qty = this.value
            let harga = cleaning($('#harga_m').val())
            let diskon_nilai = cleaning($('#diskon_nilai_m').val())

            let harga_diskon = harga - diskon_nilai
            let total = qty * harga_diskon

            $('#total_m').val(rupiah(total))

        }) 

        $('#qty_m').blur(function(){

            if(this.value==0){
                $('#qty_msg').html('Qty tidak boleh 0 atau kosong').css('color', 'red');
            }else $('#qty_msg').html('');

        })

        $('#diskon').on('keyup', function(){

            let diskon = this.value==""? 0 : this.value
            let subtotal = cleaning($('#subtotal').val())
            let ongkir = $('#ongkir').val()==""? 0 : $('#ongkir').val()

            let total_bayar = (subtotal - diskon) + parseInt(ongkir)
            $('#total_bayar').val(rupiah(total_bayar))

        })
        $('#ongkir').on('keyup', function(){

            let ongkir = this.value
            // console.log(ongkir)
            let subtotal = cleaning($('#subtotal').val())
            let diskon = $('#diskon').val()==""? 0 : $('#diskon').val()
            
            console.log('diskon : '+diskon +' , ongkir : ' + ongkir)

            let total_bayar = (subtotal - diskon) + parseInt(ongkir)
            console.log(total_bayar)
            $('#total_bayar').val(rupiah(total_bayar))

        })
            




    </script>
    
@endpush
    
@section('content')
    
    <h1>Transaksi Baru</h1>


    <div class="row">
        <div class="col-12">
            {{-- menampilkan error validasi --}}
            <div id="error-message">
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
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
                            <input type="text" class="form-control form-control-sm" id="kode" name="kode" placeholder="AUTOGENERATE" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="tgl" class="col-1 col-form-label">Date</label>
                    <div class="col-3">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm" id="tgl" name="tgl" value="{{date('Y-m-d H:i:s')}}"/>
                            {{-- date('m/d/Y H:i:s') --}}
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
                            <input type="text" class="form-control form-control-sm" value="{{old('kode_c')}}" id="kode_c" name="kode_c" placeholder="" autofocus/>
                            <span class="input-group-text" id=""><i class="fa fa-search"></i></span>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <label for="kode_c" class="col-1 col-form-label">Nama</label>
                    <div class="col-5">
                        <div class="form-group" >
                            <input type="text" class="form-control form-control-sm" value="{{old('name')}}" id="name" name="name" placeholder="" readonly/>
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
                            {{-- <th width="5%">No.</th> --}}
                            <th width="12%">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-end" width="5%">Qty</th>
                            <th class="text-end">Harga Bandrol</th>
                            <th class="text-end">Diskon (%)</th>
                            <th class="text-end">Diskon (Rp)</th>
                            <th class="text-end">Harga Diskon</th>
                            <th class="text-end">Total</th>
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
                            <td></td>
                            <td></td>
                            <th colspan="2" class="table-active text-end">Sub Total</th>
                            <td>
                                <input type="text" name="subtotal" id="subtotal" value="{{old('subtotal')}}" class="form-control form-control-sm" disabled>
                            </td>
                            {{-- <th id="subtotal"></th> --}}
                        </tr>
                        <tr class="table-active">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th colspan="2" class="table-active text-end">Diskon</th>
                            <td>
                                Rp. <input type="text" name="diskon" id="diskon" value="{{old('diskon')}}" class="form-control form-control-sm">
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th colspan="2" class="table-active text-end">Ongkir</th>
                            <td>
                                Rp. <input type="text" name="ongkir" id="ongkir" value="{{old('ongkir')}}" class="form-control form-control-sm">
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th colspan="2" class="table-active text-end">Total Bayar</th>
                            <td>
                                <input type="text" name="total_bayar" id="total_bayar" value="{{old('total_bayar')}}" class="form-control form-control-sm" disabled>
                            </td>
                        </tr>

                    </tfoot>
                </table>
                {{-- <div class="text-gray-600 bg-secondary-50">
                    {{ $transaksi->links() }}
                </div> --}}

                <div class="row mb-5 justify-content-end">
                    <div class="col-md-2 offset-md-2">
                        <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
                        <button type="button" class="btn btn-danger" id="batal">Batal</button>
                    </div>
                    {{-- <div class="col-6 align-self-end">
                    </div> --}}
                </div>

            </form>
           

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
                            <input type="text" name="harga_m" id="harga_m" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="">Diskon (%)</label>
                            <input type="text" name="diskon_pct_m" id="diskon_pct_m" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="">Diskon (Rp)</label>
                            <input type="text" name="diskon_nilai_m" id="diskon_nilai_m" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="">Harga Diskon</label>
                            <input type="text" name="harga_diskon_m" id="harga_diskon_m" class="form-control form-control-sm" disabled>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="">Qty</label>
                            <input type="number" name="qty_m" id="qty_m" class="form-control form-control-sm" >
                            <small id="qty_msg"></small>
                        </div>
                        <div class="col-md-8">
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
    