 
  <section style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{get_image($join_us->location, $join_us->image)}}');"
      class="bg-center">
      <div class="container mx-auto md:px-16 py-16 space-y-8">
          <h1 class="text-4xl md:text-5xl font-bold text-center max-w-5xl mx-auto text-white">
              {{get_content_json($join_us)[$lang]['post0']}}
          </h1>
          <div class="flex items-center justify-center gap-2">
              <a href="{{ url(request()->bahasa . '/get-involved/donation')  }}" class="block">
                  <x-web::button size="lg">
                      {{$lang == 'id' ? 'Donasi' : 'Donation'}}
                  </x-web::button>
              </a>
              <a href="{{ url(request()->bahasa . '/get-involved/volunteer') }}" class="block">
                  <x-web::button size="lg">
                      {{$lang == 'id' ? 'Relawan' : 'Volunteer'}}
                  </x-web::button>
              </a>
              <a href="{{ url(request()->bahasa . '/get-involved/partnership') }}" class="block">
                <x-web::button size="lg">
                        {{$lang == 'id' ? 'Menjadi Partner' : 'Partnership'}}
                </x-web::button>
              </a>
              <a href="{{ url(request()->bahasa . '/get-involved/career') }}" class="block">
                <x-web::button size="lg">
                        {{$lang == 'id' ? 'Karier' : 'Career'}}
                </x-web::button>
              </a>
          </div>
      </div>
  </section>
