<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Posting Of Data</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Posting Of Data</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="content flex-column-fluid" id="kt_content">
        <div class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Pilihan Bahasa</h2>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                        </div>
                        <!--begin::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Select2-->
                        <select wire:change="selectlang($event.target.value)" id="langs" wire:model.lazy="language" class="form-select mb-2">
                            <option value="id">Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    <!--end::Card body-->
                </div>

                @if(count($manyArr) > 0)
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Form Tambahan</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                            </div>
                            <!--begin::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Select2-->
                            <select wire:change="selectForm($event.target.value)" id="langs" wire:model.lazy="selectFormAdd" class="form-select mb-2">
                                <option value="">Pilih Form</option>
                                @foreach($allForm as $key => $val)
                                    @if(isset($this->manyArr[$val->menu_id]))
                                        <option value="{{$val->id}}">{{$val->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <!--end::Card body-->
                    </div>
                @endif

                <?php
                    foreach($data as $index => $value){
                        if($index == 'taxonomy_code'){
                        $i = 0;
                        foreach(json_decode($value, true) as $index_pc => $value_pc){
                            $replace_field = str_replace("fieldtaxo_","", $index_pc);
                            $name = "category.post".$i++;
                            $label_value = $value_pc['ft_taxo'.$replace_field];
                            $taxo_cat = $value_pc['fy_taxo'.$replace_field]; ?>

                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2><?=$label_value?></h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                                    </div>
                                    <!--begin::Card toolbar-->
                                </div>

                                <div class="card-body pt-0">
                                    <select class="form-select" wire:model="<?=$name?>">
                                        <option value="">Silahkan Pilih <?=$label_value?></option>
                                        <?php foreach(category_data($taxo_cat) as $index_dt => $value_dt){ ?>
                                        <option value="<?=$value_dt->id?>"><?=$value_dt->title?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>

                <?php }
                    }
                }
                ?>
            </div>





            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="card shadow-none border">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>General</h2>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <div class="form-group mb-10">
                                <label class="form-label"><b><?=($this->language == 'id' ? 'Judul' : 'Title')?></b></label>
                                <input wire:change="helperlanguage($event.target.value)" class="form-control" type="text" wire:model.lazy="content.title" />
                            </div>

                            <div>

                            <?php

                            $type = '';
                            $name = '';
                            $label_value  = '';

                            foreach($data as $index => $value){


                            if($index == 'post_code'){
                                    $i = 0;
                                    foreach(json_decode($value, true) as $index_pc => $value_pc){
                                        $replace_field = str_replace("field_","", $index_pc);
                                        $name = "content.post".$i++;
                                        $label_value = $value_pc['ft'.$replace_field];
                                        ?>

                                <div class="form-group mt-2 mb-10">
                                    <?php if(isset($this->language)){ ?>
                                    <label class="form-label"><b><?=$label_value[$this->language]?></b></label>
                                    <?php } else if(isset($_COOKIE['k_language'])){ ?>
                                    <label class="form-label"><b><?=$label_value[$_COOKIE['k_language']]?></b></label>
                                    <?php } else {?>
                                    <label class="form-label"><b><?=$label_value[$this->language]?></b></label>
                                    <?php } ?>

                                    <?php if($value_pc['fy'.$replace_field] == 'raw_text'){ ?>
                                        <input wire:change="helperlanguage($event.target.value)" wire:key="<?=$name?>"  type="text" class="form-control" wire:model.lazy="<?=$name?>" />
                                    <?php } else if($value_pc['fy'.$replace_field] == 'editor'){ ?>
                                        <div wire:ignore>
                                            <textarea id="{{str_replace('content.', 'mce', $name)}}" class="form-control"></textarea>
                                        </div>
                                    <?php } else if($value_pc['fy'.$replace_field] == 'gutenberg') { ?>
                                        <div wire:ignore>
                                        <textarea id="<?=str_replace('content.','edt',$name)?>"  name="<?=str_replace('content.','',$name)?>" class="form-control"></textarea>
                                        </div>
                                    <?php } else if($value_pc['fy'.$replace_field] == 'textarea'){ ?>
                                        <textarea wire:change="helperlanguage($event.target.value)" wire:key="<?=$name?>" class="form-control" wire:model.lazy="<?=$name?>" /></textarea>
                                    <?php } else if($value_pc['fy'.$replace_field] == 'currency'){ ?>
                                        <input wire:key="<?=$name?>" wire:change="moneys($event.target.value, '{{$name}}')"  type="text" class="form-control" wire:model.lazy="<?=$name?>" />
                                    <?php } else if($value_pc['fy'.$replace_field] == 'date'){?>
                                        <input wire:key="<?=$name?>"  type="date" class="form-control" wire:model.lazy="<?=$name?>" />
                                    <?php } ?>
                                </div>
                            <?php
                                    }
                                }
                            }
                            ?>

                            <?php if($data->type == 4){ ?>

                            <?php } ?>
                          {{--   <div class="form-group" wire:ignore>
                                <label>Tags</label>
                                <input type="text" class="form-control" name="post_tags" />
                            </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="card card-flush border py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Media</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="fv-row mb-2">
                            <!--begin::Dropzone-->
                            <div wire:ignore>
                                <?php if($data->type == 6){ ?>

                                <input type="file"  data-allowed-file-extensions="pdf docx xls" class="dropify" name="dropify_data" data-default-file="{{!empty($data_id) ? asset(Storage::disk('public')->url($sh_photo)) : ''}}" />
                                <?php } else { ?>
                                    <input type="file"  data-allowed-file-extensions="jpg jpeg png" class="dropify" name="dropify_data" data-default-file="{{!empty($data_id) ? asset(Storage::disk('public')->url($sh_photo)) : ''}}" />
                                <?php } ?>
                            </div>
                            <!--end::Dropzone-->
                        </div>

                        <div class="text-muted fs-7">Set your media here.</div>
                    </div>
                    <!--end::Card header-->
                </div>
            </div>

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                 <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Media Description</h2>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="fv-row mb-2">
                            <textarea class="form-control" wire:change="helperlanguage($event.target.value)" wire:model.lazy="content.media_description"></textarea>
                        </div>

                        <div class="text-muted fs-7">Set your description of your media here.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                 <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Meta Description</h2>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="fv-row mb-2">
                            <textarea class="form-control" wire:change="helperlanguage($event.target.value)" wire:model="content.meta_description"></textarea>
                        </div>

                        <div class="text-muted fs-7">Masukkan meta description</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-8">
        <button wire:click="submitForm" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
            <span class="indicator-label">Save Changes</span>
        </button>
    </div>
</div>


<script type="text/javascript">

    let string_cookies = ''
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

    $(document).ready(function(){
        $('.dropify').dropify({
                messages: {
                    default: 'Upload File',
                    replace: 'Replace File',
                    remove: 'Remove File',
                    error: 'Error File'
                }
            }
        );

    })


    $(document).on('change', '.dropify', function(event) {
        //console.log(event.target.files[0])

        @this.upload('dropify', event.target.files[0])
    })

    <?php
    $name = '';
    foreach($data as $index => $value){
        if($index == 'post_code'){
            $i = 0;
            foreach(json_decode($value, true) as $index_pc => $value_pc){
                $replace_field = str_replace("field_","", $index_pc);
                $name = "content.post".$i;
                $objs = "content.post".$i.'.';
                $i++;
                if($value_pc['fy'.$replace_field] == 'gutenberg') {
     ?>
        $(document).ready(function(){

    <?php if(!empty($data_id)){ ?>
        amsifySuggestags = new AmsifySuggestags($('input[name="post_tags"]'));
        amsifySuggestags._init();
        <?php
            if(is_string($tags)){
                if( strpos($tags, ',') !== false ) {
                foreach(explode(',', $tags) as $index2 => $value2){
        ?>
            amsifySuggestags.addTag('<?=$value2?>');
        <?php
                    }
                } else {
            if(strpos($tags, ',') === false){
            ?>
            amsifySuggestags.addTag('<?=$tags?>');
        <?php
                    }
                }
            }
        ?>
    <?php } ?>

        function get_value_tags(){
            return $('input[name="post_tags"]').val();
        }

        $('input[name="post_tags"]').amsifySuggestags({
            type : 'amsify',
            afterAdd: function(value) {
            // after add

            @this.set('tags', get_value_tags())
            },
            afterRemove: function(value) {
            // after remove
            @this.set('tags', get_value_tags())
            },
        });

       var APP_URL = "{{url('/')}}"

        class LaravelFilemanager extends Laraberg.wordpress.element.Component {
    constructor (props) {
        super(props)
        this.state = {
            media: []
        }
    }

    getMediaType = (path) => {
        const video = ['mp4', 'm4v', 'mov', 'wmv', 'avi', 'mpg', 'ogv', '3gp', '3g2']
        const audio = ['mp3', 'm4a', 'ogg', 'wav']
        const extension = path.split('.').slice(-1).pop()
        if (video.includes(extension)) {
            return 'video'
        } else if (audio.includes(extension)) {
            return 'audio'
        } else {
            return 'image'
        }
    }

    onSelect = (url, path) => {
        this.props.value = null
        const { multiple, onSelect } = this.props
        const media = {
            url: url,
            type: this.getMediaType(path)
        }
        if (multiple) { this.state.media.push(media) }
        onSelect(multiple ? this.state.media : media)
    }

    openModal = () => {
        let type = 'file'
        if (this.props.allowedTypes.length === 1 && this.props.allowedTypes[0] === 'image') {
            type = 'image'
        }
        this.openLFM(type, this.onSelect)
    }

    openLFM = (type, cb) => {
        const routePrefix = `${APP_URL}/laravel-filemanager`
        window.open(routePrefix, 'FileManager', 'width=900,height=600')
        window.SetUrl = function (items) {
            if (items[0]) {
                cb(items[0].url, items[0].name)
            }
        }
    }

    render () {
        const { render } = this.props
        return render({ open: this.openModal })
    }
}

Laraberg.wordpress.hooks.addFilter(
    'editor.MediaUpload',
    'core/edit-post/components/media-upload/replace-media-upload',
    () => LaravelFilemanager
)

elementRendered('.components-form-file-upload button', element => element.remove())

function elementRendered (selector, callback) {
    const renderedElements = []
    const observer = new MutationObserver((mutations) => {
        const elements = document.querySelectorAll(selector)
        elements.forEach(element => {
            if (!renderedElements.includes(element)) {
                renderedElements.push(element)
                callback(element)
            }
        })
    })
    observer.observe(document.documentElement, { childList: true, subtree: true })
    return observer
}

const mediaUpload = ({filesList, onFileChange}) => {}

        Laraberg.init('<?=str_replace('content.','edt',$name)?>', {mediaUpload, sidebar: false })

        $('#'+'<?=str_replace('content.','edt',$name)?>').on('keyup, change', function () {
            var txt = $('#'+'<?=str_replace('content.','edt',$name)?>').text()
            @this.set('<?=$name?>', txt)
        });
        })
    <?php
                    } else if($value_pc['fy'.$replace_field] == 'editor') { ?>
         var editor_config = {
            path_absolute : "{{url('/')}}",
            entity_encoding: "raw",
            selector: "#<?=str_replace('.', '', str_replace('content.','mce',$name))?>",
            relative_urls: false,
            filemanager_path: '/path/to/filemanager/filemanager/dialog.php?type=1&editor=tinymce&fldr=',
              filemanager_title: 'File Manager',  // Judul File Manager
              external_filemanager_path: '/path/to/filemanager/',
              external_plugins: {
                'filemanager': '/path/to/filemanager/filemanager/plugin.min.js'
            },
             plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export code',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | code',
            file_picker_callback : function(callback, value, meta) {
              var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
              var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

              var cmsURL = editor_config.path_absolute + '/laravel-filemanager?editor=' + meta.fieldname;
              if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
              } else {
                cmsURL = cmsURL + "&type=Files";
              }

              tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'Filemanager',
                width : x * 0.9,
                height : y * 0.9,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                  callback(message.content);
                },
              });
            },
            setup: function(ed) {
                 ed.on("keyup", (e) => {
                    @this.set('<?=$name?>', ed.getContent())
                    {{ $this->helperlanguage('',request()) }}
                  });

                <?php if(!empty($data_id)){ ?>
                ed.on('init', function (e) {
                    <?php
                        @$explodes = explode('.', str_replace('content.', '', $name));
                       // dd($explodes);
                    ?>
                    ed.setContent('<?=preg_replace("/[\n\r|\r\n|\r|\n\']/m", "", $content[$explodes[0]])?>');
                });
            <?php } else { ?>
                ed.on('init', function(e){
                    <?php
                        @$explodes = explode('.', str_replace('content.', '', $name));
                    ?>
                    ed.setContent('<?=preg_replace("/[\n\r|\r\n|\r|\n\']/m", "", @$content[$explodes[0]])?>');
                })

                <?php } ?>
            },
            skin: 'tinymce-5',
        };
            tinymce.init(editor_config)






            // var iframeDocument = fr.contentWindow.document;
            // iframeDocument.addEventListener('keydown', function(e) {

            // })
        // $(document).on('keyup','#mcepost0id_ifr', function(e) {
        //     console.log('<?=$name?>')
        // });






    <?php            }
                }
            }
        }
     ?>



  $(document).on('change', '#langs', function(){
    for (let i = 0; i < document.cookie.split(";").length; i++) {
        if(document.cookie.split(";")[i].indexOf('_x_content') > -1){
           string_cookies = document.cookie.split(";")[i]
        }
    }
        console.log(string_cookies.replace('_x_content=',''))
        //console.log(JSON.parse())
        //tinymce.get('mcepost0eng').setContent('<p>This is my new content!</p>');
})


</script>

@script
    <script>
    $wire.on('helper', (event) => {
        if(event.length > 0){
            for (var data in event[0]) {
                let tinmyMceInstance = tinymce.get('mcepost'+data);

                if( tinmyMceInstance !== null ){
                    tinymce.get('mcepost'+data).setContent(event[0][data]);
                }
            }
        }
    })
    </script>
@endscript
