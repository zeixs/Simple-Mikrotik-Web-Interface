@extends('base', ['body_class' => 'hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed
layout-footer-fixed'])
@section('title')
    Connected User
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
                        <h1 class="m-0">Connected User</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">

            <div class="row">
                <div class="col-12 col-sm-6" data-select2-id="30">
                    <div class="form-group" data-select2-id="29">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="m-1">Server</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control select2 select2-danger select2-hidden-accessible"
                                data-dropdown-css-class="select2-danger" style="width: 100%;" data-select2-id="12"
                                tabindex="-1" aria-hidden="true" id="server_select">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Client List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 450px;">
                            <table class="table table-head-fixed text-nowrap" id="client_list">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Address</th>
                                        <th>Active Mac Address</th>
                                        <th>Client Id</th>
                                        <th>Hostname</th>
                                        <th>Status</th>
                                        <th>Expires After</th>
                                        <th>Disabled</th>
                                        <th>Blocked</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
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
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'dhcp-server',
                success: function(htmlresponse) {
                    $.each(htmlresponse, function(key, value) {
                        $("#server_select").append('<option>' + value.name +
                            '</option>');
                    })
                    $.ajax({
                        type: 'GET',
                        url: '/client-list/' + $("#server_select").val(),
                        success: function(htmlresponse) {
                            var counter = 0;

                            $.each(htmlresponse, function(key, value) {
                                counter = counter + 1;
                                $("table tbody").append("<tr><td>" + counter +
                                    "</td><td>" +
                                    value.address + "</td><td>" + value[
                                        'active-mac-address'] +
                                    "</td><td>" + value['client-id'] +
                                    "</td><td>" +
                                    value['host-name'] + "</td><td>" + value
                                    .status +
                                    "</td><td>" + value['expires-after'] +
                                    "</td><td>" +
                                    value.disabled + "</td><td>" + value
                                    .blocked +
                                    "</td><tr>")
                            })
                        },
                        error: function(e) {
                            alert("error");
                        }
                    });
                },
                error: function(e) {
                    alert("error");
                }
            });
        });

        jQuery(document).ready(function getClient($) {
            $("#server_select").on('change', function() {
                var level = $(this).val();
                $('#client_list').empty();
                $.ajax({
                    type: 'GET',
                    url: '/client-list/' + level,
                    success: function(htmlresponse) {
                        $('#client_list').append('<thead><tr><th>' + 'No' + '</th><th>' +
                            'Address' + '</th><th>' +
                            'Active Mac Address' + '</th><th>' + 'Client Id' + '</th><th>' +
                            'Hostname' +
                            '</th><th>' + 'Status' + '</th><th>' + 'Expires After' +
                            '</th><th>' + 'Disabled' + '</th><th>' + 'Blocked' +
                            '</th></tr></thead>' +
                            '<tbody></tbody>');

                        var counter = 0;

                        $.each(htmlresponse, function(key, value) {
                            counter = counter + 1;
                            $("table tbody").append("<tr><td>" + counter + "</td><td>" +
                                value.address + "</td><td>" + value[
                                    'active-mac-address'] +
                                "</td><td>" + value['client-id'] + "</td><td>" +
                                value['host-name'] + "</td><td>" + value.status +
                                "</td><td>" + value['expires-after'] + "</td><td>" +
                                value.disabled + "</td><td>" + value.blocked +
                                "</td><tr>")
                        })
                    },
                    error: function(e) {
                        alert("error");
                    }
                });
            });
        });
    </script>
@endsection
@endsection

</html>
