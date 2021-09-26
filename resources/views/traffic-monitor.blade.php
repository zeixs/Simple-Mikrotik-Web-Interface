<!DOCTYPE HTML>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Monitor Traffic</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
        integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">


</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Mikrotik Traffic Monitor</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">


            </ul>

        </div>
    </nav>



    <main role="main" class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card mt-5">
                    <div id="graph"></div>
                </div>
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

            </div>
        </div>
    </main><!-- /.container -->

    <footer class="footer">
        <div class="container">
            <span class="text-muted">&copy; 2020 by <a href="//sharkwifi.com/">sharkwifi.com</a></span>
        </div>
    </footer>

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
                    console.log('++++++++++++++++++', midata);
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
</body>

</html>
