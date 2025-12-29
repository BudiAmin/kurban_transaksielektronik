<x-app-layout>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Dashboard User - Manajemen Kurban</title>
        <meta name="description" content="Dashboard pengguna untuk pendaftaran dan monitoring kurban">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        {{-- css custome --}}
        <link rel="stylesheet" href="{{ asset('css/user.css') }}">


    </head>

    <body>
        <main class="dashboard-container">
            <h1 class="section-title">Dashboard Pengguna</h1>
            <p class="muted" style="margin-bottom: 1.5rem;">Halaman ini menampilkan data kurban yang terhubung ke
                database.</p>

            <div class="dashboard-grid">
                <aside>
                    {{-- Form Tambah Peserta --}}
                    @if ($isOpen)
                        <form method="POST" action="{{ route('peserta.order.store') }}" enctype="multipart/form-data"
                            class="card stack" id="orderForm" data-old-tipe="{{ old('tipe_pendaftaran', '') }}"
                            data-old-hewan="{{ old('ketersediaan_hewan_id', '') }}"
                            data-old-berat="{{ old('berat_hewan', '') }}"
                            data-old-daging="{{ old('perkiraan_daging', '') }}"
                            data-old-harga="{{ old('total_harga', '') }}" data-old-jenis="{{ old('jenis_hewan', '') }}">
                            @csrf

                            {{-- ============================= --}}
                            {{-- HIDDEN FIELDS (FIX) --}}
                            {{-- ============================= --}}
                            <input type="hidden" name="berat_hewan" id="berat_hewan_hidden"
                                value="{{ old('berat_hewan') }}">
                            <input type="hidden" name="perkiraan_daging" id="perkiraan_daging_hidden"
                                value="{{ old('perkiraan_daging') }}">
                            <input type="hidden" name="total_harga" id="total_harga_hidden"
                                value="{{ old('total_harga') }}">
                            <input type="hidden" name="jenis_hewan" id="jenis_hewan_hidden"
                                value="{{ old('jenis_hewan') }}">

                            {{-- ============================= --}}
                            {{-- TIPE PENDAFTARAN --}}
                            {{-- ============================= --}}
                            <div class="form-group">
                                <label for="tipe_pendaftaran">Tipe Pendaftaran *</label>
                                <select id="tipe_pendaftaran" name="tipe_pendaftaran" class="input" required>
                                    <option value="">Pilih</option>
                                    <option value="transfer"
                                        {{ old('tipe_pendaftaran') == 'transfer' ? 'selected' : '' }}>
                                        Transfer Uang
                                    </option>
                                    <option value="kirim langsung"
                                        {{ old('tipe_pendaftaran') == 'kirim langsung' ? 'selected' : '' }}>
                                        Kirim Hewan ke DKM
                                    </option>
                                </select>
                                @error('tipe_pendaftaran')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- ============================= --}}
                            {{-- TRANSFER: PILIH HEWAN --}}
                            {{-- ============================= --}}
                            <div class="form-group" id="transfer-group" style="display:none">
                                <label>Pilih Hewan *</label>
                                <select id="ketersediaan_hewan_id" name="ketersediaan_hewan_id" class="input">
                                    <option value="">Pilih Hewan</option>
                                    @foreach ($ketersediaan_hewan as $hewan)
                                        @if ($hewan->jumlah > 0)
                                            <option value="{{ $hewan->id }}" data-jenis="{{ $hewan->jenis_hewan }}"
                                                data-berat="{{ $hewan->bobot }}" data-harga="{{ $hewan->harga }}"
                                                {{ old('ketersediaan_hewan_id') == $hewan->id ? 'selected' : '' }}>
                                                {{ $hewan->jenis_hewan }} ({{ $hewan->bobot }} kg) - Rp
                                                {{ number_format($hewan->harga, 0, ',', '.') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('ketersediaan_hewan_id')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- ============================= --}}
                            {{-- KIRIM LANGSUNG --}}
                            {{-- ============================= --}}
                            <div class="form-group" id="kirim-group" style="display:none">
                                <label>Jenis Hewan *</label>
                                <input type="text" name="jenis_hewan_input" id="jenis_hewan_input" class="input"
                                    placeholder="Contoh: Sapi" value="{{ old('jenis_hewan_input') }}">
                                @error('jenis_hewan')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" id="berat-kirim-group" style="display:none">
                                <label>Perkiraan Bobot (kg) *</label>
                                <input type="number" step="0.1" min="1" id="berat_kirim_input"
                                    class="input" value="{{ old('berat_kirim_input') }}">
                                @error('berat_hewan')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- ============================= --}}
                            {{-- JUMLAH HEWAN --}}
                            {{-- ============================= --}}
                            <div class="form-group">
                                <label>Jumlah Hewan *</label>
                                <input type="number" name="total_hewan" id="total_hewan" class="input" min="1"
                                    max="1" value="{{ old('total_hewan', 1) }}" required readonly>
                                @error('total_hewan')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- ============================= --}}
                            {{-- INFO OTOMATIS --}}
                            {{-- ============================= --}}
                            <div class="form-group" style="display:none">
                                <label>Berat Total</label>
                                <input type="text" id="berat_total_display" class="input" readonly>
                            </div>

                            <div class="form-group">
                                <label>Perkiraan Daging **</label>
                                <input type="text" id="perkiraan_daging_display" class="input" readonly>
                            </div>

                            <div class="form-group">
                                <label>Total Harga</label>
                                <input type="text" id="total_harga_display" class="input" readonly>
                            </div>

                            {{-- ============================= --}}
                            {{-- BUKTI PEMBAYARAN --}}
                            {{-- ============================= --}}
                            <div class="form-group" id="bukti-group" style="display:none">
                                <label>Bukti Pembayaran *</label>
                                <input type="file" name="bukti_pembayaran" class="input"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf">
                                @error('bukti_pembayaran')
                                    <small class="error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- ============================= --}}
                            {{-- SUBMIT --}}
                            {{-- ============================= --}}
                            <div class="actions">
                                <button class="btn btn-gold" type="submit" id="submitBtn">
                                    Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="card form-card" style="padding:1.5rem; margin-bottom:1.25rem;">
                            <h1 class="form-title" style="color:red;">Pendaftaran Belum Dibuka / Sudah Ditutup!</h1>
                            <br>
                            <p class="muted">
                                Pendaftaran dibuka dari
                                <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}</strong>
                                hingga
                                <strong>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}</strong>
                            </p>
                        </div>
                    @endif

                    {{-- Catatan --}}
                    <div class="card form-card">
                        <h3 class="card-title">Catatan!</h3>
                        <p class="muted">
                            <strong>*</strong> Tipe pendaftaran<strong> Transfer Uang</strong> adalah membeli hewan kurban melalui DKM
                        </p>
                        <br>
                        <p class="muted">
                            <strong>*</strong> Tipe pendaftaran<strong> Kirim Hewan Ke DKM</strong> adalah membawa hewan kurban langsung ke masjid sebelum waktu penyembelihan
                        </p>
                        <br>
                        <p class="muted">
                            <strong>*</strong> Jumlah hewan sekali pendaftaran dibatasi hanya<strong> 1 (satu)</strong>, lakukan pendaftaran ulang untuk hewan tambahan
                        </p>
                        <br>
                        <p class="muted">
                            <strong>**</strong> Perhitungan perkiraan berat daging bersih diperoleh dari
                            <a href="https://www.holycowsteak.com/blogs/story/cara-pembagian-daging-kurban"
                                target="_blank" class="text-blue-600 underline hover:text-blue-800">
                                sini
                            </a>
                        </p>

                        {{-- <div class="stack" style="margin-top: 1rem;"> --}}
                            {{-- @forelse ($penerimaKurbans as $penerima)
                                <div class="data-row">
                                    <div>
                                        <strong>{{ $penerima->Nama }}</strong>
                                        <p class="muted" style="margin-top: 0.25rem;">{{ $penerima->Tempat_Tinggal }}</p>
                                    </div>
                                </div>
                            @empty --}}
                            {{-- <p class="muted">Belum ada daftar penerima kurban yang terverifikasi.</p> --}}
                            {{-- @endforelse --}}
                        {{-- </div> --}}
                    </div>
                </aside>

                <section>
                    {{-- Jadwal Pelaksanaan Kurban --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Jadwal Pelaksanaan Kurban</h3>
                            <p class="muted">Informasi tanggal, waktu, dan lokasi penyembelihan.</p>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="table-responsive desktop-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal Pendaftaran</th>
                                        <th>Tanggal Penutupan</th>
                                        <th>Lokasi</th>
                                        <th>Jadwal Penyembelihan</th>
                                        <th>Ketua Pelaksana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelaksanaanKurban as $pelaksanaan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}
                                            </td>
                                            <td>{{ $pelaksanaan->Lokasi }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)->format('d M Y') }}
                                            </td>
                                            <td>{{ $pelaksanaan->Ketuplak }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Jadwal penyembelihan
                                                belum ditetapkan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="mobile-card-view">
                            <div class="data-grid">
                                @forelse ($pelaksanaanKurban as $pelaksanaan)
                                    <div class="data-card">
                                        <div class="data-row">
                                            <span class="data-label">Tanggal Pendaftaran</span>
                                            <span
                                                class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Pendaftaran)->format('d M Y') }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Tanggal Penutupan</span>
                                            <span
                                                class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Tanggal_Penutupan)->format('d M Y') }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Lokasi</span>
                                            <span class="data-value">{{ $pelaksanaan->Lokasi }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Jadwal Penyembelihan</span>
                                            <span
                                                class="data-value">{{ \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)->format('d M Y') }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Ketua Pelaksana</span>
                                            <span class="data-value">{{ $pelaksanaan->Ketuplak }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="data-card text-center text-muted">Jadwal penyembelihan belum
                                        ditetapkan.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pembayaran</h3>
                            <p class="muted">Status pembayaran dan informasi transaksi.</p>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="table-responsive desktop-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Donatur</th>
                                        <th>Jenis Hewan</th>
                                        <th>Total Hewan</th>
                                        <th>Total Harga</th>
                                        <th>Tipe Pendaftaran</th>
                                        <th>Status</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Alasan Penolakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detailPembayaran as $row)
                                        <tr>
                                            <td>{{ $row->user->name ?? '-' }}</td>
                                            <td>{{ $row->jenis_hewan ?? '-' }}</td>
                                            <td>{{ $row->total_hewan ?? '-' }}</td>
                                            <td>Rp. {{ number_format($row->total_harga, 0, ',', '.') ?? '-' }}</td>
                                            <td>{{ $row->tipe_pendaftaran ?? '-' }}</td>
                                            <td>
                                                <span
                                                    class="status-badge status-{{ strtolower($row->status ?? 'menunggu persetujuan') }}">
                                                    {{ $row->status ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($row->bukti_pembayaran)
                                                    <img src="{{ asset('storage/' . $row->bukti_pembayaran) }}"
                                                        alt="Bukti Pembayaran" style="max-width: 80px;">
                                                @else
                                                    <span class="text-muted">Belum ada foto</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($row->alasan_penolakan)
                                                    <div class="text-danger" style="font-size: 0.75rem;">
                                                        {{ $row->alasan_penolakan }}</div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada riwayat
                                                transaksi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="mobile-card-view">
                            <div class="data-grid">
                                @forelse ($detailPembayaran as $row)
                                    <div class="data-card">
                                        <div class="data-row">
                                            <span class="data-label">Nama Donatur</span>
                                            <span class="data-value">{{ $row->user->name ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Jenis Hewan</span>
                                            <span class="data-value">{{ $row->jenis_hewan ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Total Hewan</span>
                                            <span class="data-value">{{ $row->total_hewan ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Total Harga</span>
                                            <span class="data-value">Rp.
                                                {{ number_format($row->total_harga, 0, ',', '.') ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Tipe Pendaftaran</span>
                                            <span class="data-value">{{ $row->tipe_pendaftaran ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Status</span>
                                            <span class="data-value">
                                                <span
                                                    class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                                    {{ $row->status ?? '-' }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Bukti Pembayaran</span>
                                            <span class="data-value">
                                                @if ($row->bukti_pembayaran)
                                                    <img src="{{ asset('storage/' . $row->bukti_pembayaran) }}"
                                                        alt="Bukti Pembayaran">
                                                @else
                                                    <span class="text-muted">Belum ada foto</span>
                                                @endif
                                            </span>
                                        </div>
                                        @if ($row->alasan_penolakan)
                                            <div class="data-row">
                                                <span class="data-label">Alasan Penolakan</span>
                                                <span
                                                    class="data-value text-danger">{{ $row->alasan_penolakan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="data-card text-center text-muted">Tidak ada riwayat transaksi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Dokumentasi Penyembelihan --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dokumentasi Penyembelihan</h3>
                            <p class="muted">Dokumentasi penyembelihan hewan kurban.</p>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="table-responsive desktop-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Donatur</th>
                                        <th>Jenis Hewan</th>
                                        <th>Status Hewan</th>
                                        <th>Waktu Penyembelihan</th>
                                        <th>Berat Hewan</th>
                                        <th>Perkiraan Daging</th>
                                        <th>Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penyembelihan as $row)
                                        <tr>
                                            <td>{{ $row->order->user->name ?? '-' }}</td>
                                            <td>{{ $row->order->jenis_hewan ?? '-' }}</td>
                                            <td>
                                                <span
                                                    class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                                    {{ $row->status ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($row->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}
                                            </td>
                                            <td>{{ $row->order->berat_hewan ? number_format($row->order->berat_hewan, 1) . ' kg' : '-' }}</td>
                                            <td>{{ $row->order->perkiraan_daging ? number_format($row->order->perkiraan_daging, 1) . ' kg' : '-' }}
                                            </td>
                                            <td>
                                                @if ($row->dokumentasi_penyembelihan)
                                                    <img src="{{ asset('storage/' . $row->dokumentasi_penyembelihan) }}"
                                                        alt="Foto penyembelihan" style="max-width: 80px;">
                                                @else
                                                    <span class="text-muted">Belum ada foto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data
                                                penyembelihan untuk hewan Anda.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="mobile-card-view">
                            <div class="data-grid">
                                @forelse ($penyembelihan as $row)
                                    <div class="data-card">
                                        <div class="data-row">
                                            <span class="data-label">Nama Donatur</span>
                                            <span class="data-value">{{ $row->order->user->name ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Jenis Hewan</span>
                                            <span class="data-value">{{ $row->order->jenis_hewan ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Status Hewan</span>
                                            <span class="data-value">
                                                <span
                                                    class="status-badge status-{{ strtolower($row->status ?? 'pending') }}">
                                                    {{ $row->status ?? '-' }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Waktu Penyembelihan</span>
                                            <span
                                                class="data-value">{{ \Carbon\Carbon::parse($row->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Berat Hewan</span>
                                            <span
                                                class="data-value">{{ $row->order->berat_hewan ? number_format($row->order->berat_hewan, 1) . ' kg' : '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Perkiraan Daging</span>
                                            <span
                                                class="data-value">{{ $row->order->perkiraan_daging ? number_format($row->order->perkiraan_daging, 1) . ' kg' : '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Dokumentasi</span>
                                            <span class="data-value">
                                                @if ($row->dokumentasi_penyembelihan)
                                                    <img src="{{ asset('storage/' . $row->dokumentasi_penyembelihan) }}"
                                                        alt="Foto penyembelihan">
                                                @else
                                                    <span class="text-muted">Belum ada foto</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="data-card text-center text-muted">Tidak ada data penyembelihan untuk
                                        hewan Anda.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Distribusi Daging --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Distribusi Daging</h3>
                            <p class="muted">Catatan distribusi daging kurban yang telah dilakukan.</p>
                        </div>

                        {{-- Desktop Table --}}
                        <div class="table-responsive desktop-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Hewan Asal</th>
                                        <th>Penerima</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse ($distribusiDaging as $distribusi)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($distribusi->Tanggal_Distribusi)->format('d M Y') }}</td>
                                            <td>{{ $distribusi->hewanKurban->Jenis_Hewan ?? 'N/A' }}</td>
                                            <td>{{ $distribusi->penerimaKurban->Nama ?? $distribusi->Penerima }}</td>
                                            <td>
                                                <span class="status-badge status-{{ strtolower($distribusi->Status_Distribusi) }}">
                                                    {{ $distribusi->Status_Distribusi }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty --}}
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada riwayat distribusi
                                            daging.</td>
                                    </tr>
                                    {{-- @endforelse --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="mobile-card-view">
                            <div class="data-grid">
                                {{-- @forelse ($distribusiDaging as $distribusi)
                                    <div class="data-card">
                                        <div class="data-row">
                                            <span class="data-label">Tanggal</span>
                                            <span class="data-value">{{ \Carbon\Carbon::parse($distribusi->Tanggal_Distribusi)->format('d M Y') }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Hewan Asal</span>
                                            <span class="data-value">{{ $distribusi->hewanKurban->Jenis_Hewan ?? 'N/A' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Penerima</span>
                                            <span class="data-value">{{ $distribusi->penerimaKurban->Nama ?? $distribusi->Penerima }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Status</span>
                                            <span class="data-value">
                                                <span class="status-badge status-{{ strtolower($distribusi->Status_Distribusi) }}">
                                                    {{ $distribusi->Status_Distribusi }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                @empty --}}
                                <div class="data-card text-center text-muted">Belum ada riwayat distribusi daging.
                                </div>
                                {{-- @endforelse --}}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <script src="{{ asset('js/script.js') }}"></script>

        <script src="{{ asset('js/user.js') }}"></script>

        <script>
            // Responsive table toggler for additional control
            function toggleView() {
                const isMobile = window.innerWidth <= 768;
                document.querySelectorAll('.desktop-table').forEach(table => {
                    table.style.display = isMobile ? 'none' : 'block';
                });
                document.querySelectorAll('.mobile-card-view').forEach(card => {
                    card.style.display = isMobile ? 'block' : 'none';
                });

                // Adjust table width on resize
                if (!isMobile) {
                    document.querySelectorAll('.desktop-table table').forEach(table => {
                        table.style.width = '100%';
                    });
                }
            }

            // Initial call
            document.addEventListener('DOMContentLoaded', toggleView);

            // Listen for resize events
            window.addEventListener('resize', toggleView);

            // Fix for images in tables
            document.querySelectorAll('.desktop-table img').forEach(img => {
                img.style.maxWidth = '80px';
                img.style.height = 'auto';
            });
        </script>
    </body>

    </html>
</x-app-layout>
