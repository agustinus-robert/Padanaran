<script>
    const summaryExportExcel = async () => {
        if (confirm('Apakah Anda yakin?')) {
            let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;
            let {
                data
            } = await axios.get(@json(route('hrms::report.employees.summary', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')])), {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });
            const wb = new ExcelJS.Workbook();
            Object.keys(data.sheets).forEach((sheet) => {
                const ws = wb.addWorksheet(sheet);
                ws.columns = Object.keys(data.sheets[sheet].columns).map((col) => ({
                    header: data.sheets[sheet].columns[col],
                    key: col
                }));
                ws.columns.forEach(column => {
                    column.width = column.header.length < 12 ? 12 : column.header.length
                })
                ws.addRows(data.sheets[sheet].data);
                // Add title
                ws.insertRow(1, [data.title]);
                // Add subtitle
                ws.insertRow(2, [data.subtitle]);
            })
            wb.xlsx.writeBuffer().then((file) => {
                const blob = new Blob([file], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
                });
                saveAs(blob, `${data.file}.xlsx`);
            });
        }
    }
</script>
