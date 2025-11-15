<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Menu Builder</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Menu Builder</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="content flex-column-fluid" id="kt_content">
       <div class="card card-flush">
            <div class="card-header border-0 pt-6">
               
                <div class="card-toolbar">
                    <div x-data="{ language : $persist(@entangle('language')).using(cookieStorage) }">
                        <select class="form-select" wire:change="helperLanguage($event.target.value)">
                            <option value="id">Indonesia</option>
                            <option value="eng">English</option>
                        </select>
                    </div>
                </div>
            </div>


        <div class="card-body">
                   

        <div class="mt-2" style="margin-top:20px;">
            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 1 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-web-box mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Site General</h4>
                    <p>Step <a href="javascript:void(0)">Site General</a> Berguna untuk setting awal sebuah halaman, yang nantinya akan dimunculkan di menu</p>
            </div>

            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 2 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-post mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Site Posting</h4>
                    <p>Step <a href="javascript:void(0)">Site Type</a> Berguna untuk mengatur setting tipe dari inputan</p>
            </div>

            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 3 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-list-box mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Taxonomy Configuration</h4>
                    <p>Step <a href="javascript:void(0)">Taxonomy Configurator</a> Berguna untuk menambahkan category atau tags pada post</p>
            </div>

            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 4 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-image mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Site Image</h4>
                    <p>Step <a href="javascript:void(0)">Site Image</a> Berguna untuk memilih rekomendasi gambar baik di desktop, tablet, atau mobile</p>
            </div>


            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 5 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-shopping mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Woocommerce Integration (Opsional)</h4>
                    <p>Step <a href="javascript:void(0)">Woocommerce </a> Berguna integrasi pada plugins woocommerce</p>
            </div>

            <div class="mb-5" style="text-align: center; display: <?=$cur_step != 6 ? 'none' : 'block' ?>">
                    <i class="mdi mdi-content-save-edit mdi-48px"></i>
                    <h4 style="margin-top: 0.25em;">Simpan</h4>
                    <p>Step <a href="javascript:void(0)">Simpan </a> Berguna jika anda sudah yakin bahwa semua form terisi dengan benar</p>
            </div>

            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 1 ? '' : 'active' }}" href="javascript:void(0)"  wire:click="step_1">Site General</a>
                    </div>
                </li>
                <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 2 ? '' : 'active' }}" href="javascript:void(0)" wire:click="step_2" href="#step2">Posting Configuration</a>
                    </div>
                </li>
                <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 3 ? '' : 'active' }}" href="javascript:void(0)" wire:click="step_3" href="#step3">Taxonomy Configuration</a>
                    </div>
                </li>

                <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 4 ? '' : 'active' }}" href="javascript:void(0)" wire:click="step_4">Sites Image</a>
                    </div>
                </li>

                <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 5 ? '' : 'active' }}" href="javascript:void(0)" wire:click="step_5">Woocommerce Integration</a>
                    </div>
                </li>

                 <li class="nav-item">
                    <div x-data="{ cur_step : $persist(@entangle('cur_step')).using(cookieStorage) }">
                        <a class="nav-link {{ $cur_step != 6 ? '' : 'active' }}" href="javascript:void(0)" wire:click="step_6">Submit Process</a>
                    </div>
                </li>
            </ul>
        </div>

        <hr> 
        
        <div class="mt-3">
            <div id="step1" style="display: <?=$cur_step != 1 ? 'none' : 'block' ?>">

                <div x-data="{ title: $persist(@entangle('title')).using(cookieStorage) }" class="form-group col-md-12 mb-3">
                    <label class="form-label">Menu Title</label>
                    <input wire:key="title.{{$this->language}}" wire:model.lazy="title.{{$this->language}}" type="text" class="form-control" />
                </div>

                <div x-data="{ slug: $persist(@entangle('slug')).using(cookieStorage) }" class="form-group col-md-12 mb-3">
                    <label class="form-label">Slug Menu</label>
                    <input wire:key="slug.{{$this->language}}" wire:model.lazy="slug.{{$this->language}}" type="text" class="form-control" />
                </div>

                <label class="form-label">Icon (Silahkan pilih dropdown)</label>

                <div class="input-group col-md-12 mb-3" wire:ignore>    
                    <div class="input-group-prepend">
                        <span class="input-group-text h-100 selected-icon"></span>
                    </div>
                    
                    <input type="text" id="getD" class="form-control iconpicker">

                    {{-- <input type="hidden" id="lz" type="text" wire:model.lazy="icon" />   --}}
                </div>

                <div x-data="{ type: $persist(@entangle('type')).using(cookieStorage) }" class="form-group col-md-12 mb-3">
                    <label class="form-label">Type</label>
                    <select wire:change="check_type" wire:model="type" class="form-select">
                        <option value="">Choose Type</option>
                        <option value="1">Menu</option>
                        <option value="2">Master</option>
                        <option value="4">Content</option>
                        <option value="5">Featured</option>
                        <option value="6">Uploader File</option>
                        <option value="7">Form</option>
                        <option value="8">Blog</option>
                        <option value="9">Career</option>
                    </select>
                </div>

               <div x-data="{ isShowing: $persist(@entangle('type')).using(cookieStorage), meta_menu: $persist(@entangle('meta_menu')).using(cookieStorage) }">
                    <div x-show="isShowing == '1'" class="form-group col-md-12 mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea wire:key="meta_menu.{{$this->language}}" wire:model.lazy="meta_menu.{{$this->language}}" class="form-control col-md-12"></textarea>
                    </div>
                </div>

                <div x-data="{ isShowing: $persist(@entangle('type')).using(cookieStorage), meta_keyword: $persist(@entangle('meta_keyword')).using(cookieStorage) }">
                    <div x-show="isShowing == '1'" class="form-group col-md-12 mb-3">
                        <label class="form-label">Meta Keyword</label>
                        <textarea wire:key="meta_keyword.{{$this->language}}" wire:model.lazy="meta_keyword.{{$this->language}}" class="form-control col-md-12"></textarea>
                    </div>
                </div>

                <div x-data="{ isShowing: $persist(@entangle('type')).using(cookieStorage), custom_menu: $persist(@entangle('custom_menu')).using(cookieStorage) }">
                    <div x-show="isShowing == '5'" class="form-group col-md-12 mb-3">
                        <label class="form-label">Custom Link</label>
                        <input wire:model.lazy="custom_menu" class="form-control col-md-12" />
                    </div>
                </div>

                <div wire:ignore x-data="{ isShowing: $persist(@entangle('type')).using(cookieStorage), custom_menu: $persist(@entangle('relasi_menu')).using(cookieStorage) }">
                    <div x-show="isShowing == '9'" class="form-group col-md-12 mb-3">
                        <label class="form-label">Relasi Menu</label>
                        <select id="m-id" class="form-select select-2">
                            <option value="">Pilih Menu</option>
                            @foreach($list_menu as $key => $value)
                                <option value="{{$value->id}}">{{get_title_json($value)['id']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                 <div class="form-group col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label">Add</label>
                            <select wire:model="add" class="form-select" wire:change='change_add($event.target.value)' wire:key="add-select">
                                <option value="1">Enable</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Edit</label>
                            <select wire:model="edit" class="form-select" wire:change='change_edit($event.target.value)'>
                                <option value="1">Enable</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Album</label>
                            <select wire:model="album" class="form-select" wire:change='change_album($event.target.value)'>
                                <option value="1">Enable</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Video</label>
                            <select wire:model="video" class="form-select" wire:change='change_video($event.target.value)'>
                                <option value="1">Enable</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Delete</label>
                            <select wire:model="delete" class="form-select" wire:change='change_delete($event.target.value)'>
                                <option value="1">Enable</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
              
            </div>

            <div id="step2"  style="display: <?=$cur_step != 2 ? 'none' : 'block' ?>">
                <div class="text-center mb-3" style="margin-top:20px;">
                    <button class="btn btn-primary" wire:click="add_function">Click to Add</button>
                </div>
                <?php 
                    
                    foreach($conf_site as $k => $v){
                        $k = str_replace("field_", "", $k);

                        $part_1 = 'conf_site'.'.field_'.$k.'.ft'.$k;
                        $part_2 = 'conf_site'.'.field_'.$k.'.fy'.$k;
                        $part_3 = 'conf_site'.'.field_'.$k.'.v'.$k;
                ?>
                <div class="row">
                                   

                    <div class="col-md-3 mb-3">
                        <div x-data="{ ft{{$k}}: $persist(@entangle($part_1)).using(cookieStorage) }" class="form-group">
                            <label class="form-label">Field Title</label>
                            <input wire:key="<?=$part_1?>.{{$this->language}}" wire:model.lazy="<?=$part_1?>.{{$this->language}}" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div x-data="{ fy{{$k}}: $persist(@entangle($part_2)).using(cookieStorage) }" class="form-group">
                            <label class="form-label">Field Type</label>
                            
                            <select wire:model="<?=$part_2?>" class="form-control">
                                <option value="">Pilih Editor</option>
                                <option value="raw_text">Text</option>
                                <option value="editor">Editor</option>
                                <option value="textarea">Textarea</option>
                                <option value="currency">Currency</option>
                                <option value="date">Date</option>
                                <option value="gutenberg">Gutenberg</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div x-data="{ v{{$k}}: $persist(@entangle($part_3)).using(cookieStorage) }" class="form-group">
                            <label class="form-label">Validate</label>
                            <select wire:model="<?=$part_3?>" class="form-control">
                                <option value="">Pilih Validasi</option>
                                <option value="required">Required</option>
                                <option value="not_required">Not Required</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label">Action</label>
                            <br />
                            <button wire:click="delete_row_site({{$k}})" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>


                    
                </div>


                <?php } ?>
            </div>

            @livewire('admin::builder.clear-cookie')
                

           
            <div id="step3"  style="display: <?=$cur_step != 3 ? 'none' : 'block' ?>">
                <div class="text-center" style="margin-top:20px;">
                    <button class="btn btn-primary" wire:click="add_function_taxo">Click to Add</button>
                </div>
                <?php
                    foreach($conf_taxo as $k => $v){
                        $k = str_replace("fieldtaxo_", "", $k);

                        $part_1 = 'conf_taxo.'.'fieldtaxo_'.$k.'.ft_taxo'.$k;
                        $part_2 = 'conf_taxo.'.'fieldtaxo_'.$k.'.fy_taxo'.$k;
                        $part_3 = 'conf_taxo.'.'fieldtaxo_'.$k.'.v_taxo'.$k;
                ?>

                <div class="row">

                                  
                        
                    <div x-data="{ ft_taxo{{$k}}: $persist(@entangle($part_1)).using(cookieStorage) }" class="form-group col-md-3">
                        <label class="form-label">Field Title</label>
                        <input wire:model.lazy="<?=$part_1?>" type="text" class="form-control" />
                    </div>


                    <div x-data="{ fy_taxo{{$k}}: $persist(@entangle($part_2)).using(cookieStorage) }" class="form-group col-md-3">
                        <label class="form-label">Field Taxonomy</label>
                        
                        <select wire:model="<?=$part_2?>" class="form-control" wire:change="changeTaxonomy($event.target.value, {{$k}})">
                            <option value="">Pilih Taxonomy</option>
                            <?php 
                            foreach($menu_category as $index => $value){ ?>
                                <option value="<?=$value->id?>"><?=$value->title?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div x-data="{ v_taxo{{$k}}: $persist(@entangle($part_3)).using(cookieStorage) }" class="form-group col-md-3">
                        <label class="form-label">Validate</label>
                        <select wire:model="<?=$part_3?>" class="form-control">
                            <option value="">Pilih Validasi</option>
                            <option value="required">Required</option>
                            <option value="not_required">Not Required</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Action</label>
                            <br />
                            <button wire:click="delete_row_taxo({{$k}})" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
                 <?php } ?>
            </div> 

            @livewire('admin::builder.clear-taxo-cookie')

            <div id="step4" style="display: <?=$cur_step != 4 ? 'none' : 'block' ?>">
                <div id="col-md-12">
                    <div x-data="{ image_desktop: $persist(@entangle('size_desktop')).using(cookieStorage) }" class="form-group col-md-12">
                        <label class="form-label">Image Desktop Size</label>
                        <select wire:model="size_desktop" class="form-control">
                            <option value="">Silahkan Pilih Ukuran Image Desktop</option>
                            <option value="1280px|720px">1280 x 720 (Landscape)</option>
                            <option value="1360px|800px">1360 x 800 (Landscape)</option>
                        </select>
                        <p>Silahkan Check website <a target="_blank" href="https://tiny-img.com/blog/best-image-size-for-website/">Cek Reccomendation Image</a></p>
                    </div>

                    <div x-data="{ image_mobile: $persist(@entangle('size_mobile')).using(cookieStorage) }" class="form-group col-md-12">
                        <label class="form-label">Image Mobile Size</label>
                        <select wire:model="size_mobile" class="form-control">
                            <option value="">Silahkan Pilih Ukuran Image Mobile</option>
                            <option value="500px|720px">500 x 720 (Potrait)</option>
                            <option value="620px|800px">620 x 800 (Potrait)</option>
                        </select>
                        <p>Silahkan Check website <a target="_blank" href="https://tiny-img.com/blog/best-image-size-for-website/">Cek Reccomendation Image</a></p>
                    </div>
                </div>
            </div> 

            <div id="step5" style="display: <?=$cur_step != 5 ? 'none' : 'block' ?>">
                <div id="col-md-12">
                    <div class="form-group col-md-12">
                        <label class="form-label">Woocommerce Store URL</label>
                        <input type="text" class="form-control" />
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label">Woocommerce Consumer Key</label>
                        <input type="text" class="form-control" />
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label">Woocommerce Consumer Secret</label>
                        <input type="text" class="form-control" />
                    </div>

                    <div class="form-group col-md-2">
                        <button class="btn btn-info">Validate</button>
                    </div>
                </div>
            </div> 

             <div id="step6" style="display: <?=$cur_step != 6 ? 'none' : 'block' ?>">
                <div class="jumbotron">
                  <h1 class="display-4">Silahkan simpan data pada konfigurasi</h1>
                  <p class="lead">Jika anda yakin sudah mengisi semua form, kecuali untuk opsional</p>
                  <hr class="my-4">
                  <p>Terima Kasih</p>
                  <a class="btn btn-primary btn-lg" wire:click="submitForm" href="javascript:void(0)" role="button">Simpan</a>
                </div>
             </div>
        </div>

        <script>
        window.cookieStorage = {
            getItem(key) {
                let cookies = document.cookie.split(";");
                for (let i = 0; i < cookies.length; i++) {
                    let cookie = cookies[i].split("=");
                    if (key == cookie[0].trim()) {
                        return decodeURIComponent(cookie[1]);
                    }
                }
                return null;
            },
            setItem(key, value) {
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = key+' = '+value + expires + "; path=/";
            }
        }
    </script>


                </div>
            </div>
        </div>

    @push('scripts')
    <script>
        
         (async () => {
                const response = await fetch('{{env('APP_URL')}}/json-icon/v1.json')
                const result = await response.json()

                 var get_icon = "{{$icon}}"

                const iconpicker = new Iconpicker(document.querySelector(".iconpicker"), {
                    icons: result,
                    searchable: true,
                    showSelectedIn: document.querySelector(".selected-icon"),
                    selectedClass: "selected",
                    containerClass: "my-picker",
                    hideOnSelect: true,
                    fade: true,
                    defaultValue: (get_icon ? get_icon : 'mdi mdi-apps'),
                    valueFormat: val => `mdi mdi-${val}`
                });

                 // Set as empty

                if(get_icon !== ''){
                    iconpicker.set('')  
                    iconpicker.set(get_icon)
                } 
            })()

             // $('.iconpicker-dropdown ul li').click(function(e) {
             //    alert('ok')
             // })

            // $(document).on('change', '.iconpicker', function(e){
            //     @this.set('icon', e.icon)
            // })
            document.addEventListener("DOMContentLoaded", () => {
                $(document).on('click', '.my-picker li', function(e){
                    @this.set('icon', $('#getD').val())
                })

                $(document).on('change', '#m-id', function(e){
                    @this.set('relasi_menu', $(this).val())
                })
            })


        // document.addEventListener('DOMContentLoaded', () => {
        //     $(document).on('click', '.my-picker li', function(e){  
        //         @this.set('icon', $(e.target).attr('value'))
        //     })
        // })
    </script>
    @endpush
</div>
