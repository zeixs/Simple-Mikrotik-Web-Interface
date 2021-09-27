@extends('base', ['body_class' => 'hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed
layout-footer-fixed'])
@section('title')
    Dashboard
@endsection
@section('css')
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
@endsection
@section('body')
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble" src="{{ asset('assets/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
            height="60" width="60">
    </div>
    @include('sidebar')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Connected User</span>
                                <span class="info-box-number">
                                    10
                                    <small>%</small>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Blocked User</span>
                                <span class="info-box-number">41,410</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bandwidth Used</span>
                                <span class="info-box-number">760</span>
                            </div>
                        </div>
                    </div>
                </div>
                <main role="main" class="container">
                    <div id="graph" class="col-md-12"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Interace</th>
                                <th>TX</th>
                                <th>RX</th>
                            </tr>
                            <tr>
                                <td><input name="interface" id="interface" type="text" value="ether2" /></td>
                                <td>
                                    <div id="tabletx"></div>
                                </td>
                                <td>
                                    <div id="tablerx"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </main><!-- /.container -->
            </div>
        </section>
    </div>
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
@section('script')
    <script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('assets/adminlte/dist/js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/adminlte/plugins/raphael/raphael.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/adminlte/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/adminlte/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/adminlte/dist/js/demo.js') }}"></script> --}}
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"
        integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/highchart/js/highcharts.js') }}"></script>
    <script>
        var chart;

        function requestDatta(interface) {
            $.ajax({
                // url: '/monitor-traffic/' + interface,
                url: 'monitor-traffic/' + interface,
                datatype: "json",
                success: function(data) {
                    var midata = JSON.parse(data);
                    if (midata.length > 0) {
                        var TX = parseInt(midata[0].data);
                        var RX = parseInt(midata[1].data);
                        var x = (new Date()).getTime();
                        shift = chart.series[0].data.length > 19;
                        chart.series[0].addPoint([x, TX], true, shift);
                        chart.series[1].addPoint([x, RX], true, shift);
                        document.getElementById("tabletx").innerHTML = convert(TX);
                        document.getElementById("tablerx").innerHTML = convert(RX);
                    } else {
                        document.getElementById("tabletx").innerHTML = "0";
                        document.getElementById("tablerx").innerHTML = "0";
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.error("Status: " + textStatus + " request: " + XMLHttpRequest);
                    console.error("Error: " + errorThrown);
                }
            });
        }

        $(document).ready(function() {
            Highcharts.setOptions({
                global: {
                    useUTC: false
                }
            });


            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'graph',
                    animation: Highcharts.svg,
                    type: 'spline',
                    events: {
                        load: function() {
                            setInterval(function() {
                                requestDatta(document.getElementById("interface").value);
                            }, 1000);
                        }
                    }
                },
                title: {
                    text: 'Monitoring'
                },
                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 150,
                    maxZoom: 20 * 1000
                },

                yAxis: {
                    minPadding: 0.2,
                    maxPadding: 0.2,
                    title: {
                        text: 'Traffic'
                    },
                    labels: {
                        formatter: function() {
                            var bytes = this.value;
                            var sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
                            if (bytes == 0) return '0 bps';
                            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                            return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
                        },
                    },
                },
                series: [{
                    name: 'TX',
                    data: []
                }, {
                    name: 'RX',
                    data: []
                }],
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br/>',
                    pointFormat: '{point.x:%Y-%m-%d %H:%M:%S}<br/>{point.y}'
                },


            });
        });

        function convert(bytes) {

            var sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
            if (bytes == 0) return '0 bps';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
@endsection

</html>
