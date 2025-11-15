<script>
    const exportExcel = async () => {
        if (confirm('Apakah Anda yakin?')) {
            let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;
            let {
                data
            } = await axios.get(@json(route('finance::payroll.templates.export')), {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });
            const wb = new ExcelJS.Workbook();
            const ws = wb.addWorksheet('Rekap gaji karyawan');
            // Add title
            ws.insertRow(1, [data.title]);

            // Add subtitle
            ws.insertRow(2, [data.subtitle]);

            // Add columns
            row = ws.getRow(4);
            row.getCell(1).value = 'No';
            ws.mergeCells('A4:A5');
            row.getCell(2).value = 'Nama';
            ws.mergeCells('B4:B5');
            row.getCell(3).value = 'NPWP';
            ws.mergeCells('C4:C5');
            row.getCell(4).value = 'Departemen';
            ws.mergeCells('D4:D5');
            row.getCell(5).value = 'Jabatan';
            ws.mergeCells('E4:E5');

            // Header
            let colnum = 6;
            row.getCell(colnum).value = 'THR'
            ws.mergeCells(4, colnum, 4, colnum + Object.values(data.thr).length - 1);
            row.getCell(colnum + Object.values(data.thr).length).value = 'Gaji ke-13'
            ws.mergeCells(4, colnum + Object.values(data.thr).length, 4, colnum + Object.values(data.thr).length + Object.values(data.g13).length - 1);

            // Subheader
            row = ws.getRow(5);
            Object.values(data.thr).forEach((v) => {
                row.getCell(colnum++).value = v
            });
            Object.values(data.g13).forEach((v) => {
                row.getCell(colnum++).value = v
            });

            // Data
            ws.columns = Object.keys(data.columns).map(i => ({
                key: i
            }))
            ws.insertRows(6, data.rows);

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
