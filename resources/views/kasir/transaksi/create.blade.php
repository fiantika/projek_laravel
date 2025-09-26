{{--
    Halaman pembuatan transaksi kasir dengan desain TailwindCSS.
    Halaman ini menggantikan tampilan AdminLTE/Bootstrap sebelumnya untuk
    memberikan pengalaman lebih modern dan konsisten untuk pengguna mobile.
    Pengguna dapat memilih produk, menentukan jumlah, menambahkan item ke
    transaksi, melihat daftar item, dan menghitung kembalian.  Semua
    interaksi tetap sama dengan halaman lama, hanya tampilan yang berubah.
--}}

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
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Pilih</button>
            </div>
        </form>
        <!-- Form untuk menambahkan item ke transaksi -->
        <form action="/kasir/transaksi/detail/create" method="POST">
            @csrf
            <input type="hidden" name="transaksi_id" value="{{ $transaksi_id ?? '' }}">
            @if ($p_detail)
                <input type="hidden" name="produk_id" value="{{ $p_detail->id }}">
                <input type="hidden" name="produk_name" value="{{ $p_detail->name }}">
                <input type="hidden" name="subtotal" value="{{ $subtotal }}">
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
                <input type="text" class="border border-gray-300 rounded w-full p-2 bg-gray-100" value="{{ isset($p_detail) ? $p_detail->harga : '' }}" disabled>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">QTY</label>
                <div class="flex items-center space-x-2">
                    <a href="?produk_id={{ request('produk_id') }}&act=min&qty={{ $qty }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded">-</a>
                    <input type="number" name="qty" value="{{ $qty }}" class="border border-gray-300 rounded w-24 text-center p-2">
                    <a href="?produk_id={{ request('produk_id') }}&act=plus&qty={{ $qty }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded">+</a>
                </div>
            </div>
            <div class="mb-4">
                <p class="font-semibold">Subtotal: Rp. {{ format_rupiah($subtotal) }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="/kasir/transaksi" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">Kembali</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Tambah</button>
            </div>
        </form>
    </div>
    <!-- Kolom kanan: daftar detail transaksi dan tindakan -->
    <div class="bg-white rounded shadow p-4">
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
            <a href="/kasir/transaksi/detail/selesai/{{ Request::segment(3) }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">Selesai</a>
            <a href="#" class="px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded">Pending</a>
        </div>
    </div>
</div>

<!-- Seksi pembayaran: total, dibayarkan dan kembalian -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
    <div class="bg-white rounded shadow p-4">
        <form method="GET">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Belanja</label>
                <input type="number" value="{{ $transaksi->total }}" name="total_belanja" class="border border-gray-300 rounded w-full p-2" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dibayarkan</label>
                <input type="number" name="dibayarkan" value="{{ request('dibayarkan') }}" class="border border-gray-300 rounded w-full p-2">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Hitung</button>
        </form>
        <hr class="my-4">
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Uang Kembalian</label>
            <input type="text" value="{{ format_rupiah($kembalian) }}" class="border border-gray-300 rounded w-full p-2 bg-gray-100" readonly>
        </div>
    </div>
</div>