<div>
    <div class="d-flex justify-content-between align-items-center pb-3 pt-5">
        <h2 class="h3 fw-normal mb-0">Top 10 Penjulan</h2>
        <div wire:loading wire:target="selectedRangeTopSeller" class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <div class="dropdown">
            <select class="form-select" wire:model="selectedTop" wire:change="selectedRangeTopSeller">
                <option value="">Pilih</option>
                <option value="currentWeek">Minggu Ini</option>
                <option value="beforeWeek">Minggu Sebelumnya</option>
                <option value="currentMonth">Bulan Ini</option>
                <option value="beforeMonth">Bulan Sebelumnya</option>
            </select>
        </div>
    </div>
    <div class="block-rounded block-fx-pop block">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="p-md-2 p-lg-3">
                        <canvas id="topSellChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- Include Chart.js jika belum dimuat di layout --}}

    <script>
        const ctx = document.getElementById('topSellChart').getContext('2d');

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Top 10 Produk: Barang Keluar vs Masuk'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        const mixedChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels), // Nama produk
                datasets: [{
                        label: 'Barang Keluar',
                        data: @json($chartStockOut),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderRadius: 5
                    },
                    {
                        label: 'Barang Masuk',
                        data: @json($chartStockIn),
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderRadius: 5
                    }
                ]
            },
            options: options
        });
    </script>
@endpush
