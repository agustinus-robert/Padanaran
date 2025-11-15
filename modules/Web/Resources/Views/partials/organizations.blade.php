 <section class="container mx-auto md:px-16 py-16 space-y-8">
     @include('web::partials.section-header', [
         'title' => [
             ['content' => explode(' ', get_content_json($section)[$lang]['title'])[0], 'isBold' => true],
             ['content' => explode(' ', get_content_json($section)[$lang]['title'])[1]],
         ],
     ])
     <div class="flex items-center justify-center gap-4 md:gap-24">
        @foreach($content_company as $key => $value)
            @if($key == 1)
                @php $style = 'w-60 h-14'; @endphp
                
                @php
                    $image1 = get_image(get_dark_light_image($value->id)[0]->location, get_dark_light_image($value->id)[0]->image);
                    $image2 = get_image(get_dark_light_image($value->id)[1]->location, get_dark_light_image($value->id)[1]->image);
                @endphp

                
               <img src="{{$image1}}" class="{{$style. ' block dark:hidden'}}" />
               <img src="{{$image2}}" class="{{$style. ' hidden dark:block'}}" />
                
            @else
                @php $style = 'size-20 md:size-32'; 
                    $image = get_image($value->location, $value->image);
                @endphp

                <img src="{{$image}}" class="{{$style}}" />
            @endif

         
        {{--  <img src="https://suarabhakti.id/images/logo_WB_png.png" alt="Wisma Bahasa Logo" class="size-20 md:size-32" />
         <img src="{{asset('images/ImportedPhoto_1728633399057.jpg')}}" alt="CD Logo" class="size-20 md:size-32" /> --}}
         @endforeach
     </div>
 </section>
