<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">No Faktur Beli</th>
            <th class="border border-gray-300 px-4 py-2">Supplier</th>
            <th class="border border-gray-300 px-4 py-2">Barang</th>
            <th class="border border-gray-300 px-4 py-2 text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembelian as $item)
        <tr>
            <td class="border border-gray-300 px-4 py-2">{{ $item->no_faktur_beli }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $item->supplier }}</td>
            <td class="border border-gray-300 px-4 py-2">
                @foreach($item->pembelianBarang as $detail)
                    - {{ $detail->barang->nama_barang }} ({{ $detail->jml }}) <br>
                @endforeach
            </td>
            <td class="border border-gray-300 px-4 py-2 text-right">
                Rp{{ number_format($item->total_biaya, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>