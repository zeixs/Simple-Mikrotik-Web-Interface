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
                                {{-- <option data-select2-id="39">dhcp1</option>
                                <option data-select2-id="40">dhcp2</option> --}}
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

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 450px;">
                            <table class="table table-head-fixed text-nowrap" id="client_list">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>IP Address</th>
                                        <th>client Id</th>
                                        <th>Mac Address</th>
                                        <th>Server</th>
                                        <th>Status</th>
                                        <th>Expired In</th>
                                        <th>Last Seen</th>

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
                        console.log("++++++ ServerOnload ++++++", value.name);
                    })
                    $.ajax({
                        type: 'GET',
                        url: '/client-list/' + $("#server_select").val(),
                        success: function(htmlresponse) {
                            $.each(htmlresponse, function(key, value) {
                                var tr = $("<tr />")
                                $.each(value, function(k, v) {
                                    tr.append(
                                        $("<td />", {
                                            html: v
                                        })[0].outerHTML
                                    );
                                    $("table tbody").append(tr)
                                })
                            })
                            console.log("++++++ Onload ++++++", htmlresponse);
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

        // $(document).ready(function() {

        // });

        jQuery(document).ready(function getClient($) {
            $("#server_select").on('change', function() {
                var level = $(this).val();
                $('#client_list').empty();
                $.ajax({
                    type: 'GET',
                    url: '/client-list/' + level,
                    success: function(htmlresponse) {
                        $('#client_list').append('<thead><tr><th>' + 'No' + '</th><th>' +
                            'blah' + '</th><th>' +
                            'blah' + '</th><th>' + 'blah' + '</th><th>' + 'blah' +
                            '</th><th>' + 'blah' + '</th></tr></thead>' +
                            '<tbody></tbody>');
                        $.each(htmlresponse, function(key, value) {
                            var tr = $("<tr />")
                            $.each(value, function(k, v) {
                                tr.append(
                                    $("<td />", {
                                        html: v
                                    })[0].outerHTML
                                );
                                $("table tbody").append(tr)
                            })
                        })
                        console.log("+++++CurrentPage+++++++", htmlresponse);
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
