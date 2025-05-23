@extends('partials.main')

@section('content')
    <div class="content">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item"> <a
                        class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a></li>
                <li class="breadcrumb-item"> <a
                        class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                        href="{{ route('invoicepayment.index') }}">
                        Payment
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $invoicepayment->number }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <label for="supplier" class="col-sm-2 col-form-label">Supplier</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="supplier"
                            value="{{ $invoicepayment->supplier->name }}">
                    </div>
                </div>
                <div class="row">
                    <label for="supplier" class="col-sm-2 col-form-label">Invoice Number</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="purchase"
                            value="{{ $invoicepayment->invoice->number }}">
                    </div>
                </div>
                <div class="row">
                    <label for="date" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="date"
                            value="{{ \Carbon\Carbon::parse($invoicepayment->date)->format('d/m/Y') }}">
                    </div>
                </div>
                <div class="row">
                    <label for="invoice_total" class="col-sm-2 col-form-label">Payment Total</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="invoice_total"
                            value="{{ Illuminate\Support\Number::format((float) $payment_total) }}">
                    </div>
                </div>
                <div class="row">
                    <label for="payment_method" class="col-sm-2 col-form-label">Payment Method</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="payment_method"
                            value="{{ $invoicepayment->payment_method }}">
                    </div>
                </div>
                <div class="row">
                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="bank_name"
                            value="{{ $invoicepayment->bank_name }}">
                    </div>
                </div>
                <div class="row">
                    <label for="bank_account" class="col-sm-2 col-form-label">Bank Account</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="bank_account"
                            value="{{ $invoicepayment->bank_account }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="transaction_number" class="col-sm-2 col-form-label">Transaction Number</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="transaction_number"
                            value="{{ $invoicepayment->transaction_number }}">
                    </div>
                </div>
                <a type="button" class="btn btn-outline-secondary" href="{{ route('invoicepayment.index') }}">Back</a>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script></script>
@endpush
