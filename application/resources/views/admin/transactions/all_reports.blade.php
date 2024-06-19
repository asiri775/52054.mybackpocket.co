@extends('admin.layouts.newMaster')
@section('title', $title)
@section('content')
    <?php
    $bankAccountId = '';
    $bankAccountStatementId = '';
    if (isset($bankStatement)) {
        $bankAccountId = $bankStatement->bank_account_id;
        $bankAccountStatementId = $bankStatement->id;
    }
    ?>
    <style>
        .akDataTable .dataTables_filter {
            display: inline-block;
        }

        .akDataTable .tableHead {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .akDataTable div.dataTables_info {
            padding: 0 !important;
            margin: 0 !important;
            font-size: 19px !important;
            line-height: 28px;
            font-weight: 600;
            margin-top: -4px !important;
        }

        .akDataTable div.dataTables_wrapper div.dataTables_filter label {
            margin: 0;
            padding: 0;
        }

        .akDataTable div.dataTables_wrapper div.dataTables_length label {
            margin: 0;
            padding: 0;
        }

        .akDataTable .tableHeadRight {
            display: flex;
            margin-right: 0;
            margin-left: auto;
            align-items: center;
        }

        .akDataTable .tableHeadRight .dataTables_filter {
            margin-right: 15px;
        }

        .akDataTable .form-control {
            border-color: #ccc;
        }

        .tableFilters .form-group {
            width: 100%;
        }

        .p-0,
        .card .card-body.p-0 {
            padding: 0 !important;
        }

        .reports-overview ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: block;
        }

        .reports-overview ul li {
            display: inline-block;
        }

        .reports-overview ul li:first-child a {
            padding-left: 0 !important;
        }

        .reports-overview ul li a {
            display: block;
            padding: 0 15px;
            position: relative;
        }

        .reports-overview ul li a:after {
            content: "|";
            position: absolute;
            right: 0;
        }

        #transactionsTable_filter {
            display: none;
        }

        .modal-open .select2-container {
            z-index: 9999999;
        }

        .fieldsWrapper {
            width: 100%;
            margin: 0 0 15px;
        }

        @media only screen and (max-width: 767px) {
            .akDataTable .tableHead {
                display: block;
            }

            .reports-overview {
                border-top: 1px solid #ccc;
                padding-top: 10px;
                margin-top: 10px;
            }
        }
    </style>
    <style>
        .align-left {
            float: left;
        }

        .full-width {
            width: 100%;
        }

        #editFormData .select2-container {
            width: 100% !important;
        }

        #message-vendor,
        #message-budget,
        #message-envelope {
            padding-top: 10px;
        }

        #vendor_id,
        #budget_id,
        #envelope_id,
        .vendor-text-name,
        .budget-text-name,
        .envelope-text-name {
            margin-bottom: 10px;
        }
    </style>
    <script>
        let envlopmentElementSelect2 = null;
    </script>
    <!-- START CONTAINER FLUID -->
    <div class="container-fluid container-fixed-lg">
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif(Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif


        <div class="card card-default akDataTable p-l-20  p-r-20">
            <div class="card-header separator" style="padding-inline: 0">
                <div class="card-title">
                    <h5><strong>{{ $title }}</strong></h5>
                </div>
            </div>
            <div class="p-b-10 mt-3 searchFiltersContainer">
                <div class="card-body p-t-10 searchFilters">

                    <form method="post" action="{{ route('transactions.reportGenerate') }}">
                        {{ csrf_field() }}

                        <input type="hidden" value="1" name="type">

                        <div class="row">
                            <div class="col-12">
                                <h5>Trx Cat Yr by Yr</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from" class="control-label">From Year</label>
                                    <input min="1950" value="{{ old('from_year') }}" required type="number"
                                        name="from_year" id="from" class="form-control filterField search-filter-from"
                                        placeholder="Select From Year">
                                    @if ($errors->has('from_year'))
                                        <span class="help-block" style="color: red">{!! $errors->first('from_year') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to" class="control-label">To Year</label>
                                    <input min="1950" value="{{ old('to_year') }}" required type="number"
                                        name="to_year" id="to" class="form-control filterField search-filter-to"
                                        placeholder="Select To Year">
                                    @if ($errors->has('to_year'))
                                        <span class="help-block" style="color: red">{!! $errors->first('to_year') !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-2">
                                <button type="submit"
                                    class="btn btn-info form-control advanceNonAdvanceSearch ActivateAdvanceSerach">
                                    Generate Report
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
                <br>
                <div class="card-body p-t-10 searchFilters">
                    <form method="post" action="{{ route('transactions.reportGenerate') }}">
                        {{ csrf_field() }}

                        <input type="hidden" value="2" name="type">

                        <div class="row">
                            <div class="col-12">
                                <h5>Trx Cat Date Wise</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_category" class="control-label">Category</label>
                                    <select required id="filter_category" name="category" class="form-control filterField">
                                        <option value="">- Select Category -</option>
                                        @php
                                            $accountCategories = \App\Models\Category::where('type', 'accounting')
                                                ->where('role', 'main')
                                                ->get();
                                        @endphp
                                        @foreach ($accountCategories as $key => $account)
                                            <option value="{{ $account->id }}" style="font-weight: bold;font-size:15px;">
                                                {{ $account->name }}</option>
                                            @foreach (\App\Models\Category::where('role', 'sub')->where('mainid', $account->id)->orderBy('name', 'ASC')->get() as $subCategory)
                                                <option value="{{ $subCategory->id }}"
                                                    style="font-weight: bold;font-size:14px;">
                                                    &nbsp;&nbsp;{{ $subCategory->name }}</option>
                                                @foreach (\App\Models\Category::where('role', 'child')->where('mainid', $subCategory->id)->orderBy('name', 'ASC')->get() as $childCategory)
                                                    <option value="{{ $childCategory->id }}" style="font-size:13px;">
                                                        &nbsp;&nbsp;&nbsp;{{ $childCategory->name }}</option>
                                                @endforeach
                                            @endforeach
                                        @endforeach

                                    </select>
                                    @if ($errors->has('category'))
                                        <span class="help-block" style="color: red">{!! $errors->first('category') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from" class="control-label">From</label>
                                    <input value="{{ old('from') }}" required type="date" name="from"
                                        id="from" class="form-control filterField search-filter-from"
                                        placeholder="Select From">
                                    @if ($errors->has('from'))
                                        <span class="help-block" style="color: red">{!! $errors->first('from') !!}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to" class="control-label">To</label>
                                    <input value="{{ old('to') }}" required type="date" name="to"
                                        id="to" class="form-control filterField search-filter-to"
                                        placeholder="Select To">
                                    @if ($errors->has('to'))
                                        <span class="help-block" style="color: red">{!! $errors->first('to') !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-2">
                                <button type="submit"
                                    class="btn btn-info form-control advanceNonAdvanceSearch ActivateAdvanceSerach">
                                    Generate Report
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>



@endsection

<?php
$fileName = date('mdY') . '_' . $title;
$fileName = \App\Helpers\Helper::slugifyText($fileName);
?>

@section('page-js')
    <script>
        $(document).ready(function(e) {

            $(".tableFilters #year_options").select2();
            $(".tableFilters #filter_vendor").select2();
            // $(".tableFilters #filter_category").select2({
            //     placeholder: "Select Category"
            // });

            $.fn.datepicker.defaults.format = "mm/dd/yyyy";
            $(".datePicker").datepicker();

            trans_datatable.draw();
            $(document).on('keyup change', '.filterField', function() {
                trans_datatable.draw();
            });


        });
    </script>


@endsection
