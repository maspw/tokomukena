<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">No Pembelian</th>
            <th class="border border-gray-300 px-4 py-2">Supplier</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal</th>
            <th class="border border-gray-300 px-4 py-2">Status</th>
            <th class="border border-gray-300 px-4 py-2">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembelians as $pembelian)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $pembelian->no_pembelian }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $pembelian->supplier->nama_supplier ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $pembelian->tgl_pembelian ? $pembelian->tgl_pembelian->format('d M Y') : '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <span class="px-2 py-1 rounded text-sm font-semibold
                        @if($pembelian->status == 'pending') bg-yellow-200 text-yellow-800
                        @elseif($pembelian->status == 'diterima') bg-blue-200 text-blue-800
                        @elseif($pembelian->status == 'selesai') bg-green-200 text-green-800
                        @endif
                    ">{{ ucfirst($pembelian->status) }}</span>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-right">Rp{{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($pembelians->isNotEmpty())
    @php $pembelian = $pembelians->first(); @endphp
    @if($pembelian->pembelianBarangs->count() > 0)
        <h4 class="mt-4 mb-2 font-semibold text-gray-700">Detail Barang:</h4>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">No</th>
                    <th class="border border-gray-300 px-4 py-2">Barang</th>
                    <th class="border border-gray-300 px-4 py-2">Harga Beli</th>
                    <th class="border border-gray-300 px-4 py-2">Jumlah</th>
                    <th class="border border-gray-300 px-4 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian->pembelianBarangs as $index => $detail)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->barang->nama_barang ?? '-' }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $detail->jumlah }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endif
