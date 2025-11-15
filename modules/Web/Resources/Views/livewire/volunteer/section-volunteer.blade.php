<div>
	<section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold">{{$lang == 'id' ? 'Bergabung sebagai sukarelawan' : 'Become a volunteer!'}}</h1>
            <p class="max-w-4xl text-muted-foreground">
                {{$lang == 'id' ? 'Ayo jadi sukarelawan!' : 'Ayo jadi sukarelawan!'}}
            </p>
        </div>
        <form class="space-y-8" wire:submit="save">
            <p class="text-muted-foreground">
                {!! $lang == 'id' ? 'Anda bisa bernyanyi, mengemudi, membuat roti, mengajar, atau menanam untuk kami! Tidak ada batasan untuk
                apapun yang Anda bisa lakukan untuk menggalang dukungan bagi YSBY.<br />Siapapun dapat bergabung sebagai
                sukarelawan, apapun latar belakang pendidikan atau pekerjaannya. Tidak ada batas maksimal usia, namun calon
                sukarelawan setidaknya telah berusia 18 tahun.' : 'You can sing, drive, bake, teach or plant for us! There\'s no limit to the ways in which you can raise support for YSBY.<br /> Anyone from any background can participate in volunteering. There is no maximum age limit, but applicants must be at least 18 years old.' !!}
               
            </p>
            @include('web::get-involved.partials.get-involved-form', [
                'label' => 'Silahkan masukan informasi data diri anda untuk proses pendaftaran.',
                'buttonLabel' => (isset(\Auth::user()->role->role_id) && \Auth::user()->role->role_id == 2 ? 'Dashboard' : 'Daftar Sekarang'),
                'role' => (isset(\Auth::user()->role->role_id) ?  \Auth::user()->role->role_id : '')
            ])
        </form>
    </section>
</div>