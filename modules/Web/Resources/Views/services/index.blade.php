@extends('web::layouts.default', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Layanan' . ' | ')

@section('main')
<section id="services" class="py-10 bg-base-200">
  <!-- Section Title -->
  <div class="container mx-auto text-center mb-10">
    <h2 class="text-3xl font-bold text-primary">Services</h2>
    <p class="text-gray-600 mt-2">
      Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit
    </p>
  </div>

  <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Service Item -->
    <div class="card shadow-md bg-white hover:shadow-lg">
      <div class="card-body">
        <div class="flex items-center mb-4">
          <i class="bi bi-activity text-primary text-4xl mr-4"></i>
          <h3 class="card-title text-xl font-semibold">Nesciunt Mete</h3>
        </div>
        <p>
          Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.
        </p>
        <a href="#" class="link link-primary mt-4 inline-block">
          Learn More <i class="bi bi-arrow-right ml-2"></i>
        </a>
      </div>
    </div>

    <!-- Repeat Service Item -->
    <div class="card shadow-md bg-white hover:shadow-lg">
      <div class="card-body">
        <div class="flex items-center mb-4">
          <i class="bi bi-broadcast text-secondary text-4xl mr-4"></i>
          <h3 class="card-title text-xl font-semibold">Eosle Commodi</h3>
        </div>
        <p>
          Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque eum hic non ut nesciunt dolorem.
        </p>
        <a href="#" class="link link-secondary mt-4 inline-block">
          Learn More <i class="bi bi-arrow-right ml-2"></i>
        </a>
      </div>
    </div>

    <!-- Add more service items similarly -->
    <div class="card shadow-md bg-white hover:shadow-lg">
      <div class="card-body">
        <div class="flex items-center mb-4">
          <i class="bi bi-easel text-accent text-4xl mr-4"></i>
          <h3 class="card-title text-xl font-semibold">Ledo Markt</h3>
        </div>
        <p>
          Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.
        </p>
        <a href="#" class="link link-accent mt-4 inline-block">
          Learn More <i class="bi bi-arrow-right ml-2"></i>
        </a>
      </div>
    </div>

    <!-- Repeat other items with variations -->
  </div>
</section>

<section id="features" class="py-10 bg-base-200">
  <!-- Section Title -->
  <div class="container mx-auto text-center mb-10">
    <h2 class="text-3xl font-bold text-primary">Features</h2>
    <p class="text-gray-600 mt-2">
      Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit
    </p>
  </div>

  <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <!-- Feature Item -->
    <div class="card bg-white shadow-md hover:shadow-lg p-5">
      <div class="flex flex-col items-center text-center">
        <i class="bi bi-eye text-4xl text-yellow-500 mb-4"></i>
        <h3 class="text-lg font-semibold mb-2">Lorem Ipsum</h3>
        <a href="#" class="link link-primary">Learn More</a>
      </div>
    </div>

    <!-- Repeat Feature Item -->
    <div class="card bg-white shadow-md hover:shadow-lg p-5">
      <div class="flex flex-col items-center text-center">
        <i class="bi bi-infinity text-4xl text-blue-500 mb-4"></i>
        <h3 class="text-lg font-semibold mb-2">Dolor Sitema</h3>
        <a href="#" class="link link-primary">Learn More</a>
      </div>
    </div>

    <div class="card bg-white shadow-md hover:shadow-lg p-5">
      <div class="flex flex-col items-center text-center">
        <i class="bi bi-mortarboard text-4xl text-pink-500 mb-4"></i>
        <h3 class="text-lg font-semibold mb-2">Sed Perspiciatis</h3>
        <a href="#" class="link link-primary">Learn More</a>
      </div>
    </div>

    <div class="card bg-white shadow-md hover:shadow-lg p-5">
      <div class="flex flex-col items-center text-center">
        <i class="bi bi-nut text-4xl text-purple-500 mb-4"></i>
        <h3 class="text-lg font-semibold mb-2">Magni Dolores</h3>
        <a href="#" class="link link-primary">Learn More</a>
      </div>
    </div>

    <!-- Add additional items as needed -->
  </div>
</section>

@endsection
