/**
 * rekap-form.js
 * Tabel dinamis input rekap sampah + fetch harga otomatis dari API.
 * Vanilla JS, tanpa jQuery atau library eksternal.
 */

(function () {
    'use strict';

    let barisIndex = 0;

    /** Data dari PHP (window.NAMA_SAMPAH_LIST); fallback jika skrip inline gagal */
    function getNamaList() {
        if (typeof window !== 'undefined' && Array.isArray(window.NAMA_SAMPAH_LIST)) {
            return window.NAMA_SAMPAH_LIST;
        }
        return [];
    }

    function getBaseUrl() {
        if (typeof window !== 'undefined' && typeof window.BASE_URL === 'string') {
            return window.BASE_URL;
        }
        return '';
    }

    // ──────────────────────────────────────────
    // Bangun <select> nama sampah dari data PHP
    // ──────────────────────────────────────────
    function buatSelectNama(selectedId = 0) {
        let html = '<select name="id_nama_sampah[]" class="form-control rekap-select-nama" required>';
        html += '<option value="">-- Pilih Item --</option>';

        // Kelompokkan per jenis
        const kelompok = {};
        getNamaList().forEach(item => {
            if (!kelompok[item.jenis]) kelompok[item.jenis] = [];
            kelompok[item.jenis].push(item);
        });

        Object.keys(kelompok).forEach(jenis => {
            html += `<optgroup label="${escHtml(jenis)}">`;
            kelompok[jenis].forEach(item => {
                const sel = item.id === selectedId ? 'selected' : '';
                html += `<option value="${item.id}" data-harga="${item.harga}" ${sel}>${escHtml(item.nama)}</option>`;
            });
            html += '</optgroup>';
        });

        html += '</select>';
        return html;
    }

    // ──────────────────────────────────────────
    // Tambah baris baru ke tabel
    // ──────────────────────────────────────────
    function tambahBaris() {
        const tbody = document.getElementById('tbody-rekap');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.dataset.idx = barisIndex;
        tr.innerHTML = `
            <td>${buatSelectNama()}</td>
            <td>
                <input type="number" name="berat[]" class="form-control berat-input"
                       min="0.01" step="0.01" placeholder="0.00">
            </td>
            <td>
                <input type="number" name="harga_satuan[]" class="form-control harga-input font-mono"
                       min="0" step="50" placeholder="0" readonly
                       style="background:var(--color-bg);">
            </td>
            <td>
                <input type="number" name="subtotal[]" class="form-control subtotal-input font-mono"
                       min="0" placeholder="0" readonly
                       style="background:var(--color-bg);font-weight:600;">
            </td>
            <td>
                <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)" title="Hapus baris">✕</button>
            </td>
        `;

        tbody.appendChild(tr);
        barisIndex++;

        // Fokus ke select di baris baru
        tr.querySelector('select').focus();
    }

    // ──────────────────────────────────────────
    // Hapus baris
    // ──────────────────────────────────────────
    function hapusBaris(btn) {
        const tr = btn.closest('tr');
        if (document.querySelectorAll('#tbody-rekap tr').length <= 1) {
            alert('Minimal harus ada satu baris item.');
            return;
        }
        tr.remove();
        hitungGrandTotal();
    }

    // ──────────────────────────────────────────
    // Fetch harga dari API saat select berubah
    // ──────────────────────────────────────────
    async function fetchHarga(selectEl) {
        const tr       = selectEl.closest('tr');
        const idNama   = parseInt(selectEl.value, 10);
        const hargaInp = tr.querySelector('.harga-input');
        const subInp   = tr.querySelector('.subtotal-input');

        if (!idNama) {
            hargaInp.value = '';
            subInp.value   = '';
            hitungGrandTotal();
            return;
        }

        // Coba ambil harga dari data-harga (cache di option)
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        const cachedHarga = parseFloat(selectedOpt.dataset.harga || 0);

        if (cachedHarga > 0) {
            hargaInp.value = cachedHarga;
            hitungSubtotal(tr);
            return;
        }

        // Fallback: fetch dari API
        try {
            const res  = await fetch(`${getBaseUrl()}/api/harga.php?id_nama=${idNama}`);
            const data = await res.json();
            if (data && typeof data.harga !== 'undefined') {
                hargaInp.value = data.harga;
                // Update cache di option
                selectedOpt.dataset.harga = data.harga;
                hitungSubtotal(tr);
            }
        } catch (err) {
            console.warn('Gagal fetch harga:', err);
        }
    }

    // ──────────────────────────────────────────
    // Hitung subtotal per baris
    // ──────────────────────────────────────────
    function hitungSubtotal(tr) {
        const berat    = parseFloat(tr.querySelector('.berat-input')?.value)    || 0;
        const harga    = parseFloat(tr.querySelector('.harga-input')?.value)    || 0;
        const subtotal = tr.querySelector('.subtotal-input');

        if (subtotal) {
            subtotal.value = (berat * harga).toFixed(2);
        }
        hitungGrandTotal();
    }

    // ──────────────────────────────────────────
    // Hitung grand total semua baris
    // ──────────────────────────────────────────
    function hitungGrandTotal() {
        const subtotals  = document.querySelectorAll('.subtotal-input');
        let grandTotal   = 0;

        subtotals.forEach(inp => {
            grandTotal += parseFloat(inp.value) || 0;
        });

        const el = document.getElementById('grand-total');
        if (el) {
            el.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', { minimumFractionDigits: 0 });
        }
    }

    // ──────────────────────────────────────────
    // Validasi form sebelum submit
    // ──────────────────────────────────────────
    function validasiForm(e) {
        const baris = document.querySelectorAll('#tbody-rekap tr');
        if (baris.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu item sampah.');
            return false;
        }

        let adaValid = false;
        baris.forEach(tr => {
            const idNama = tr.querySelector('select')?.value;
            const berat  = parseFloat(tr.querySelector('.berat-input')?.value) || 0;
            if (idNama && berat > 0) adaValid = true;
        });

        if (!adaValid) {
            e.preventDefault();
            alert('Pastikan setiap baris memiliki item dan berat yang valid.');
            return false;
        }
    }

    // ──────────────────────────────────────────
    // Escape HTML untuk keamanan output JS
    // ──────────────────────────────────────────
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ──────────────────────────────────────────
    // Event delegation (tanpa onclick/onchange inline — lolos CSP)
    // ──────────────────────────────────────────
    function bindTbodyDelegation() {
        const tbody = document.getElementById('tbody-rekap');
        if (!tbody || tbody.dataset.bsiRekapBound === '1') {
            return;
        }
        tbody.dataset.bsiRekapBound = '1';

        tbody.addEventListener('change', function (e) {
            const sel = e.target.closest('select.rekap-select-nama');
            if (sel) {
                fetchHarga(sel);
            }
        });

        tbody.addEventListener('input', function (e) {
            if (e.target.classList.contains('berat-input')) {
                const tr = e.target.closest('tr');
                if (tr) {
                    hitungSubtotal(tr);
                }
            }
        });

        tbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-hapus-baris');
            if (btn) {
                e.preventDefault();
                hapusBaris(btn);
            }
        });
    }

    // ──────────────────────────────────────────
    // Init saat DOM siap
    // ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        bindTbodyDelegation();

        const btnTambah = document.getElementById('btn-tambah-baris');
        if (btnTambah) {
            btnTambah.addEventListener('click', tambahBaris);
        }

        const formRekap = document.getElementById('form-rekap');
        if (formRekap) {
            formRekap.addEventListener('submit', validasiForm);
        }

        // Tambah 1 baris awal otomatis
        tambahBaris();
    });

})();
