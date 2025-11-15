<button class="btn btn-info btn-xs btn_detail_modal mb-1 ml-1" type="button" id="productTransition{{ $id }}" title="Edit">
    <i class="fas fa-eye"></i>
</button>

<div class="modal fade" id="productDetailTransition{{ $id }}" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Detail Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @livewire('poz::transaction.information', ['id' => $id])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('productTransition{{ $id }}').addEventListener('click', function(e) {
        e.preventDefault(); // Mencegah navigasi default
        // Tampilkan modal
        var modal = new bootstrap.Modal(document.getElementById('productDetailTransition{{ $id }}'));
        modal.show();
    });
</script>
