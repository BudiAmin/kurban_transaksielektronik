
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const tipe = document.getElementById('tipe_pendaftaran');
    const transferGroup = document.getElementById('transfer-group');
    const kirimGroup = document.getElementById('kirim-group');
    const beratKirimGroup = document.getElementById('berat-kirim-group');
    const buktiGroup = document.getElementById('bukti-group');
    const form = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitBtn');

    // Input elements
    const hewanSelect = document.getElementById('ketersediaan_hewan_id');
    const jumlahInput = document.getElementById('total_hewan');
    const jenisHewanInput = document.getElementById('jenis_hewan_input');
    const beratKirimInput = document.getElementById('berat_kirim_input');

    // Hidden fields (akan dikirim ke server)
    const beratHidden = document.getElementById('berat_hewan_hidden');
    const dagingHidden = document.getElementById('perkiraan_daging_hidden');
    const hargaHidden = document.getElementById('total_harga_hidden');
    const jenisHidden = document.getElementById('jenis_hewan_hidden');

    // Display fields (hanya untuk tampilan)
    const beratDisplay = document.getElementById('berat_total_display');
    const dagingDisplay = document.getElementById('perkiraan_daging_display');
    const hargaDisplay = document.getElementById('total_harga_display');

    // Data konversi berdasarkan jenis hewan (sesuai sumber)
    const konversiHewan = {
        'sapi': {
            karkasPersen: 0.50, // 50% rata-rata (45-55%)
            dagingDariKarkas: 0.75, // 75% dari karkas
            kepalaPersen: 0.04, // 4% dari berat hidup
            kakiPerEkor: 4.5, // 4.5 kg per ekor (total 4 kaki)
            ekorPersen: 0.007, // 0.7% dari berat hidup
            jeroanPersen: 0.10 // 10% dari berat karkas
        },
        'kambing': {
            // Data untuk kambing (disesuaikan)
            karkasPersen: 0.40, // 40% dari berat hidup
            dagingDariKarkas: 0.70, // 70% dari karkas
            kepalaPersen: 0.03, // 3% dari berat hidup
            kakiPerEkor: 1.2, // 1.2 kg per ekor
            ekorPersen: 0.004, // 0.4% dari berat hidup
            jeroanPersen: 0.08 // 8% dari berat karkas
        },
        'domba': {
            // Data untuk domba (disesuaikan)
            karkasPersen: 0.42, // 42% dari berat hidup
            dagingDariKarkas: 0.72, // 72% dari karkas
            kepalaPersen: 0.03, // 3% dari berat hidup
            kakiPerEkor: 1.0, // 1.0 kg per ekor
            ekorPersen: 0.005, // 0.5% dari berat hidup
            jeroanPersen: 0.09 // 9% dari berat karkas
        }
    };

    // Fungsi untuk menghitung perkiraan daging berdasarkan jenis hewan
    function hitungPerkiraanDaging(beratPerEkor, jumlahHewan, jenisHewan) {
        const totalBeratHidup = beratPerEkor * jumlahHewan;

        // Ambil data konversi berdasarkan jenis hewan
        const konversi = konversiHewan[jenisHewan.toLowerCase()] || konversiHewan['sapi'];

        // 1. Hitung berat karkas
        const beratKarkas = totalBeratHidup * konversi.karkasPersen;

        // 2. Hitung daging murni (75% dari berat karkas untuk sapi)
        const dagingMurni = beratKarkas * konversi.dagingDariKarkas;

        // 3. Hitung kepala (4% dari berat hidup untuk sapi)
        const kepalaTotal = totalBeratHidup * konversi.kepalaPersen;

        // 4. Hitung kaki (4.5 kg × jumlah hewan untuk sapi)
        const kakiTotal = konversi.kakiPerEkor * jumlahHewan;

        // 5. Hitung ekor (0.7% dari berat hidup untuk sapi)
        const ekorTotal = totalBeratHidup * konversi.ekorPersen;

        // 6. Hitung jeroan (10% dari berat karkas untuk sapi)
        const jeroanTotal = beratKarkas * konversi.jeroanPersen;

        // 7. TOTAL daging yang bisa didistribusikan
        const totalDagingDistribusi = dagingMurni + kepalaTotal + kakiTotal + ekorTotal + jeroanTotal;

        return {
            totalBeratHidup: totalBeratHidup,
            beratKarkas: beratKarkas,
            dagingMurni: dagingMurni,
            kepalaTotal: kepalaTotal,
            kakiTotal: kakiTotal,
            ekorTotal: ekorTotal,
            jeroanTotal: jeroanTotal,
            totalDagingDistribusi: totalDagingDistribusi
        };
    }

    // Fungsi untuk mendapatkan jenis hewan berdasarkan tipe
    function getJenisHewan() {
        let jenisHewan = '';

        if (tipe.value === 'transfer' && hewanSelect.value) {
            const selectedOption = hewanSelect.options[hewanSelect.selectedIndex];
            jenisHewan = selectedOption.dataset.jenis || 'sapi'; // Default ke sapi
        } else if (tipe.value === 'kirim langsung') {
            jenisHewan = jenisHewanInput.value || 'sapi';
        }

        return jenisHewan.toLowerCase();
    }

    // Fungsi untuk menghitung semua nilai
    function hitungDanUpdate() {
        let beratPerEkor = 0;
        let hargaPerEkor = 0;
        let jenisHewan = '';

        // Reset nilai
        beratHidden.value = '';
        dagingHidden.value = '';
        hargaHidden.value = '';
        jenisHidden.value = '';

        // Ambil data berdasarkan tipe
        if (tipe.value === 'transfer' && hewanSelect.value) {
            const selectedOption = hewanSelect.options[hewanSelect.selectedIndex];
            beratPerEkor = parseFloat(selectedOption.dataset.berat) || 0;
            hargaPerEkor = parseFloat(selectedOption.dataset.harga) || 0;
            jenisHewan = selectedOption.dataset.jenis || 'sapi';
        } else if (tipe.value === 'kirim langsung') {
            beratPerEkor = parseFloat(beratKirimInput.value) || 0;
            hargaPerEkor = 0; // Tidak ada harga untuk kirim langsung
            jenisHewan = jenisHewanInput.value || 'sapi';
        }

        // Jika berat per ekor tidak valid, reset semua
        if (beratPerEkor <= 0) {
            beratDisplay.value = '0 kg';
            dagingDisplay.value = '0 kg';
            hargaDisplay.value = tipe.value === 'transfer' ? 'Rp 0' :
                'Tidak ada biaya (Kirim hewan langsung)';
            return;
        }

        // Hitung total hewan
        const totalHewan = parseInt(jumlahInput.value) || 1;
        const jenisHewanLower = jenisHewan.toLowerCase();

        // Hitung menggunakan rumus baru berdasarkan sumber
        const hasilPerhitungan = hitungPerkiraanDaging(beratPerEkor, totalHewan, jenisHewanLower);

        // Untuk perkiraan daging, kita pakai total daging distribusi
        const perkiraanDaging = hasilPerhitungan.totalDagingDistribusi;
        const totalBerat = hasilPerhitungan.totalBeratHidup;
        const totalHarga = hargaPerEkor * totalHewan;

        // Update HIDDEN fields (yang dikirim ke server)
        beratHidden.value = beratPerEkor;
        dagingHidden.value = perkiraanDaging.toFixed(2);
        hargaHidden.value = totalHarga;
        jenisHidden.value = jenisHewanLower;

        // Update DISPLAY fields (hanya untuk tampilan)
        beratDisplay.value = totalBerat.toFixed(1) + ' kg';
        dagingDisplay.value = perkiraanDaging.toFixed(1) + ' kg';

        if (tipe.value === 'transfer') {
            hargaDisplay.value = 'Rp ' + totalHarga.toLocaleString('id-ID');
        } else {
            hargaDisplay.value = 'Tidak ada biaya (Kirim hewan langsung)';
        }

        // Debug log (opsional)
        console.log('Hitung - Jenis Hewan:', jenisHewanLower);
        console.log('Hitung - Detail:', {
            beratPerEkor: beratPerEkor,
            totalHewan: totalHewan,
            totalBeratHidup: hasilPerhitungan.totalBeratHidup,
            beratKarkas: hasilPerhitungan.beratKarkas,
            dagingMurni: hasilPerhitungan.dagingMurni,
            kepala: hasilPerhitungan.kepalaTotal,
            kaki: hasilPerhitungan.kakiTotal,
            ekor: hasilPerhitungan.ekorTotal,
            jeroan: hasilPerhitungan.jeroanTotal,
            totalDaging: perkiraanDaging
        });
        console.log('Hitung - Hidden Values:', {
            berat: beratHidden.value,
            daging: dagingHidden.value,
            harga: hargaHidden.value,
            jenis: jenisHidden.value
        });
    }

    // Fungsi untuk toggle form visibility
    function toggleFormSections() {
        const isTransfer = tipe.value === 'transfer';
        const isKirim = tipe.value === 'kirim langsung';

        // Toggle visibility
        transferGroup.style.display = isTransfer ? 'block' : 'none';
        buktiGroup.style.display = isTransfer ? 'block' : 'none';
        kirimGroup.style.display = isKirim ? 'block' : 'none';
        beratKirimGroup.style.display = isKirim ? 'block' : 'none';

        // Toggle required attributes
        hewanSelect.required = isTransfer;
        jenisHewanInput.required = isKirim;
        beratKirimInput.required = isKirim;

        const buktiInput = document.querySelector('input[name="bukti_pembayaran"]');
        if (buktiInput) buktiInput.required = isTransfer;

        // Reset jika berganti tipe
        if (isTransfer) {
            jenisHewanInput.value = '';
            beratKirimInput.value = '';
        } else if (isKirim) {
            hewanSelect.value = '';
        }

        // Hitung ulang
        hitungDanUpdate();
    }

    // Event listener untuk form submit (debugging)
    form.addEventListener('submit', function (e) {
        console.log('=== FORM SUBMIT DATA ===');
        console.log('Tipe:', tipe.value);
        console.log('Hidden Fields:', {
            berat_hewan: beratHidden.value,
            perkiraan_daging: dagingHidden.value,
            total_harga: hargaHidden.value,
            jenis_hewan: jenisHidden.value
        });
        console.log('Form Data:');

        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        // Validasi sebelum submit
        if (tipe.value === 'transfer' && !hewanSelect.value) {
            e.preventDefault();
            alert('Pilih hewan untuk tipe transfer');
            return;
        }

        if (tipe.value === 'transfer' && (!beratHidden.value || !dagingHidden.value || !hargaHidden
            .value)) {
            e.preventDefault();
            alert('Data belum lengkap. Silakan tunggu perhitungan selesai.');
            return;
        }

        // Nonaktifkan button saat submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Menyimpan...';
    });

    // Event listeners untuk perubahan
    tipe.addEventListener('change', toggleFormSections);
    hewanSelect.addEventListener('change', hitungDanUpdate);
    jumlahInput.addEventListener('input', hitungDanUpdate);
    jenisHewanInput.addEventListener('input', hitungDanUpdate);
    beratKirimInput.addEventListener('input', hitungDanUpdate);

    // Inisialisasi saat load
    toggleFormSections();

    // Jika ada old input dari session, set nilai
    function setOldValues() {
        const form = document.getElementById('orderForm');
        if (!form) return;

        // Helper function untuk cek nilai
        const hasValue = (val) => val !== null && val !== undefined && val !== '';

        const oldTipe = form.dataset.oldTipe;
        const oldHewan = form.dataset.oldHewan;
        const oldBerat = form.dataset.oldBerat;
        const oldDaging = form.dataset.oldDaging;
        const oldHarga = form.dataset.oldHarga;
        const oldJenis = form.dataset.oldJenis;

        // Set nilai jika ada
        if (hasValue(oldTipe)) {
            tipe.value = oldTipe;
            toggleFormSections();
        }

        if (hasValue(oldHewan) && hewanSelect) {
            hewanSelect.value = oldHewan;
        }

        if (hasValue(oldBerat)) {
            if (beratHidden) beratHidden.value = oldBerat;
            if (beratDisplay) beratDisplay.value = oldBerat + ' kg';
        }

        if (hasValue(oldDaging)) {
            if (dagingHidden) dagingHidden.value = oldDaging;
            if (dagingDisplay) dagingDisplay.value = oldDaging + ' kg';
        }

        if (hasValue(oldHarga)) {
            if (hargaHidden) hargaHidden.value = oldHarga;
            if (hargaDisplay) {
                hargaDisplay.value = oldHarga > 0
                    ? 'Rp ' + Number(oldHarga).toLocaleString('id-ID')
                    : 'Tidak ada biaya (Kirim hewan langsung)';
            }
        }

        if (hasValue(oldJenis) && jenisHidden) {
            jenisHidden.value = oldJenis;
            if (jenisHewanInput && tipe.value === 'kirim langsung') {
                jenisHewanInput.value = oldJenis;
            }
        }

        // Jika ada nilai, hitung ulang
        if (hasValue(oldTipe) || hasValue(oldHewan) || hasValue(oldBerat)) {
            hitungDanUpdate();
        }
    }

    setOldValues();

    // Tambahan: Fungsi untuk test perhitungan (opsional, bisa dihapus)
    window.testPerhitungan = function () {
        const hasil = hitungPerkiraanDaging(300, 1, 'sapi');
        console.log('=== TEST PERHITUNGAN SAPI 300kg ===');
        console.log('Berat Karkas: ' + hasil.beratKarkas.toFixed(1) + ' kg');
        console.log('Daging Murni: ' + hasil.dagingMurni.toFixed(1) + ' kg');
        console.log('Kepala: ' + hasil.kepalaTotal.toFixed(1) + ' kg');
        console.log('Kaki: ' + hasil.kakiTotal.toFixed(1) + ' kg');
        console.log('Ekor: ' + hasil.ekorTotal.toFixed(1) + ' kg');
        console.log('Jeroan: ' + hasil.jeroanTotal.toFixed(1) + ' kg');
        console.log('TOTAL DAGING: ' + hasil.totalDagingDistribusi.toFixed(1) + ' kg');
        alert('Test perhitungan untuk sapi 300kg: ' + hasil.totalDagingDistribusi.toFixed(1) +
            ' kg (seharusnya ≈146.1 kg)');
    };
});