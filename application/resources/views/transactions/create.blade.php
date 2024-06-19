@extends((( Auth::user()->role_id == \App\Constants\Constants::USER_ADMIN) ? 'admin.layouts.newMaster' : 'user.layouts.master' ))

@section('title', 'Add Transactions')

@section('page-css')
    <style>



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
                    <h5><strong>Add Transaction</strong></h5>
                </div>
                <div class="pull-right">
                    @if (Auth::user()->role_id == \App\Constants\Constants::USER_ADMIN)
                        <a href="{{ route('transactions.list') }}" style="vertical-align: middle;"
                            class="btn btn-danger btn-md" id="back">Back</a>
                    @elseif(Auth::user()->role_id == \App\Constants\Constants::USER_ROLE)
                        <a href="{{ route('user.transactions.index') }}" style="vertical-align: middle;"
                            class="btn btn-danger btn-md" id="back">Back</a>
                    @endif
                </div>
            </div>
            <div class="p-b-20">
                @include('transactions._form')
            </div>
        </div>
        <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->

@endsection
@section('page-js')
    <script></script>

@endsection
