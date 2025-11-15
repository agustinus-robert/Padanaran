<script>
    const exportExcel = async () => {
        if (confirm('Apakah Anda yakin?')) {
            let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;
            let {
                data
            } = await axios.get(@json(route('finance::report.overtimes.excel', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')])), {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });
            const wb = new ExcelJS.Workbook();
            const ws = wb.addWorksheet('Rekap lembur karyawan');
            // Add column title
            ws.columns = Object.keys(data.columns).map((key) => ({
                key: key,
                header: data.columns[key]
            }));
            ws.getRow(1).font = {
                bold: true
            };
            // Add employees
            ws.insertRows(2, data.employees);
            // Set auto width column
            ws.columns.forEach(function(column, i) {
                var maxLength = 0;
                column["eachCell"]({
                    includeEmpty: true
                }, function(cell) {
                    var columnLength = cell.value ? cell.value.toString().length : 10;
                    if (columnLength > maxLength) {
                        maxLength = columnLength;
                    }
                });
                column.width = maxLength < 10 ? 10 : maxLength;
            });
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
