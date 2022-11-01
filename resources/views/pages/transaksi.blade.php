@extends('index')

@section('title', 'Transaksi')

@push('scripts')
    
@endpush

@section('content')
    
    <h1>Daftar Transaksi</h1>
    <div class="row">
        {{-- <div class="col align-self-end">
        </div> --}}
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{-- <td class="text-end">Cari</td> --}}
                        <td colspan="3">
                            <form action="{{ url()->current() }} " method="get">
                                <div class="form-group">
                                    <input type="text" name="search" id="search" value="{{request('search')}} " placeholder="Cari ..." class="form-control form-control-sm">
                                </div>
                            </form>
                        
                        </td>
                    </tr>
                    <tr>
                        <th>No.</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nama Customer</th>
                        <th class="text-end">Jumlah Barang</th>
                        <th class="text-end">Sub Total</th>
                        <th class="text-end">Diskon</th>
                        <th class="text-end">Ongkir</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>

                    
                    @if ($transaksi->count() > 0)
                    
                    @foreach ($transaksi as $key => $item)

                    <tr>
                        {{-- <td>{{ $transaksi->count() * ($transaksi->currentPage() - 1) + $loop->iteration }}</td> --}}
                        <td>{{$key+1}} </td>
                        <td> {{$item->kode}} </td>
                        <td> {{ Carbon\Carbon::parse($item->tgl)->format('d-M-Y')}} </td>
                        <td> {{$item->name}} </td>
                        <td class="text-end"> {{$item->jumlah}} </td>
                        <td class="text-end"> @currency($item->subtotal) </td>
                        <td class="text-end"> {{$item->diskon}}</td>
                        <td class="text-end"> @currency($item->ongkir)</td>
                        <td class="text-end"> @currency($item->total_bayar)</td>
                    </tr>
                        
                    @endforeach

                    @else

                    <tr class="select-none">
                        <td class="px-6 py-3 leading-4 text-center whitespace-nowrap">-</td>
                        <td class="px-6 py-3 leading-4 whitespace-nowrap">-</td>
                        <td class="px-6 py-3 leading-4 whitespace-nowrap">-</td>
                        <td class="px-6 text-right whitespace-nowrap">-</td>
                        <td class="px-6 py-3 text-center whitespace-nowrap">-</td>
                        <td class="px-6 py-3 whitespace-nowrap">-</td>
                        <td class="px-6 py-3 whitespace-nowrap">-</td>
                        <td class="px-6 text-right whitespace-nowrap">-</td>
                        <td class="px-6 text-right whitespace-nowrap">-</td>
                    </tr>
                        
                    @endif


                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th colspan="4" class="table-active text-center" style="align-item:right">Grand Total</th>
                        <th id="grand_total" class="text-end">@currency($grand_total) </th>
                    </tr>
                </tfoot>
            </table>
            {{-- <div class="text-gray-600 bg-secondary-50" width=10%>
                {{ $transaksi->links() }}
            </div> --}}
        </div>
    </div>

@endsection