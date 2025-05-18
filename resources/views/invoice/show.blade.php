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
                        href="{{ route('invoice.index') }}">
                        Invoice
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $invoice->number }}</li>
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
                            value="{{ $invoice->supplier->name }}">
                    </div>
                </div>
                <div class="row">
                    <label for="supplier" class="col-sm-2 col-form-label">Good Receipt Number</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="purchase"
                            value="{{ $invoice->goodreceipt->number }}">
                    </div>
                </div>
                <div class="row">
                    <label for="date" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-1">
                        :
                    </div>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext" id="date"
                            value="{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table id="table1" class="table mt-3">
                            <thead class="table-group-divider">
                                <tr>
                                    <th>Items</th>
                                    <th class="text-center" style="width: 10%">Unit</th>
                                    <th class="text-end" style="width: 15%">Price</th>
                                    <th class="text-end" style="width: 10%">Qty</th>
                                    <th class="text-end" style="width: 15%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->invoicedetail as $d)
                                    <tr>
                                        <td>
                                            {{ $d->item->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $d->unit }}
                                        </td>
                                        <td class='text-end'>
                                            {{ Illuminate\Support\Number::format((float) $d->price) }}
                                        </td>
                                        <td class='text-end'>
                                            {{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}
                                        </td>
                                        <td class='text-end'>
                                            {{ Illuminate\Support\Number::format((float) $d->sub_total) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="4" class="text-end">Sub Total</th>
                                    <th class="text-end">
                                        {{ Illuminate\Support\Number::format((float) $invoice->total) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Discount</th>
                                    <th class="text-end">
                                        {{ Illuminate\Support\Number::format((float) $invoice->discount) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end">
                                        {{ Illuminate\Support\Number::format((float) $invoice->after_discount) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Tax ({{ $systemsetting['tax'] }}%)</th>
                                    <th class="text-end">
                                        {{ Illuminate\Support\Number::format((float) $invoice->tax) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Grand Total</th>
                                    <th class="text-end">
                                        {{ Illuminate\Support\Number::format((float) $invoice->grand_total) }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <a type="button" class="btn btn-outline-secondary" href="{{ route('invoice.index') }}">Back</a>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script></script>
@endpush
