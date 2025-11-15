<script>
    const exportExcel = async () => {
        if (confirm('Apakah Anda yakin?')) {
            let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;
            let {
                data
            } = await axios.get(@json(route('portal::overtime.export.index', ['start_at' => $start_at, 'end_at' => $end_at])), {
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
            ws.insertRow(1, [data.title]);
            // Add subtitle
            ws.insertRow(2, [data.employee]);
            // Add position
            ws.insertRow(3, [data.position]);
            // Add dept
            ws.insertRow(4, [data.department]);
            // Add br
            ws.insertRow(5, []);
            // Add overtime
            ws.insertRows(7, data.overtimes);

            // Set auto width column
            ws.columns.forEach(function(column, i) {
                var maxLength = 0;
                column.alignment = {
                    vertical: 'top',
                    horizontal: 'left',
                    wrapText: true
                };
                column["eachCell"]({
                    includeEmpty: true
                }, function(cell) {
                    var columnLength = cell.value ? cell.value.toString().length : 10;
                    if (columnLength > maxLength) {
                        maxLength = columnLength;
                    }
                });
                column.width = maxLength < 10 ? 10 : (maxLength > 100 ? 100 : maxLength);
            });
            // Render
            wb.xlsx.writeBuffer().then((file) => {
                const blob = new Blob([file], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
                });
                saveAs(blob, `${data.title}.xlsx`);
            });
        }
    }
</script>
