  <section class="bg-foreground dark:bg-muted">
      <div class="container mx-auto md:px-16 py-16 space-y-8">
          @include('web::partials.section-header', [
              'title' => [
                  ['content' => explode(' ', get_content_json($caption)[$lang]['title'])[0], 'isBold' => true],
                  ['content' => ''],
              ],
              'description' => get_content_json($caption)[$lang]['post0'],
              'isDarkBackground' => true,
          ])
          <div class="grid grid-cols-3 gap-16">
              <div class="space-y-4">
                  <h3 class="text-white text-lg font-medium">{{ get_content_json($fund_1)[$lang]['title'] }}</h3>
                  <div class="py-6" id="financial-sources-chart"></div>
              </div>
              <div class="space-y-4">
                  <h3 class="text-white text-lg font-medium">{{ get_content_json($fund_2)[$lang]['title'] }}</h3>
                  <p class="text-white text-sm">{{ get_content_json($fund_2)[$lang]['post0'] }}
                  </p>
                  <div>
                      <h2 class="font-bold text-4xl text-white text-center">10 M</h2>
                      <p class="text-sm text-white/80 text-center">Dana yang diperlukan</p>
                      <div class="grid grid-cols-2 place-items-center">
                          <div class="py-6" id="financial-alocations-chart"></div>
                          <div class="space-y-8 w-full">
                              <div class="space-y-4">
                                  <div class="flex items-center gap-4">
                                      <div class="w-6 h-2.5 bg-green-400 rounded-sm"></div>
                                      <p class="text-white text-xs">Dana Terkumpul</p>
                                  </div>
                                  <div class="grid grid-cols-[64%,36%] relative">
                                      <div class="w-full h-1 bg-green-400 rounded-l-sm"></div>
                                      <div class="w-full h-1 bg-neutral-800 dark:bg-neutral-700 rounded-r-sm"></div>
                                      <div class="text-[10px] absolute top-1 left-1 text-white/80">
                                          64%
                                      </div>
                                  </div>
                              </div>
                              <div class="space-y-4">
                                  <div class="flex items-center gap-4">
                                      <div class="w-6 h-2.5 bg-purple-400 rounded-sm"></div>
                                      <p class="text-white text-xs">Tersalurkan</p>
                                  </div>
                                  <div class="grid grid-cols-[44%,56%] relative">
                                      <div class="w-full h-1 bg-purple-400 rounded-l-sm"></div>
                                      <div class="w-full h-1 bg-neutral-800 dark:bg-neutral-700 rounded-r-sm"></div>
                                      <div class="text-[10px] absolute top-1 left-1 text-white/80">
                                          44%
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="space-y-4">
                  <h3 class="text-white text-lg font-medium">{{ get_content_json($fund_3)[$lang]['title'] }}</h3>
                  <p class="text-white text-sm">{{ get_content_json($fund_3)[$lang]['post0'] }}
                  </p>
                  <a href="{{ url(request()->bahasa . '/get-involved/donation') }}" class="block">
                      <x-web::button>Donasi</x-web::button>
                  </a>
              </div>
          </div>
      </div>
  </section>

  @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script>
          const RP = new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0
          });

          function getFinancialSourcesChartOptions() {
              return {
                  series: [35.1, 23.5, 2.4, 5.4],
                  colors: ["#4ade80", "#facc15", "#fb7185", "#8b5cf6"],
                  chart: {
                      height: 280,
                      width: "100%",
                      type: "donut",
                  },
                  stroke: {
                      colors: ["transparent"],
                      lineCap: "",
                  },
                  plotOptions: {
                      pie: {
                          donut: {
                              labels: {
                                  show: true,
                                  name: {
                                      show: true,
                                      offsetY: 20,
                                  },
                                  total: {
                                      showAlways: true,
                                      show: true,
                                      color: "white",
                                      fontSize: "12px",
                                      formatter: function(w) {
                                          const sum = w.globals.seriesTotals.reduce((a, b) => {
                                              return a + b
                                          }, 0)
                                          return RP.format(sum * 100)
                                      },
                                  },
                                  value: {
                                      show: true,
                                      offsetY: -20,
                                      fontWeight: 700,
                                      formatter: function(value) {
                                          return RP.format(value * 100)
                                      },
                                      color: "white",

                                  },
                              },
                              size: "80%",
                          },
                      },
                  },
                  grid: {
                      padding: {
                          top: -2,
                      },
                  },
                  labels: ["Donatur", "Sponsor", "Sumber 3", "Sumber 4"],
                  dataLabels: {
                      enabled: false,
                  },
                  legend: {
                      position: "bottom",
                      fontSize: "12px",
                      labels: {
                          colors: "white",
                      },
                      markers: {
                          size: 5,
                          strokeWidth: 0,
                          offsetX: -2,
                      },
                      itemMargin: {
                          horizontal: 6,
                          vertical: 4
                      },
                  },

                  yaxis: {
                      labels: {
                          formatter: function(value) {
                              return RP.format(value * 100)
                          },
                      },
                  },
                  xaxis: {
                      labels: {
                          formatter: function(value) {
                              return RP.format(value * 100)
                          },
                      },
                      axisTicks: {
                          show: false,
                      },
                      axisBorder: {
                          show: false,
                      },
                  },
              }
          }

          if (document.getElementById("financial-sources-chart") && typeof ApexCharts !== 'undefined') {
              const chart = new ApexCharts(document.getElementById("financial-sources-chart"),
                  getFinancialSourcesChartOptions());
              chart.render();
          }

          function getFinancialAlocationsChartOptions() {
              return {
                  series: [35.1, 23.5],
                  colors: ["#4ade80", "#facc15"],
                  chart: {
                      height: 180,
                      width: "100%",
                      type: "donut",
                  },
                  stroke: {
                      colors: ["transparent"],
                      lineCap: "",
                  },
                  plotOptions: {
                      pie: {
                          donut: {
                              labels: {
                                  show: true,
                                  name: {
                                      show: true,
                                      offsetY: 20,
                                  },
                                  value: {
                                      show: true,
                                      offsetY: -20,
                                      fontWeight: 700,
                                      formatter: function(value) {
                                          return RP.format(value * 100)
                                      },
                                      color: "white",
                                      fontSize: "14px",
                                  },
                              },
                              size: "80%",
                          },
                      },
                  },
                  grid: {
                      padding: {
                          top: -2,
                      },
                  },
                  labels: ["Terkumpul", "Tersalurkan"],
                  dataLabels: {
                      enabled: false,
                  },
                  legend: {
                      show: false
                  },
                  yaxis: {
                      labels: {
                          formatter: function(value) {
                              return RP.format(value * 100)
                          },
                      },
                  },
                  xaxis: {
                      labels: {
                          formatter: function(value) {
                              return RP.format(value * 100)
                          },
                      },
                      axisTicks: {
                          show: false,
                      },
                      axisBorder: {
                          show: false,
                      },
                  },
              }
          }

          if (document.getElementById("financial-alocations-chart") && typeof ApexCharts !== 'undefined') {
              const chart = new ApexCharts(document.getElementById("financial-alocations-chart"),
                  getFinancialAlocationsChartOptions());
              chart.render();
          }
      </script>
  @endpush
