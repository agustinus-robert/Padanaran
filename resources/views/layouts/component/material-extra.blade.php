<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Silahkan Kelola Modul Anda</h5>
          <p>Daftar modul</p>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">

      <div class="card-body pt-0">
        <div class="container-fluid px-0">

            <div class="row text-center">

                <!-- Setting -->

                <div class="col-4 border border-light">
                    <a class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5"
                    href="{{ route('core::dashboard') }}" class="menu-tile">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">settings</span>
                        <span class="menu-label" style="font-size:15px;">Setting</span>
                    </a>
                </div>

                <!-- Tata Usaha -->
                <div class="col-4 border border-light">
                    <a href="{{ route('administration::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">apartment</span>
                        <span class="menu-label" style="font-size:15px;">Administrasi</span>
                    </a>
                </div>

                <!-- Guru -->
                <div class="col-4 border border-light">
                    <a href="{{ route('teacher::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">menu_book</span>
                        <span class="menu-label" style="font-size:15px;">Guru</span>
                    </a>
                </div>

                <!-- Akademik -->
                <div class="col-4 border border-light">
                    <a href="{{ route('academic::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">school</span>
                        <span class="menu-label" style="font-size:15px;">Akademik</span>
                    </a>
                </div>

                <!-- Konseling -->
                <div class="col-4 border border-light">
                    <a href="{{ route('counseling::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">record_voice_over</span>
                        <span class="menu-label" style="font-size:15px;">Konseling</span>
                    </a>
                </div>

                <!-- HRMS -->
                <div class="col-4 border border-light">
                    <a href="{{ route('hrms::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">badge</span>
                        <span class="menu-label" style="font-size:15px;">HRMS</span>
                    </a>
                </div>

                <!-- MSDM -->
                <div class="col-4 border border-light">
                    <a href="{{ route('portal::dashboard-msdm.index') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">captive_portal</span>
                        <span class="menu-label" style="font-size:15px;">Portal</span>
                    </a>
                </div>

                <!-- Finance -->
                <div class="col-4 border border-light">
                    <a href="{{ route('finance::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">payments</span>
                        <span class="menu-label" style="font-size:15px;">Finance</span>
                    </a>
                </div>

                <!-- Pondok -->
                <div class="col-4 border border-light">
                    <a href="{{ route('boarding::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">home_work</span>
                        <span class="menu-label" style="font-size:15px;">Pondok</span>
                    </a>
                </div>

            </div>
        </div>

        <hr class="horizontal dark my-sm-2>›

        <div class="row">
            <div class="col-12">
                <a href="{{ route('account::user.profile') }}"
                    class="btn bg-gradient-info  d-flex align-items-center gap-2 bg-body-secondary rounded">
                        <span class="material-symbols-rounded fs-4">account_circle</span>
                        <span class="menu-label" style="font-size:15px;">Profil anda</span>
                </a>
            </div>

            <div class="col-12">
                <a href="javascript:void(0)" onclick="signout();"
                    class="btn bg-gradient-primary d-flex align-items-center gap-2 bg-body-secondary rounded">
                        <span class="material-symbols-rounded fs-4">logout</span>
                        <span class="menu-label" style="font-size:15px;">Keluar</span>
                </a>
            </div>
        </div>
    </div>

    </div>
  </div>

<script src="{{ asset('material/js/core/popper.min.js') }}"></script>
<script src="{{ asset('material/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('material/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('material/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{ asset('material/js/plugins/chartjs.min.js')}}"></script>

@auth
    @if (Route::has(config('modules.auth.signout.route')))
        <script>
            const signout = (e) => {
                if (confirm('Apakah Anda yakin?')) {
                    document.getElementById('signout-form').submit();
                }
            }
        </script>
        <form class="form-block form-confirm" id="signout-form" action="{{ route(config('modules.auth.signout.route')) }}" method="POST" style="display: none;"> @csrf
        </form>
    @endif
@endauth

<script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
          label: "Views",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#43A047",
          data: [50, 45, 22, 28, 50, 60, 76],
          barThickness: 'flex'
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: '#e5e5e5'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
              color: "#737373"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    // var ctx2 = document.getElementById("chart-line").getContext("2d");

    // new Chart(ctx2, {
    //   type: "line",
    //   data: {
    //     labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
    //     datasets: [{
    //       label: "Sales",
    //       tension: 0,
    //       borderWidth: 2,
    //       pointRadius: 3,
    //       pointBackgroundColor: "#43A047",
    //       pointBorderColor: "transparent",
    //       borderColor: "#43A047",
    //       backgroundColor: "transparent",
    //       fill: true,
    //       data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220],
    //       maxBarThickness: 6

    //     }],
    //   },
    //   options: {
    //     responsive: true,
    //     maintainAspectRatio: false,
    //     plugins: {
    //       legend: {
    //         display: false,
    //       },
    //       tooltip: {
    //         callbacks: {
    //           title: function(context) {
    //             const fullMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    //             return fullMonths[context[0].dataIndex];
    //           }
    //         }
    //       }
    //     },
    //     interaction: {
    //       intersect: false,
    //       mode: 'index',
    //     },
    //     scales: {
    //       y: {
    //         grid: {
    //           drawBorder: false,
    //           display: true,
    //           drawOnChartArea: true,
    //           drawTicks: false,
    //           borderDash: [4, 4],
    //           color: '#e5e5e5'
    //         },
    //         ticks: {
    //           display: true,
    //           color: '#737373',
    //           padding: 10,
    //           font: {
    //             size: 12,
    //             lineHeight: 2
    //           },
    //         }
    //       },
    //       x: {
    //         grid: {
    //           drawBorder: false,
    //           display: false,
    //           drawOnChartArea: false,
    //           drawTicks: false,
    //           borderDash: [5, 5]
    //         },
    //         ticks: {
    //           display: true,
    //           color: '#737373',
    //           padding: 10,
    //           font: {
    //             size: 12,
    //             lineHeight: 2
    //           },
    //         }
    //       },
    //     },
    //   },
    // });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

    new Chart(ctx3, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Tasks",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: "#43A047",
          pointBorderColor: "transparent",
          borderColor: "#43A047",
          backgroundColor: "transparent",
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [4, 4],
              color: '#e5e5e5'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#737373',
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [4, 4]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('material/js/material-dashboard.js')}}"></script> 
