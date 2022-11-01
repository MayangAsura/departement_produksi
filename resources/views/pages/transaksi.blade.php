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
                                    {{-- <label for="search" class="d-block">Cari</label> --}}
                                    <input type="text" name="search" id="search" value="{{request('search')}} " placeholder="Cari ..." class="form-control form-control-sm">
                                </div>
                            </form>
                            {{-- <form action="{{ url()->current() }}"
                                method="get">
                                <div class="relative mx-auto">
                                <input type="search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search ....."
                                    class="block w-full pl-4 pr-10 text-sm leading-5 transition rounded-full shadow-sm border-secondary-300 bg-secondary-50 focus:bg-white focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </div>
                            </form> --}}
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

                    {{-- {{dd($transaksi) }} --}}
                    @if ($transaksi->count() > 0)
                    
                    @foreach ($transaksi as $item)

                    <tr>
                        <td>{{ $transaksi->count() * ($transaksi->currentPage() - 1) + $loop->iteration }}</td>
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
                        <th id="grand_total"></th>
                    </tr>
                </tfoot>
            </table>
            <div class="text-gray-600 bg-secondary-50">
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>

@endsection