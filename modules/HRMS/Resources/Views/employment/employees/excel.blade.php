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
                <th>ID Karyawan</th>
                <th>Nama Tersimpan</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>Status</th>
                <th>Jumlah Tanggungan</th>
                <th>Agama</th>
                <th>No Hp</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr @if ($employee->trashed()) class="table-light text-muted" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->user->name }}</td>
                    <td></td>
                    <td>{{ !empty($employee->user->getMeta('profile_sex')) ? \Modules\Account\Enums\SexEnum::tryFrom($employee->user->getMeta('profile_sex'))->label() : '' }}</td>
                    <td>{{ $employee->user->getMeta('address_primary') }}</td>
                    <td>{{ $employee->user->getMeta('profile_nik') }}</td>
                    <td>{{ $employee->user->getMeta('tax_number') }}</td>
                    <td>{{ !empty($employee->user->getMeta('profile_mariage')) ? \Modules\Account\Enums\MariageEnum::tryFrom($employee->user->getMeta('profile_mariage'))->label() : '' }}</td>
                    <td>{{ $employee->user->getMeta('profile_child') }}</td>
                    <td>{{ !empty($employee->user->getMeta('profile_religion')) ? \Modules\Account\Enums\ReligionEnum::tryFrom($employee->user->getMeta('profile_religion'))->label() : '' }}</td>
                    <td>{{ $employee->user->getMeta('phone_number') }}</td>
                    <td>{{ $employee->user->email_address }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    <br>
</body>

</html>
