/**
 * upload-preview.js
 * Preview foto sebelum upload — dipanggil via onchange di input file.
 * Fungsi bersifat global agar bisa dipanggil dari atribut HTML.
 */

/**
 * Tampilkan preview gambar yang dipilih.
 *
 * @param {HTMLInputElement} input   - Elemen input[type=file]
 * @param {string}           previewId - ID elemen <img> untuk preview
 */
function previewUpload(input, previewId) {
    const previewEl = document.getElementById(previewId);
    if (!previewEl) return;

    if (input.files && input.files[0]) {
        const file     = input.files[0];
        const maxSize  = 2 * 1024 * 1024; // 2MB
        const allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

        // Validasi tipe file di sisi klien (validasi server tetap wajib)
        if (!allowed.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
            input.value = '';
            previewEl.style.display = 'none';
            return;
        }

        // Validasi ukuran
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            previewEl.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewEl.src     = e.target.result;
            previewEl.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewEl.style.display = 'none';
        previewEl.src = '';
    }
}

// Bind event listener otomatis (hindari inline `onchange` agar sesuai CSP).
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[data-preview-target]');
    inputs.forEach(function (inp) {
        const previewId = inp.getAttribute('data-preview-target');
        if (!previewId) return;
        inp.addEventListener('change', function () {
            previewUpload(inp, previewId);
        });
    });
});
