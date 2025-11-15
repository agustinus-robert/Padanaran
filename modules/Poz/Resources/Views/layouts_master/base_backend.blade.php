<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Static Navigation - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ url('public/bsadmin/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@2.8.2/dist/alpine.min.js"></script>

    <link rel="stylesheet" href="{{ url('public/iconpicker/iconpicker-1.5.0.css') }}" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>



    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
</head>

<body>
    <nav class="sb-topnav navbar-expand navbar-dark bg-dark navbar">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
        <!-- Sidebar Toggle-->
        <button class="order-lg-0 me-lg-0 btn btn-link btn-sm order-1 me-4" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline me-md-3 my-md-0 my-2 me-0 ms-auto">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-md-0 me-lg-4 me-3 ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Your Site</div>
                        <a class="nav-link" href="{{ url('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-technometer"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Sites Configuration</div>
                        <a class="nav-link" href="{{ url('menu') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-window-restore"></i></div>
                            Menu
                        </a>
                        <a class="nav-link" href="{{ url('category_cfg') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-list-alt" aria-hidden="true"></i>
                            </div>
                            Category
                        </a>
                        <a class="nav-link" href="{{ url('tags_cfg') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-tags" aria-hidden="true"></i>
                            </div>
                            Tags
                        </a>
                        <div class="sb-sidenav-menu-heading">Master Category</div>
                        <?php foreach(get_category() as $kmenu => $vmenu){?>
                        <a class="nav-link" href="{{ url('category') }}/<?= $vmenu->id ?>">
                            <div class="sb-nav-link-icon"><i class="<?= $vmenu->icon ?>" aria-hidden="true"></i></div>
                            <?= $vmenu->title ?>
                        </a>
                        <?php } ?>
                        <div class="sb-sidenav-menu-heading">Master Menu</div>
                        <?php foreach(get_menu() as $kmenudata => $menudata){?>
                        <a class="nav-link" href="{{ url('posting') }}/<?= $menudata->id ?>">
                            <div class="sb-nav-link-icon"><i class="<?= $menudata->icon ?>" aria-hidden="true"></i></div>
                            <?= $menudata->title ?>
                        </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">


                    <?php if(isset($output_data['contents_php'])){ ?>
                    {!! view($output_data['contents_php'], $output_data)->render() !!}
                    <?php } else { ?>
                    @yield('konten')
                    <?php } ?>


                    <?php if(isset($output_data['content_js'])){ ?>
                    {!! view($output_data['content_js'], $output_data)->render() !!}
                    <?php } ?>
                </div>
            </main>
            <footer class="bg-light mt-auto py-4">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


    <script src="{{ url('public/iconpicker/iconpicker-1.5.0.js') }}"></script>
    <script src="{{ url('public/iconpicker/index-tooltip.js') }}"></script>

    <script>
        // Default options
        var jsonURLs = "{{ url('public/iconpicker/iconpicker-1.5.0.json') }}"
        IconPicker.Init({
            // Required: You have to set the path of IconPicker JSON file to "jsonUrl" option. e.g. '/content/plugins/IconPicker/dist/iconpicker-1.5.0.json'
            jsonUrl: jsonURLs,
            // Optional: Change the buttons or search placeholder text according to the language.
            searchPlaceholder: 'Search Icon',
            showAllButton: 'Show All',
            cancelButton: 'Cancel',
            noResultsFound: 'No results found.', // v1.5.0 and the next versions
            borderRadius: '20px', // v1.5.0 and the next versions
        });

        IconPicker.Run('#GetIconPicker');

        IconPicker.Run('#GetIconPicker', function() {

            // codes...


        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ url('public/bsadmin/js/datatables-simple-demo.js') }}"></script>


</body>

</html>
