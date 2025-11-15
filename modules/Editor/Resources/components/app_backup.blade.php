<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pemad Admin CMS - {{date('Y')}}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{url('public/favicon_io/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{url('public/favicon_io/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('public/favicon_io/favicon-16x16.png')}}">
    <link rel="manifest" href="{{url('public/favicon_io/site.webmanifest')}}">

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ url('public/timepicker/jquery.datetimepicker.min.css') }}" />

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <!-- Custom styles for this template-->
    <link href="{{url('public/bsadmin4/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
    <link rel="stylesheet" href="{{url('public/icpc/css/bootstrap-iconpicker.min.css')}}" />
    <link href="{{url('public/amsify/css/amsify.suggestags.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/example/tag-basic-style.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />
   <!--  <link href="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/skins/ui/oxide/content.min.css" rel="stylesheet"> -->

<!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
   

     <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js"></script>
     <script src="{{url('public/moment/moment.js')}}"></script>
     <script src="{{url('public/moment/moment-with-locales.js')}}"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
     <script src="https://cdn.tiny.cloud/1/7jc8msr0tnfjp8i8cs2ohsuaqrfdsbwgfncvnawv9i3o41ev/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
     <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="{{url('public/vendor/laraberg/css/laraberg.css')}}">
    <link rel="stylesheet" href="{{ url('public/vendor/file-manager/css/file-manager.css') }}">


    <style>
        @media (min-width: 768px){
            .modal-xl{
                width: 90%;
                max-width: 1200px;
            }
        }

        .no-borders{
            border: 0;
            box-shadow: none;
        }
    </style>

        
       
    @livewireStyles
</head>

<body id="page-top">
    <?php 
        if(!isset($_COOKIE['k_language'])){
            $cookie_name = "k_language";
            $cookie_value = "id";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
    ?>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Pemad CMS <sup>v1 <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">Multilanguage</span></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php if(@$_COOKIE['k_status'] == '2'){ ?>
                <!-- Nav Item - Pages Collapse Menu -->
                <?php 
                $kount_menu = json_decode(get_menu_order(), false);
                   foreach($kount_menu as $index => $val){
                    if(isset($val->children) && isset(get_needed($val->id)[0]->type)){


                ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo<?=$val->id?>"
                        aria-expanded="true" aria-controls="collapseTwo<?=$val->id?>">
            
                        <?php if(isset(get_needed(@$val->id)[0]->icon)){ ?>
                            <i class="<?=get_needed(@$val->id)[0]->icon?>"></i>
                        <?php } ?>

                        <span><?php if(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '1'){ ?>
                                <?=json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']]?>
                        <?php } else if(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '5'){ ?>
                                <?=json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']]?>
                        <?php } ?>
                        </span>
                    </a>
                    <div id="collapseTwo<?=$val->id?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">List of :</h6>

                            <?php if(isset($val->children)){
                                foreach($val->children as $indext => $valuet){
                             ?>
                             <?php if(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '2'){ ?>
                                    <a class="collapse-item" href="{{url('posting')}}/<?=$valuet->id?>">
                                        <i class="<?=get_needed($valuet->id)[0]->icon?>"></i>

                                    <?=json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']]?></a>
                             <?php  } else if(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '3'){ ?>
                                    <a class="collapse-item" href="{{url('category')}}/<?=$valuet->id?>">
                                        <i class="<?=get_needed($valuet->id)[0]->icon?>"></i><?=get_needed($valuet->id)[0]->title?></a>
                             <?php   } else if(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '4'){ ?>
                                    <a class="collapse-item" href="{{url('posting')}}/<?=$valuet->id?>">
                                        <i class="<?=get_needed($valuet->id)[0]->icon?>"></i><?=json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']]?></a>
                            <?php } else if(isset(get_needed($valuet->id)[0]->type) && get_needed($valuet->id)[0]->type == '5'){ ?>
                                    <a class="collapse-item" href="{{url(get_menu_id($valuet->id)->custom_links)}}">
                                        <i class="<?=get_needed($valuet->id)[0]->icon?>"></i><?=json_decode(get_needed($valuet->id)[0]->title, true)[@$_COOKIE['k_language']]?></a> 
                            <?php } 
                                }
                            }
                            ?>
                            
                        </div>
                    </div>

                </li> 
            <?php } else { ?>
                <li class="nav-item">
                <?php if(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type != '5' ){ ?>
                <a class="nav-link" href="{{url('posting')}}/<?=$val->id?>">
                    <i class="<?=@get_needed($val->id)[0]->icon?>" aria-hidden="true"></i>
                    <?=json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']]?>
                </a>
                <?php } else if(isset(get_needed($val->id)[0]->type) && get_needed($val->id)[0]->type == '5' ){  ?>
                    <a class="nav-link" href="{{url(get_menu_id($val->id)->custom_links)}}">
                    <i class="<?=@get_needed($val->id)[0]->icon?>" aria-hidden="true"></i>
                    <?=json_decode(get_needed($val->id)[0]->title, true)[@$_COOKIE['k_language']]?>
                </a>
                <?php } ?>
            </li>
            <?php } ?>
        
        <?php } ?>

            <?php } else { ?>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{url('dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->


            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> -->


            <div class="sidebar-heading">Sites Configuration</div>
            <li class="nav-item">
                <a class="nav-link" href="{{url('menu')}}">
                    <i class="fa fa-outdent" aria-hidden="true"></i>
                    Menu Builder
                </a>
            </li>

           
            <li class="nav-item">
                <a class="nav-link" href="{{url('order')}}">
                    <i class="fa fa-sort" aria-hidden="true"></i>
                    Menu Order
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('role')}}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    Menu Role
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Taxonomy Configuration</div>
           
            <li class="nav-item">
                <a class="nav-link" href="{{url('category_cfg')}}">
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    Category
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('tags_cfg')}}">
                    <i class="fa fa-tags" aria-hidden="true"></i>
                    Tags
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Features</div>
           
            <li class="nav-item">
                <a class="nav-link" href="{{url('contact')}}">
                    <i class="fas fa-address-book" aria-hidden="true"></i>
                    Contact
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('newssub')}}">
                    <i class="fas fa-book" aria-hidden="true"></i>
                    Newslatter & Subscription
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('site_configuration')}}">
                    <i class="fas fa-laptop" aria-hidden="true"></i>
                    Site Configuration
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Report Resources</div>



            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                    <i class="fas fa-chart-pie"></i>
                    <span>User Statistic</span>
                </a>
                <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar" style="">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="{{url('log_user')}}"><i class="fas fa-id-card-alt" aria-hidden="true"></i> User Log</a>
                        <a class="collapse-item" href="{{url('activity_log')}}"><i class="far fa-keyboard" aria-hidden="true"></i> Activity Log</a>
                    </div>
                </div>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePost" aria-expanded="false" aria-controls="collapsePost">
                    <i class="fas fa-chart-area"></i>
                    <span>Post Statistic</span>
                </a>
                <div id="collapsePost" class="collapse" aria-labelledby="headingPost" data-parent="#accordionSidebar" style="">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Posting Category</a>
                        <a class="collapse-item" href="cards.html">Posting Image</a>
                    </div>
                </div>
            </li> -->


            

        <?php } ?>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                   

                    <form class="col-md-1">
                            <select class="form-control" id="change_page">
                                <option <?=(@$_COOKIE['k_status'] == '1' ? 'selected' : '')?> value="1">Admin</option>
                                <option <?=(@$_COOKIE['k_status'] == '2' ? 'selected' : '')?> value="2">User</option>
                            </select>
                    </form>

                    <div class="col-md-1">
                        <select class="form-control" id="change_language">
                            <option <?=(@$_COOKIE['k_language'] == 'id' ? 'selected' : '')?> value="id">Indonesia</option>
                            <option <?=(@$_COOKIE['k_language'] == 'eng' ? 'selected' : '')?> value="eng">English</option>
                        </select>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
            

                        <!-- Nav Item - Messages -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Developer</span>
                                <img class="img-profile rounded-circle"
                                    src="{{url('public/bsadmin4/img/bird.png')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if(isset($output_data['contents_php'])){ ?>
                         {!! view($output_data['contents_php'], $output_data)->render() !!}
                    <?php } else { ?>
                          @yield('konten')
                    <?php } ?>

                       
                    <?php if(isset($output_data['content_js'])){ ?>
                        {!! view($output_data['content_js'], $output_data)->render() !!}
                    <?php } ?>    
                </div>

                        <!-- Pending Requests Card Example -->
                <!-- /.container-fluid -->
                <!-- <div id="fm" style="height: 600px;"></div> -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <b>Pemad CMS v1</b> <?=date('Y')?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

              
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="https://unpkg.com/react@17.0.2/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17.0.2/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix/dist/AIO/notiflix-aio-1.9.1.min.js"></script>

    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{url('public/amsify/js/jquery.amsify.suggestags.js')}}"></script>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{{url('public/icpc/js/bootstrap-iconpicker.bundle.min.js')}}"></script>     
    <script src="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/tagging.min.js"></script>
       
    <!-- Core plugin JavaScript-->
    <script src="{{url('public/bsadmin4/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{url('public/bsadmin4/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{url('public/bsadmin4/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{url('public/vendor/file-manager/js/file-manager.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/timepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>

    <script type="text/javascript" src="{{ url('public/timepicker/timepickers.js') }}"></script>


 
    <!-- Page level custom scripts -->
   <!--  <script src="{{url('public/bsadmin4/vendor/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{url('public/bsadmin4/vendor/js/demo/chart-pie-demo.js')}}"></script>
 -->
    <script src="{{ url('public/vendor/laraberg/js/laraberg.js') }}"></script>
    <!-- Page level custom scripts -->
   
    <script type="text/javascript">
        (function(){
          $('#msbo').on('click', function(){
            $('body').toggleClass('msb-x');
          });

         

          $('#change_page').on('change', function(){
            cout = 10000
            expires = "; expires=" + cout;
            document.cookie = 'k_language'+' = '+$('#change_language').val()+ expires + "; path=/";

            if($(this).val() == '1'){
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_status'+' = '+'1'+ expires + "; path=/";

                location.reload()
            } else {
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_status'+' = '+'2'+ expires + "; path=/";

                location.reload()
            }
          })

            

          $('#change_language').on('change', function(){
            if($(this).val() == 'id'){
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_language'+' = '+'id'+ expires + "; path=/";

                location.reload()
            } else {
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_language'+' = '+'eng'+ expires + "; path=/";

                location.reload()
            }
          })

        }());
    </script>

     @livewireScripts
    <script src="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <!-- <script src="{{url('public/bsadmin4/js/datatables-demo.js')}}"></script>
 -->
</body>

</html>