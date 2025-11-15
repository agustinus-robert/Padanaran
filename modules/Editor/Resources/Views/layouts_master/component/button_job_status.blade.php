<a class="btn btn-sm btn-light-info" id="confirmJobTransition"  href="#"  title="Edit">
    <i class="mdi mdi-file-edit"></i> Inquiry ke Job
</a>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="confirmationDate" class="form-label">Estimasi Tanggal Awal</label>
                    <input type="date" class="form-control" id="confirmationDate" required>
                </div>
                <div class="mb-3">
                    <label for="estimatedDate" class="form-label">Estimasi Tanggal Akhir</label>
                    <input type="date" class="form-control" id="estimatedDate" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmJobTransitionButton">Setujui</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.getElementById('confirmJobTransition').addEventListener('click', function(e) {
    e.preventDefault(); // Mencegah navigasi default
    // Tampilkan modal
    var modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
});

document.getElementById('confirmJobTransitionButton').addEventListener('click', function() {
    // Ambil tanggal dari input
    var confirmationDate = document.getElementById('confirmationDate').value;
    var estimatedDate = document.getElementById('estimatedDate').value;

    // Lakukan validasi jika diperlukan
    if (!confirmationDate || !estimatedDate) {
        alert("Harap isi semua kolom tanggal.");
        return;
    }

    // Jika validasi berhasil, navigasi ke URL yang diinginkan
    window.location.href = '<?=url($job_status)?>?confirmationDate=' + confirmationDate + '&estimatedDate=' + estimatedDate;
});
</script>