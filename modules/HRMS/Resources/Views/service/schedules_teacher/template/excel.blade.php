<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <div class="header">
        <h3>Daftar Karyawan</h3>
        <br>
    </div>
    <table class="table-striped table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pegawai</th>
                <th>Kode Pegawai</th>
                <th>Beban Kerja</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr @if ($employee->trashed()) class="table-light text-muted" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->user->name }}</td>
                    <td>{{ $employee->getMeta('code') ?? '' }}</td>
                    <td>{{ $employee->getMeta('default_workhour') ?? '' }}</td>
                    <td>{{ $employee->id ?? ''}}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    <br>
</body>

</html>
