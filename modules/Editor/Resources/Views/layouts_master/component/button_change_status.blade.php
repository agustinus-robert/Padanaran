<button class="btn btn-sm btn-primary" id="viewStatus" title="View Status">
    <i class="fas fa-eye"></i> Change Status
</button>

<div class="modal fade" id="statusChangedModal" tabindex="-1" aria-labelledby="statusChangedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Ubah status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="confirmationStatus" class="form-label"><b>Status</b></label>
                    <select class="form-select" id="confirmationStatus" required>
                        <option value="1">Ordered</option>
                        <option value="2">Delivered</option>
                        <option value="3">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusButton">Simpan</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.getElementById('viewStatus').addEventListener('click', function(e) {
    e.preventDefault(); // Mencegah navigasi default
    // Tampilkan modal
    var modal = new bootstrap.Modal(document.getElementById('statusChangedModal'));
    modal.show();
});

document.getElementById('confirmStatusButton').addEventListener('click', function() {
    // Ambil tanggal dari input
    var estimatedConfirmStatus = document.getElementById('confirmationStatus').value;

    // Lakukan validasi jika diperlukan
    if(!estimatedConfirmStatus){
        alert('Harap isikan konfirmasi status.')
        return;
    }

    // Jika validasi berhasil, navigasi ke URL yang diinginkan
    window.location.href = '<?=url($statusUrl)?>?estimatedConfirmStatus=' + estimatedConfirmStatus;
});
</script>