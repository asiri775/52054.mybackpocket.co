@extends('user.layouts.master')

@section('title', 'Purchases By Marchant Report')

@section('page-css')
    <style>
        .dataTables_filter {
            display: none;
        }

        #ActivateAdvanceSerach,
        #HideActivateAdvanceSerach {
            color: #fff !important;
            background-color: #00238C !important;
            border-color: #3b475 !important;
        }

        .searchFilters {
            background-color: #eaebe6;
        }

        .card .card-body {
            padding: 5px 10px 5px 10px !important;
        }

        #tableTransactions_info {
            text-align: center;
            text-transform: uppercase;
            font-size: 14px;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px !important;
            float: unset !important;
            text-align: center !important;
            margin-bottom: 20px !important;
        }

        .dataTables_wrapper .dataTables_paginate ul>li {
            font-size: 14px !important;
        }

        .batch-action {
            color: #fff !important;
            background-color: #010267 !important;
            border: 1px solid #f0f0f0 !important;
        }

        .batch-export {
            color: #fff !important;
            background-color: #248c01 !important;
            border: 1px solid #f0f0f0 !important;
        }

        .batch-export:hover,
        .batch-action:hover {
            background-color: #fafafa !important;
            border: 1px solid rgba(98, 98, 98, 0.27) !important;
            color: #333 !important;
        }

        .batch-export.active,
        .batch-action.active {
            border-color: #e6e6e6 !important;
            background: #fff !important;
            color: #333 !important;
        }

        .form-control {
            font-family: Montserrat, sans-serif !important;
        }

    </style>
@endsection
@section('content')
    <div class=" container-fluid   container-fixed-lg">
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <!-- START card -->
        <div class="card card-default  p-l-20  p-r-20">
            <div class="card-header separator">
                <div class="card-title">
                    <h5><strong>purchases by vendor reports</strong></h5>
                </div>
            </div>
            <div class="p-b-10">
                <div class="col md-12  p-t-10 p-l-30 row">
                    <div class="col-md-4  card card-default p-r-1">
                        <div class="card-title">
                            <h5><strong>Favourites</strong></h5>

                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 10%; ">Store</th>
                                        <th style="width: 20%; ">Transactions
                                        </th>
                                        <th style="width: 20%; ">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($favourites as $favourite)
                                        <tr>
                                            <td> <a
                                                    href="{{ url('/user/reports/vendor-detail/' . $favourite->vendor_id) }}">
                                                    {{ $favourite->getVendor($favourite->vendor_id) }}</a>
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ url('/user/reports/vendor-detail/' . $favourite->vendor_id) }}">
                                                    {{ $favourite->transactionCount($favourite->vendor_id) }}</a>
                                            </td>
                                            <td>${{ $favourite->transactionAmount($favourite->vendor_id) }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-8 p-t-10 card card-default">
                        <div class="card-title">
                            <h5><strong>Transactions By Store</strong></h5>
                        </div>
                        <div class="card-body p-t-10 searchFilters">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_vendor" class="control-label">Store Name</label><br>
                                        <input type="text" class="form-control" id="filter_vendor">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_options" class="control-label">Quick Date</label>
                                        <select name="date_options" id="date_options" class="form-control">
                                            <option value="all_time">Pick an option</option>
                                            <option value="this_month" @if ($time == 'this_month') selected @endif>This Month</option>
                                            <option value="this_year" @if ($time == 'this_year') selected @endif>This Year</option>
                                            <option value="all_time" @if ($time == 'all_time') selected @endif>All Time</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from" class="control-label">Category</label><br>
                                        <select id="filter_category" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                        <button type="button" class="btn btn-info form-control"
                                            id="ActivateAdvanceSerach">Advance Search</button>
                                        <button type="button" class="btn btn-info form-control"
                                            id="HideActivateAdvanceSerach" style="display: none;">Hide Advance
                                            Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body searchFilters m-b-10 p-b-10" id="AdvanceFilters" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from" class="control-label">From</label>
                                        <input type="text" id="from" placeholder="mm-dd-yyyy" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to" class="control-label">To</label>
                                        <input type="text" id="to" placeholder="mm-dd-yyyy" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="low" class="control-label">Low</label>
                                        <input type="text" id="low" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="high" class="control-label">High</label>
                                        <input type="text" id="high" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br>
                        <table
                            class=" table table-hover table-condensed table-responsive-block
                    table-responsive"
                            id="tableTransactions">
                            <thead>
                                <tr>
                                    <th style="width:5%"></th>
                                    <th style="width:5%;">ID</th>
                                    <th style="width: 10%;">Store</th>
                                    <th style="width: 10%;">Category</th>
                                    <th style="width: 10%;">Date</th>
                                    <th style="width: 10%;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->

@endsection
@section('page-js')
    <script>
        $(document).ready(function(e) {
            $("#ActivateAdvanceSerach").click(function() {
                $("#AdvanceFilters").show();
                $("#HideActivateAdvanceSerach").show();
                $("#ActivateAdvanceSerach").hide()
            });
            $("#HideActivateAdvanceSerach").click(function() {
                $("#AdvanceFilters").hide();
                $("#HideActivateAdvanceSerach").hide();
                $("#ActivateAdvanceSerach").show();
            });


            function savePDF() {
                var newWin = window.open();
                var ids = [];
                $.each($("input[name='pdr_checkbox[]']:checked"), function() {
                    ids.push($(this).val());
                });
                if (ids.length > 0) {
                    var CSRF_TOKEN = $('input[name="_token"]').attr('value');
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url: '<?php echo route('user.transactions.printList'); ?>',
                        data: {
                            _token: CSRF_TOKEN,
                            transaction_ids: ids
                        },
                        success: function(html) {}
                    });
                }
            }

            function printAllTransactions() {
                var newWin = window.open();
                var ids = [];
                $.each($("input[name='pdr_checkbox[]']:checked"), function() {
                    ids.push($(this).val());
                });
                if (ids.length > 0) {
                    var CSRF_TOKEN = $('input[name="_token"]').attr('value');
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url: '<?php echo route('user.transactions.printAll'); ?>',
                        data: {
                            _token: CSRF_TOKEN,
                            transaction_ids: ids
                        },
                        success: function(html) {
                            newWin.document.write(html);
                            newWin.document.close();
                            newWin.focus();
                            newWin.print();
                            newWin.close();
                        }
                    });
                }
            }

            function bulkXLSTransactions() {
                var download = function(content, fileName, mimeType) {
                    var a = document.createElement('a');
                    mimeType = mimeType || 'application/octet-stream';

                    if (navigator.msSaveBlob) { // IE10
                        navigator.msSaveBlob(new Blob([content], {
                            type: mimeType
                        }), fileName);
                    } else if (URL && 'download' in a) { //html5 A[download]
                        a.href = URL.createObjectURL(new Blob([content], {
                            type: mimeType
                        }));
                        a.setAttribute('download', fileName);
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        location.href = 'data:application/octet-stream,' + encodeURIComponent(
                        content); // only this mime type is supported
                    }
                }
                var newWin = window.open();
                var ids = [];
                $.each($("input[name='pdr_checkbox[]']:checked"), function() {
                    ids.push($(this).val());
                });
                if (ids.length > 0) {
                    var CSRF_TOKEN = $('input[name="_token"]').attr('value');
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url: '<?php echo route('user.transactions.exportAll'); ?>',
                        data: {
                            _token: CSRF_TOKEN,
                            transaction_ids: ids
                        },
                        success: function(html) {
                            download(html, 'dowload.csv', 'text/csv;encoding:utf-8');
                        }
                    });
                }
            }

            $(document).ready(function() {
                var array = [];
                $("#selectAllTransactions").on("click", function(e) {
                    var table = $("#tableTransactions");
                    var boxes = $('input:checkbox', table);
                    $.each($('input:checkbox', table), function() {

                        $(this).parent().addClass('checked');
                        $(this).prop('checked', 'checked');

                    });

                    $('#hideTransactions').prop('disabled', false);
                    $('#deselectAllTransactions').prop('disabled', false);
                });

                $("#deselectAllTransactions").on("click", function(e) {
                    var table = $("#tableTransactions");
                    var boxes = $('input:checkbox', table);
                    $.each($('input:checkbox', table), function() {

                        $(this).parent().removeClass('checked');
                        $(this).prop('checked', false);

                    });
                    $('#hideTransactions').prop('disabled', true);
                });

            });

            var table = $('#tableTransactions');
            $.fn.dataTable.ext.errMode = 'none';
            var trans_datatable = table.DataTable({
                "serverSide": true,
                "sDom": '<"H"lfr>t<"F"ip>',
                "destroy": true,
                "pageLength": 10,
                "sPaginationType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "ajax": {
                    "url": "{{ route('user.reports.purchaseByVendor.datatable') }}",
                    "method": "POST",
                    'data': function(data) {
                        data.order_no = $('#filter_order_no').val();
                        data.vendor_name = $('#filter_vendor').val();
                        data.from = $('#from').val();
                        data.to = $('#to').val();
                        data.date_option = $('#date_options').val();
                        data.year_to_date = $('#year_to_date').val();
                    }
                },
                "order": [
                    [0, "asc"]
                ],
                "columns": [{
                        data: 'checkboxes',
                        name: 'checkboxes',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                            $(nTd).html(
                                "<a style='color: #0090d9' href='/user/reports/vendor-detail/" +
                                oData.id + "'>" + oData.name + "</a>");
                        }
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                ],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                }
            });

            $(document).on('keyup', '#filter_vendor', function() {
                trans_datatable.draw();
            });


            $('#from').change(function() {
                trans_datatable.draw();
            });
            $('#to').change(function() {
                trans_datatable.draw();
            });
            $('#date_options').change(function() {
                trans_datatable.draw();
            });
            $('#year_to_date').change(function() {
                trans_datatable.draw();
            });

            //Date Pickers
            $('#from').datepicker({
                format: 'mm/dd/yyyy'
            });

            $('#to').datepicker({
                format: 'mm/dd/yyyy'
            });

        });
    </script>


@endsection
