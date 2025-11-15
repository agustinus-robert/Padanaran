<div>
    <main>
        <div class="container-fluid px-4">
         
            <h1 class="mt-4">Posting Of Data</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item active">Posting Of Data</li>
            </ol>
         

            <div class="card shadow-none border my-4">
                <div class="card-header p-4 border-bottom bg-body">
                    <h4 class="text-body mb-0"><i class="far fa-file-alt" aria-hidden="true"></i> Your Post</h4>
                </div>

                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <button wire:click="checkSession($event.target.value)">Test</button>
                            </div>
                        </div>
                    </div>
 --}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select wire:change="selectlang($event.target.value)" id="langs" class="form-control ml-2 mb-2" wire:model.lazy="language">
                                    <option value="id">Indonesia</option>
                                    <option value="eng">English</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                        
                        <div class="form-group">
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
                                    $label_value = $value_pc['ft'.$replace_field]; ?>

                            <div class="form-group mt-2">
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

                        <div class="col-md-3">
                            <div class="form-group" wire:ignore>
                                <label class="form-label"><b>Image Upload</b></label>
                                <input type="file"  data-allowed-file-extensions="jpg jpeg png" class="dropify" name="dropify_data" data-default-file="{{!empty($data_id) ? Storage::url($sh_photo) : ''}}" />
                            </div>

                            <div class="form-group">
                                <label class="form-label"><b>Image description<b></label>
                                <textarea class="form-control" wire:model="image_desc"></textarea>
                            </div>

                            <?php 
                          
                            foreach($data as $index => $value){ 
                                    if($index == 'taxonomy_code'){
                                    $i = 0;
                                    foreach(json_decode($value, true) as $index_pc => $value_pc){
                                        $replace_field = str_replace("fieldtaxo_","", $index_pc);
                                        $name = "category.post".$i++; 
                                        $label_value = $value_pc['ft_taxo'.$replace_field]; 
                                        $taxo_cat = $value_pc['fy_taxo'.$replace_field]; ?>
                                
                                <div class="form-group mt-3">
                                    <label class="form-label"><?=$label_value?></label>

                                    <select class="form-control" wire:model="<?=$name?>">
                                        <option value="">Silahkan Pilih <?=$label_value?></option>
                                        <?php foreach(category_data($taxo_cat) as $index_dt => $value_dt){ ?>
                                        <option value="<?=$value_dt->id?>"><?=$value_dt->title?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } 
                                }
                            }
                            ?>
                        </div>
                    </div>
                   
                </div>

                <div class="card-footer">
                      <input type="button" class="btn btn-primary" wire:click="submitForm" value="save">
                </div>
            </div>
             <?php if(Session::get('tema') == 'bootstrap'){ ?>

            <?php } else { ?>
                </div>
            <?php } ?>
        </div>
    </main>l
    
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
                    default: 'Upload Image',
                    replace: 'Replace Image',
                    remove: 'Remove Image',
                    error: 'Error Image'
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
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
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
