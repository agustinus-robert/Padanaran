<div>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold">{{$lang == 'id' ? 'Menjadi partner kami' : 'Be a partner!'}}</h1>
            <p class="max-w-4xl text-muted-foreground">
                {{$lang == 'id' ? 'Ayo menjadi partner YSBY!' : 'Be a partner!'}}
            </p>
        </div>
        <form class="space-y-8" wire:submit="save">
            <p class="text-muted-foreground">
                {!!$lang == 'id' ? 'Saatnya bekerja sama dengan tim yang berpotensi membantu Anda membuat perbedaan lebih besar di masyarakat
                dan mengembangkan kapabilitas organisasi.<br />
                Silakan menggali lebih dalam mengenai apa yang kami tawarkan dan turut serta dalam upaya kami untuk
                menumbuhkan peluang bagi semua insan, di masyarakat manapun, demi mencapai kesejahteraan.' :  'It\'s time to team up with a group that can assist you in making a bigger difference in your community and expanding your organization\'s capabilities. Explore the assistance we offer and come alongside us in our effort to foster opportunities for all individuals, in any community, to prosper. <br /> Share a little about yourself and let\'s discuss how you can become an excellent partner.'!!}</p>
            @include('web::get-involved.partials.get-involved-form', [
                'label' => 'Silahkan masukan informasi data diri anda untuk proses pendaftaran.',
                'buttonLabel' => (isset(\Auth::user()->role->role_id) && \Auth::user()->role->role_id == 2 ? 'Dashboard' : 'Daftar Sekarang'),
                'role' => (isset(\Auth::user()->role->role_id) ?  \Auth::user()->role->role_id : '')
            ])
        </form>
    </section>
</div>