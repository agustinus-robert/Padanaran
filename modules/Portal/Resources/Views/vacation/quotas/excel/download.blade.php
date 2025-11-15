<button id="export-excel" class="list-group-item list-group-item-action p-4" style="border-style: dashed;" onclick="exportExcel()">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-inline-block bg-soft-secondary text-success me-2 rounded text-center" style="height: 36px; width: 36px;">
            <i class="mdi mdi-file-excel-outline mdi-24px"></i>
        </div>
        <div class="flex-grow-1">Unduh seluruh data yang ditampilkan ke *.xlsx</div>
        <i class="mdi mdi-chevron-right-circle-outline"></i>
    </div>
</button>

@push('scripts')
    <script>
        const exportExcel = async () => {
            if (confirm('Apakah Anda yakin?')) {
                let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;
                let {
                    data
                } = await axios.get("{{ route('portal::vacation.quotas.excel.index', request()->all()) }}", {
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                });
                const wb = new ExcelJS.Workbook();
                const ws = wb.addWorksheet('Sisa kuota cuti');
                ws.columns = Object.keys(data.columns).map((k) => ({
                    key: k,
                    header: data.columns[k].replace(/(<([^>]+)>)/gi, " ").trim(),
                }));
                // Add title
                ws.insertRow(1, [data.title]);
                ws.mergeCells(1, 1, 1, Object.values(data.columns).length);
                // Add subtitle
                ws.insertRow(2, [data.subtitle]);
                ws.mergeCells(2, 1, 2, Object.values(data.columns).length);
                // Set header font style
                ws.getRow(3).font = {
                    bold: true
                };
                // Insert row of employees
                let row = ws.getRow(ws.lastRow.number + 1);
                let primaryCells = ['index', 'name', 'position'];
                for (employee of data.employees) {
                    let lastRowNumber = ws.lastRow.number;
                    primaryCells.forEach((key, i) => {
                        row.getCell(key).value = employee[key];
                        row.getCell(key).alignment = {
                            vertical: 'top',
                            horizontal: 'left'
                        };
                    })
                    // Insert row of quotas
                    for (quota of employee.quotas) {
                        for (key of Object.keys(quota)) {
                            row.getCell(key).value = quota[key];
                        }
                        row = ws.getRow(ws.lastRow.number + 1);
                    }
                    // Merge cells
                    primaryCells.forEach((key, i) => {
                        ws.mergeCells(lastRowNumber, i + 1, lastRowNumber - 1 + employee.quotas.length, i + 1);
                    })
                }
                // Render
                wb.xlsx.writeBuffer().then((file) => {
                    const blob = new Blob([file], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
                    });
                    saveAs(blob, `${data.file}.xlsx`);
                });
            }
        }
    </script>
@endpush
