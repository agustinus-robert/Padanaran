<style>
  /* Garis penghubung antar titik */
  .milestone-hr {
    position: relative;
  }
  @media (min-width: 768px) {
    .milestone-hr::before {
      content: "";
      position: absolute;
      top: 25px; /* Sesuaikan dengan posisi vertikal lingkaran */
      left: 10%;
      right: 10%;
      height: 2px;
      background: #dee2e6;
      z-index: 0;
    }
  }
  .milestone-dot {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    position: relative;
    margin: 0 auto;
    border: 4px solid #fff;
  }
</style>

<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Alur Kerja Kami</h2>
    <p class="text-muted">Proses sederhana untuk hasil yang luar biasa</p>
  </div>

  <div class="row milestone-hr g-4">
    <div class="col-md-4 text-center">
      <div class="milestone-dot bg-primary text-white rounded-circle shadow-sm mb-3">
        <i class="bi bi-search">1</i>
      </div>
      <h5 class="fw-bold">Riset</h5>
      <p class="text-muted px-lg-4">Kami menganalisis kebutuhan pasar dan target audiens Anda secara mendalam.</p>
    </div>

    <div class="col-md-4 text-center">
      <div class="milestone-dot bg-primary text-white rounded-circle shadow-sm mb-3">
        <i class="bi bi-code-slash">2</i>
      </div>
      <h5 class="fw-bold">Eksekusi</h5>
      <p class="text-muted px-lg-4">Proses pengembangan dengan standar kualitas tinggi dan pengujian ketat.</p>
    </div>

    <div class="col-md-4 text-center">
      <div class="milestone-dot bg-success text-white rounded-circle shadow-sm mb-3">
        <i class="bi bi-rocket-takeoff">3</i>
      </div>
      <h5 class="fw-bold text-success">Peluncuran</h5>
      <p class="text-muted px-lg-4">Produk siap digunakan dan kami siap memberikan dukungan teknis penuh.</p>
    </div>
  </div>
</div>