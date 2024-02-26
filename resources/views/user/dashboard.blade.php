@include('user.header')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-body d-flex align-items-center py-0 mt-8">
                            <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                <a href="javascript;" class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary">{{count($subjects_init)}}</a>
                                <span class="font-weight-bold text-muted  font-size-lg">Employees</span>
                            </div>
                            <img src="/assets/media/svg/avatars/029-boy-11.svg"  alt="" class="align-self-end h-100px"/>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-3.svg)">
                        <div class="card-body d-flex align-items-center py-0 mt-8">
                            <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                <a href="javascript;" class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary">{{count($locations)}}</a>
                                <span class="font-weight-bold text-muted  font-size-lg">Locations</span>
                            </div>
                            <!-- <img src="/assets/media/svg/shapes/abstract-3.svg"  alt="" class="align-self-end h-100px"/> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-custom card-stretch gutter-b">                        
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Locations</span>
                                    <span class="d-block text-muted mt-2 font-size-sm"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-xxl-4 d-flex flex-column">
                                    <?php
                                    $location_name = [];
                                    $employees = [];
                                    $colors = ['success', 'primary', 'info', 'danger', 'warning'];
                                    ?>
                                    <div class="bg-light-warning p-8 rounded-xl flex-grow-1">
                                        @foreach($locations as $key => $val)
                                        <?php
                                        $location_name[] = $val->name;
                                        ?>
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                                <div class="symbol-label">
                                                    <span class="svg-icon svg-icon-md svg-icon-{{$colors[$key % 4]}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-size-sm font-weight-bold">{{$val->name}}</div>
                                                <?php $exist = false?>
                                                @foreach($subjects as $sub)
                                                    @if($sub->location == $val->id)
                                                    <?php 
                                                        $exist = true;
                                                        $employees[] = $sub->total;
                                                    ?>
                                                    <div class="font-size-sm text-muted">{{$sub->total}}</div>
                                                    @endif
                                                @endforeach
                                                @if($exist == false)
                                                    <?php $employees[] = 0;?>
                                                    <div class="font-size-sm text-muted">0</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-8 col-xxl-8">
                                    <div id="kt_locations"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('user.footer')
<script>
    $(document).ready(function(){
        var KTWidgets_cus = function() {
            var inti_location = function() {
                var element = document.getElementById("kt_locations");

                if (!element) {
                    return;
                }

                var options = {
                    series: [ {
                        name: 'Employees',
                        data: <?php echo json_encode($employees)?>
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: ['30%'],
                            endingShape: 'rounded'
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: <?php echo json_encode($location_name)?>,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                                fontSize: '12px',
                                fontFamily: KTApp.getSettings()['font-family']
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                                fontSize: '12px',
                                fontFamily: KTApp.getSettings()['font-family']
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        },
                        y: {
                            formatter: function(val) {
                                return "" + val + ""
                            }
                        }
                    },
                    colors: [KTApp.getSettings()['colors']['theme']['base']['success'], KTApp.getSettings()['colors']['gray']['gray-300']],
                    grid: {
                        borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }
            return {
                init: function() {
                    inti_location();
                }
            }
        }();
        jQuery(document).ready(function() {
            KTWidgets_cus.init();
        });
    })
</script>