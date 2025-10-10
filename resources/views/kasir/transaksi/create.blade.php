{{--
    Halaman pembuatan transaksi kasir dengan desain TailwindCSS.
    Halaman ini menggantikan tampilan AdminLTE/Bootstrap sebelumnya untuk
    memberikan pengalaman lebih modern dan konsisten untuk pengguna mobile.
    Pengguna dapat memilih produk, menentukan jumlah, menambahkan item ke
    transaksi, melihat daftar item, dan menghitung kembalian.  Semua
    interaksi tetap sama dengan halaman lama, hanya tampilan yang berubah.
--}}

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 p-2 mx-4 my-2 rounded">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 p-2 mx-4 my-2 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
    <!-- Kolom kiri: Pilih produk dan form tambah detail -->
    <div class="bg-white rounded shadow p-4">
        <!-- Form untuk memilih produk -->
        <form method="GET" class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Produk</label>
            <div class="flex space-x-2">
                <select name="produk_id" class="border border-gray-300 rounded flex-grow p-2">
                    <option value="">--{{ isset($p_detail) ? $p_detail->name : 'Nama Produk' }}--</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id }}">{{ $item->id.' - '.$item->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 btn btn-primary">Pilih</button>
            </div>
        </form>
        <!-- Form untuk menambahkan item ke transaksi -->
        <form action="/kasir/transaksi/detail/create" method="POST">
            @csrf
            <input type="hidden" name="transaksi_id" value="{{ $transaksi_id ?? '' }}">
            @if ($p_detail)
                <input type="hidden" name="produk_id" value="{{ $p_detail->id }}">
                <input type="hidden" name="produk_name" value="{{ $p_detail->name }}">
                <input type="hidden" id="hiddenSubtotal" name="subtotal" value="{{ $subtotal }}">
            @else
                <div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded">
                    Silakan pilih produk terlebih dahulu
                </div>
            @endif

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" class="border border-gray-300 rounded w-full p-2 bg-gray-100" value="{{ isset($p_detail) ? $p_detail->name : '' }}" disabled>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                <input type="text" class="border border-gray-300 rounded w-full p-2 bg-gray-100" value="{{ isset($p_detail) ? 'Rp. '.format_rupiah($p_detail->harga) : '' }}" disabled>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">QTY</label>
                <div class="flex items-center space-x-2">
                    <a href="?produk_id={{ request('produk_id') }}&act=min&qty={{ $qty }}" class="px-3 py-1 btn btn-primary">-</a>
                    <input id="qtyInput" type="number" name="qty" value="{{ $qty }}" class="border border-gray-300 rounded w-24 text-center p-2" min="1">
                    <a href="?produk_id={{ request('produk_id') }}&act=plus&qty={{ $qty }}" class="px-3 py-1 btn btn-primary">+</a>
                </div>
            </div>
            <div class="mb-4">
                <p class="font-semibold">Subtotal: Rp. <span id="subtotalDisplay">{{ format_rupiah($subtotal) }}</span></p>
            </div>
            <div class="flex space-x-2">
                <!-- Button to cancel (delete) the transaction. Redirects to cancel route -->
                <a href="{{ route('kasir.transaksi.cancel', ['id' => $transaksi_id ?? Request::segment(3)]) }}" class="px-4 py-2 btn btn-danger">Kembali</a>
                <button type="submit" class="px-4 py-2 btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
    <!-- Kolom kanan: daftar detail transaksi dan tindakan -->
    <div class="bg-white rounded shadow p-4 mt-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 text-left font-medium text-gray-600 uppercase">No</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-600 uppercase">Nama Produk</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-600 uppercase">Qty</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-600 uppercase">Subtotal</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-600 uppercase">#</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($transaksi_detail as $index => $item)
                <tr>
                    <td class="px-3 py-2">{{ $index + 1 }}</td>
                    <td class="px-3 py-2">{{ $item->produk_name }}</td>
                    <td class="px-3 py-2">{{ $item->qty }}</td>
                    <td class="px-3 py-2">Rp. {{ format_rupiah($item->subtotal) }}</td>
                    <td class="px-3 py-2">
                        <a href="/kasir/transaksi/detail/delete?id={{ $item->id }}" class="text-red-600 hover:text-red-800">x</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 flex space-x-2">
            <!-- Complete button passes the amount paid via query string for validation -->
            <a href="{{ route('kasir.transaksi.detail.done', ['id' => Request::segment(3)]) }}?bayar={{ $dibayarkan ?? 0 }}" class="px-4 py-2 btn btn-success">Selesai</a>
        </div>
    </div>
</div>

@if (isset($p_detail))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const qtyInput = document.getElementById('qtyInput');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const hiddenSubtotal = document.getElementById('hiddenSubtotal');
    // Price of the selected product (server-rendered)
    const price = {{ $p_detail->harga ?? 0 }};
    // Helper to format numbers as rupiah with thousand separators
    function formatRupiah(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    // Update subtotal when quantity changes
    function updateSubtotal() {
        let qty = parseInt(qtyInput.value);
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            qtyInput.value = qty;
        }
        const subtotal = qty * price;
        subtotalDisplay.textContent = formatRupiah(subtotal);
        hiddenSubtotal.value = subtotal;
    }
    if (qtyInput) {
        qtyInput.addEventListener('input', updateSubtotal);
    }
});
</script>
@endif

<!-- Seksi pembayaran: total, dibayarkan dan kembalian -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
    <div class="bg-white rounded shadow p-4">
        <form method="GET">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Belanja</label>
                <input type="text" value="{{ 'Rp. '.format_rupiah($transaksi->total) }}" name="total_belanja" class="border border-gray-300 rounded w-full p-2 bg-gray-100" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dibayarkan</label>
                <input type="number" name="dibayarkan" value="{{ $dibayarkan ?? '' }}" class="border border-gray-300 rounded w-full p-2" placeholder="Masukkan jumlah uang bayar">
            </div>
            <button type="submit" class="w-full px-4 py-2 btn btn-primary">Hitung</button>
        </form>
        <hr class="my-4">
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Uang Kembalian</label>
            <input type="text" value="{{ 'Rp. '.format_rupiah($kembalian) }}" class="border border-gray-300 rounded w-full p-2 bg-gray-100" readonly>
        </div>
    </div>
</div>
