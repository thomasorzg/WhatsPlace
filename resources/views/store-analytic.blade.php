@extends('layouts.admin')
@section('page-title')
    {{__('Store Analytics')}}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 float-left">{{__('Visitor')}}</h6>
                    <span class="mb-0 float-right">{{__('Last 15 Days')}}</span>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div id="apex-storedashborad" data-color="primary" data-height="280"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-md-6 col-sm-12">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 float-left">{{('Top URL')}}</h6>
                    <span class="mb-0 float-right">{{__('Top') .'5'. __('URL')}}</span>
                </div>
                <div class="card-body">
                    @foreach($visitor_url as $url)
                        <div class="row mb-3">
                            <div class="col-9">
                                <div class="progress-wrapper">
                                    <span class="progress-label text-muted text-sm">{{$url->url}}</span>
                                </div>
                            </div>
                            <div class="col-3 align-self-end text-right">
                                <span class="h6 mb-0">{{$url->total}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-sm-12">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 float-left">{{('Platform')}}</h6>
                    <span class="mb-0 float-right">{{__('Last 15 Days')}}</span>
                </div>
                <div id="user_platform-chart" data-color="primary"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-md-12 col-sm-12">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 float-left">{{__('Device')}}</h6>
                    <span class="mb-0 float-right">{{__('Last 15 Days')}}</span>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div id="pie-storedashborad" data-color="primary" data-height="280"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12 col-sm-12">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0 float-left">{{__('Browser')}}</h6>
                    <span class="mb-0 float-right">{{__('Last 15 Days')}}</span>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div id="pie-storebrowser" data-color="primary" data-height="280"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        var options = {
            series: [
                {
                    name: "{{__('Totle Page View')}}",
                    data: {!! json_encode($chartData['data']) !!}
                },
                {
                    name: "{{__('Unique Page View')}}",
                    data: {!! json_encode($chartData['unique_data']) !!}
                },
            ],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#77B6EA', '#011c4b'],
            dataLabels: {
                enabled: true,
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: '',
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1
            },
            xaxis: {
                categories: {!! json_encode($chartData['label']) !!},
                title: {
                    text: 'Last 30 days'
                }
            },
            yaxis: {
                title: {
                    text: '{{__('Visitor')}}'
                },
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };
        var chart = new ApexCharts(document.querySelector("#apex-storedashborad"), options);
        chart.render();

        var options = {
            series:{!! json_encode($devicearray['data']) !!},
            chart: {
                width: 400,
                type: 'pie',
            },
            labels:{!! json_encode($devicearray['label']) !!},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#pie-storedashborad"), options);
        chart.render();
        var options = {
            series:{!! json_encode($browserarray['data']) !!},
            chart: {
                width: 400,
                type: 'pie',
            },
            labels:{!! json_encode($browserarray['label']) !!},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#pie-storebrowser"), options);
        chart.render();
    </script>
    <script>
        var WorkedHoursChart = (function () {
            var $chart = $('#user_platform-chart');

            function init($this) {
                var options = {
                    chart: {
                        width: '100%',
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        shadow: {
                            enabled: false,
                        },

                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            distributed: true,
                            columnWidth: '30%',
                            endingShape: 'rounded'
                        },
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    series: [{
                        name: 'Platform',
                        data: {!! json_encode($platformarray['data']) !!},
                    }],
                    xaxis: {
                        labels: {
                            // format: 'MMM',
                            style: {
                                colors: PurposeStyle.colors.gray[600],
                                fontSize: '14px',
                                fontFamily: PurposeStyle.fonts.base,
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        },
                        title: {
                            text: '{{__('Platform')}}'
                        },
                        categories: {!! json_encode($platformarray['label']) !!},
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: PurposeStyle.colors.gray[600],
                                fontSize: '12px',
                                fontFamily: PurposeStyle.fonts.base,
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1

                    },
                    markers: {
                        size: 4,
                        opacity: 0.7,
                        strokeColor: "#fff",
                        strokeWidth: 3,
                        hover: {
                            size: 7,
                        }
                    },
                    grid: {
                        borderColor: PurposeStyle.colors.gray[300],
                        strokeDashArray: 5,
                    },
                    dataLabels: {
                        enabled: false
                    }
                }
                // Get data from data attributes
                var dataset = $this.data().dataset,
                    labels = $this.data().labels,
                    color = $this.data().color,
                    height = $this.data().height,
                    type = $this.data().type;

                // Inject synamic properties
                // options.colors = [
                //     PurposeStyle.colors.theme[color]
                // ];
                // options.markers.colors = [
                //     PurposeStyle.colors.theme[color]
                // ];
                options.chart.height = height ? height : 350;
                // Init chart
                var chart = new ApexCharts($this[0], options);
                // Draw chart
                setTimeout(function () {
                    chart.render();
                }, 300);
            }

            // Events
            if ($chart.length) {
                $chart.each(function () {
                    init($(this));
                });
            }
        })();
    </script>
@endpush
