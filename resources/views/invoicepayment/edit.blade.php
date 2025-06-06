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
                <form action="{{ route('invoicepayment.update', $invoicepayment->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Supplier</label>
                                <select class="form-select" id="supplier_id" name="supplier_id"
                                    data-placeholder="Choose supplier">
                                    @foreach ($supplier as $d)
                                        <option value="{{ $d->id }}"
                                            {{ $d->id == request()->get('supplier_id') ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="number" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="number" name="number" readonly
                                    value="{{ $invoicepayment->number }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="invoice_total" class="form-label">Invoice Total</label>
                                <input type="text" class="form-control" id="invoice_total" name="invoice_total" readonly
                                    value="{{ Illuminate\Support\Number::format((float) $invoicepayment->invoice->grand_total) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method"
                                    data-placeholder="Choose payment method">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="payment_total" class="form-label">Payment Total</label>
                                <input type="text" class="form-control" id="payment_total" name="payment_total"
                                    value="{{ Illuminate\Support\Number::format((float) $invoicepayment->payment_total) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="bank_account" class="form-label">Bank Account</label>
                                <input type="text" class="form-control" id="bank_account" name="bank_account">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="transaction_number" class="form-label">Transaction Number</label>
                                <input type="text" class="form-control" id="transaction_number"
                                    name="transaction_number">
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Submit
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" name="status" value="Draft">Draft</button></li>
                            <li><button class="dropdown-item" name="status" value="Submit">Submit</button></li>
                        </ul>
                    </div>
                    <a type="button" class="btn btn-outline-secondary"
                        href="{{ route('invoicepayment.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        var tax = {{ $systemsetting['tax'] }}

        $(document).ready(function() {
            $('#supplier_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            }).on('change', function() {
                to_select_invoice()
            });

            to_select_invoice()

            $('#payment_method').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            });

            $("#payment_total").on('blur', function() {
                $(this).val(numeral(this.value).format("0,0"))
            })
        });

        function to_select_invoice() {
            $('#invoice_id').val("").trigger('change');
            $('#invoice_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                    'w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                ajax: {
                    url: '{{ route('invoicepayment.get_invoice') }}',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1,
                            supplier_id: $("#supplier_id").val() || null
                        };
                    },
                    cache: true,
                }
            }).on('select2:select', function() {
                var url = "{!! route('invoicepayment.get_invoice_by_id', ['invoice_id' => '_invoice_id']) !!}"
                url = url.replace('_invoice_id', this.value)
                $.get(url, function(data) {
                    $("#invoice_total").val(numeral(data.invoice.grand_total).format("0,0"))
                    $("#table1 tbody").empty()
                    $("#table1 tbody").html(data.view)
                });
            }).on('select2:clear', function() {
                $("#invoice_total").val("")
            });;
        }
    </script>
@endpush
