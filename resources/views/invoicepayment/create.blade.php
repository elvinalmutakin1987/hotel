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
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('invoice.store') }}" method="post">
                    @csrf
                    @method('POST')
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
                                <label for="invoice_id" class="form-label">Invoice Number</label>
                                <select class="form-select" id="invoice_id" name="invoice_id"
                                    data-placeholder="Choose invoice number">

                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('invoice.index') }}">Back</a>
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
            });
        }
    </script>
@endpush
