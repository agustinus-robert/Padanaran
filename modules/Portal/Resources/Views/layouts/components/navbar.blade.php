 <ul class="nav-main nav-main-horizontal nav-main-hover nav-main-dark">
     <li class="nav-main-item">
         <a class="nav-main-link active" href="{{ route('portal::dashboard.index') }}">
             <i class="nav-main-link-icon fa fa-chart-pie"></i>
             <span class="nav-main-link-name">Dashboard</span>
         </a>
     </li>

     {{-- <li class="nav-main-item">
         <a class="nav-main-link" href="{{ route('editor::editor.editor') }}">
             <i class="nav-main-link-icon fas fa-coffee"></i>
             <span class="nav-main-link-name">Buat Cerita Anda</span>
         </a>
     </li> --}}

     <li class="nav-main-item">
         <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
             <i class="nav-main-link-icon fa fa-sync-alt"></i>
             <span class="nav-main-link-name">Kelola</span>
         </a>
         <ul class="nav-main-submenu">
             <li class="nav-main-item">
                 <a class="nav-main-link" href="{{ route('portal::outlet.manage-outlet.index') }}">
                     <span class="nav-main-link-name">Outlet</span>
                 </a>
             </li>
         </ul>
     </li>


     <li class="nav-main-item ms-lg-auto">
     <li class="nav-main-item">
         <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
             <i class="nav-main-link-icon fa fa-globe"></i>
             <span class="nav-main-link-name">Outlet Anda</span>
         </a>
         <ul class="nav-main-submenu">
             @if (outletList(Auth::user()->id)->count() > 0)
                 @foreach (outletList(Auth::user()->id) as $value)
                     <li class="nav-main-item">
                         <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                             <span class="nav-main-link-name">{{ $value->name }}</span>
                         </a>
                         <ul class="nav-main-submenu">
                             <li class="nav-main-item">
                                 <a class="nav-main-link" href="">
                                     <i class="nav-main-link-icon fa fa-coffee"></i>
                                     <span class="nav-main-link-name">Profil</span>
                                 </a>
                             </li>
                             <li class="nav-main-item">
                                 <a class="nav-main-link" href="">
                                     <i class="nav-main-link-icon fa fa-users"></i>
                                     <span class="nav-main-link-name">Pegawai</span>
                                 </a>
                             </li>
                             <li class="nav-main-item">
                                 <a class="nav-main-link" href="">
                                     <i class="nav-main-link-icon fa fa-file-alt"></i>
                                     <span class="nav-main-link-name">Laporan Penjualan</span>
                                 </a>
                             </li>
                             <li class="nav-main-item">
                                 <a class="nav-main-link" href="{{ route('poz::dashboard') }}?outlet={{ $value->id }}">
                                     <i class="nav-main-link-icon fas fa-store-alt"></i>
                                     <span class="nav-main-link-name">Kunjungi Outlet</span>
                                 </a>
                             </li>
                         </ul>
                     </li>
                 @endforeach
             @else
                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
                         <span class="nav-main-link-name">Belum ada Outlet</span>
                     </a>
                 </li>
             @endif
         </ul>
     </li>

     <li class="nav-main-item">
         {{-- <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
             <i class="nav-main-link-icon fa fa-brush"></i>
             <span class="nav-main-link-name">Themes</span>
         </a> --}}
         <ul class="nav-main-submenu nav-main-submenu-right">
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="default" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-default"></i>
                     <span class="nav-main-link-name">Default</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xwork"></i>
                     <span class="nav-main-link-name">xWork</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xmodern"></i>
                     <span class="nav-main-link-name">xModern</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xeco"></i>
                     <span class="nav-main-link-name">xEco</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xsmooth"></i>
                     <span class="nav-main-link-name">xSmooth</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xinspire"></i>
                     <span class="nav-main-link-name">xInspire</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xdream"></i>
                     <span class="nav-main-link-name">xDream</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xpro"></i>
                     <span class="nav-main-link-name">xPro</span>
                 </a>
             </li>
             <li class="nav-main-item">
                 <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#">
                     <i class="nav-main-link-icon fa fa-circle text-xplay"></i>
                     <span class="nav-main-link-name">xPlay</span>
                 </a>
             </li>
         </ul>
     </li>
 </ul>
