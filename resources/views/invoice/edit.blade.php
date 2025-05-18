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
                <form action="{{ route('invoice.update', $invoice->id) }}" method="post">
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
                                <label for="goodreceipt_id" class="form-label">Good Receipt Number</label>
                                <select class="form-select" id="goodreceipt_id" name="goodreceipt_id"
                                    data-placeholder="Choose good receipt number">
                                    @if ($invoice->goodreceipt->number)
                                        <option value="{{ $invoice->goodreceipt_id }}">{{ $invoice->goodreceipt->number }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ $invoice->date ?? \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="date" class="form-label">Supplier Bill</label>
                                <input type="text" class="form-control" id="supplier_bill" name="supplier_bill"
                                    value="{{ $invoice->supplier_bill }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="table1" class="table mt-3">
                                <thead class="table-group-divider" id="thead">
                                    <tr>
                                        <th>Items</th>
                                        <th class="text-center" style="width: 10%">Unit</th>
                                        <th class="text-end" style="width: 15%">Price</th>
                                        <th class="text-end" style="width: 10%">Qty</th>
                                        <th class="text-end" style="width: 15%">Amount</th>
                                        <th style="width: 50px" class="text-center"></th>
                                    </tr>
                                    @if (!$invoice->goodreceipt->number)
                                        <tr>
                                            <th>
                                                <select class="form-select" id="add_item_id" name="add_item_id"
                                                    data-placeholder="Choose item">

                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" id="add_unit" name="add_unit"
                                                    readonly>
                                            </th>
                                            <th class="text-end">
                                                <input type="text" class="form-control text-end" id="add_price"
                                                    name="add_price">
                                            </th>
                                            <th class="text-end">
                                                <input type="text" class="form-control text-end" id="add_qty"
                                                    name="add_qty">
                                            </th>
                                            <th class="text-end">
                                                <input type="text" class="form-control text-end" id="add_sub_total"
                                                    name="add_sub_total" readonly>
                                            </th>
                                            <th class="text-center">
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="add()">Add</button>
                                            </th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody id="tbody">
                                    @foreach ($invoice->invoicedetail as $d)
                                        <tr>
                                            <td>
                                                {{ $d->item->name }}
                                                <input type="hidden" name="item_id[]" value="{{ $d->item_id }}">
                                            </td>
                                            <td>
                                                {{ $d->unit }}
                                                <input type="hidden" name="unit[]" value="{{ $d->unit }}">
                                            </td>
                                            <td class='text-end'>
                                                {{ Illuminate\Support\Number::format((float) $d->price) }}
                                                <input type="hidden" name="price[]"
                                                    value="{{ Illuminate\Support\Number::format((float) $d->price) }}">
                                            </td>
                                            <td class='text-end'>
                                                {{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}
                                                <input type="hidden" name="qty[]"
                                                    value="{{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}">
                                            </td>
                                            <td class='text-end'>
                                                {{ Illuminate\Support\Number::format((float) $d->sub_total) }}
                                                <input type="hidden" name="sub_total[]"
                                                    value="{{ Illuminate\Support\Number::format((float) $d->sub_total) }}">
                                            </td>
                                            <td class="text-end">
                                                @if (!$invoice->goodreceipt->number)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-row">Delete</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 ms-auto">
                            <div class="mb-3 row align-items-center">
                                <label for="total" class="form-label fw-bold col-4">Sub Total</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="total" name="total"
                                        readonly value="{{ Illuminate\Support\Number::format((float) $invoice->total) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="discount" class="form-label fw-bold col-4">Discount</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="discount" name="discount"
                                        value="{{ Illuminate\Support\Number::format((float) $invoice->discount) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="after_discount" class="form-label fw-bold col-4">Total</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="after_discount"
                                        name="after_discount" readonly
                                        value="{{ Illuminate\Support\Number::format((float) $invoice->after_discount) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="tax" class="form-label fw-bold col-4">Tax (11%)</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="tax" name="tax"
                                        readonly value="{{ Illuminate\Support\Number::format((float) $invoice->tax) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="grand_total" class="form-label fw-bold col-4">Grand Total</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="grand_total"
                                        name="grand_total"
                                        value="{{ Illuminate\Support\Number::format((float) $invoice->grand_total) }}">
                                </div>
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
                to_select_goodreceipt()
            });

            to_price()

            to_qty()

            to_select_goodreceipt()

            to_select_add()
        });

        function count_sub_total() {
            var price = numeral($("#add_price").val()).format("0")
            var qty = numeral($("#add_qty").val()).format("0.0")
            price = parseFloat(price)
            qty = parseFloat(qty)
            sub_total = price * qty
            $("#add_sub_total").val(numeral(sub_total).format("0,0"))
        }

        function add() {
            var item_id = $("#add_item_id").val();
            var item_name = $("#add_item_id option:selected").text();
            var unit = $("#add_unit").val();
            var price = $("#add_price").val();
            var qty = $("#add_qty").val();
            var sub_total = $("#add_sub_total").val();
            var tbody = $("#table1 > tbody");

            var rowHTML = `
                <tr>
                    <td>
                        ${item_name}
                        <input type="hidden" name="item_id[]" value="${item_id}">
                    </td>
                    <td>
                        ${unit}
                        <input type="hidden" name="unit[]" value="${unit}">
                    </td>
                    <td class='text-end'>
                        ${price}
                        <input type="hidden" name="price[]" value="${price}">
                    </td>
                    <td class='text-end'>
                        ${qty}
                        <input type="hidden" name="qty[]" value="${qty}">
                    </td>
                    <td class='text-end'>
                        ${sub_total}
                        <input type="hidden" name="sub_total[]" value="${sub_total}">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger delete-row">Delete</button>
                    </td>
                </tr>`;

            tbody.append(rowHTML);

            $("#add_unit").val("");
            $("#add_price").val("0");
            $("#add_qty").val("1");
            $("#add_sub_total").val("0");
            $('#add_item_id').val(null).trigger('change');

            count_grand_total()
        }

        $("#table1").on("click", ".delete-row", function() {
            $(this).closest("tr").remove();

            count_grand_total()
        });

        $("#discount").on('blur', function() {
            $(this).val(numeral(this.value).format("0,0"))

            count_grand_total()
        })

        function count_grand_total() {
            let total = 0;
            $('#table1 tbody tr').each(function() {
                const subTotalText = numeral($(this).find('td:eq(4)').text()).format("0");
                const subTotal = parseFloat(subTotalText);
                if (!isNaN(subTotal)) {
                    total += subTotal;
                }
            });
            let discount = numeral($("#discount").val()).format('0');
            let after_discount = parseFloat(total) - parseFloat(discount);
            let vat = after_discount * tax / 100;
            let grand_total = after_discount + vat;
            $('#total').val(numeral(total).format("0,0"));
            $('#tax').val(numeral(vat).format("0,0"));
            $('#discount').val(numeral(discount).format("0,0"));
            $('#after_discount').val(numeral(after_discount).format("0,0"));
            $('#grand_total').val(numeral(grand_total).format("0,0"));
        }

        function to_select_add() {
            $('#add_item_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                    'w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                ajax: {
                    url: '{{ route('invoice.get_item') }}',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        };
                    },
                    cache: true,
                }
            }).on('change', function() {
                var url = "{!! route('invoice.get_item_by_id', ['item_id' => '_item_id', 'supplier_id' => '_supplier_id']) !!}";
                url = url.replace('_item_id', this.value);
                url = url.replace('_supplier_id', $("#supplier_id").val());
                $.get(url, function(data) {
                    if (data.itemprice) {
                        $("#add_price").val(numeral(data.itemprice.price).format(
                            "0,0"));
                    } else {
                        $("#add_price").val(numeral(0).format("0,0"));
                    }
                    $("#add_unit").val(data.item.unit);
                    $("#add_qty").val(1);
                    count_sub_total();
                });
            });
        }

        function to_select_goodreceipt() {
            $('#goodreceipt_id').val("").trigger('change');
            $('#goodreceipt_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                    'w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                ajax: {
                    url: '{{ route('invoice.get_goodreceipt') }}',
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
                var url = "{!! route('invoice.get_goodreceipt_by_id', ['goodreceipt_id' => '_goodreceipt_id']) !!}"
                url = url.replace('_goodreceipt_id', this.value)
                $("#thead").html(`
                    <tr>
                        <th>Items</th>
                        <th class="text-center" style="width: 10%">Unit</th>
                        <th class="text-end" style="width: 15%">Price</th>
                        <th class="text-end" style="width: 10%">Qty</th>
                        <th class="text-end" style="width: 15%">Amount</th>
                        <th style="width: 50px" class="text-center"></th>
                    </tr>`);
                $("#tbody").html(`
                    <tr>
                        <td colspan="6">
                             <div class="text-center" id="spinner">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        </td>
                    </td>`);
                $.get(url, function(data) {
                    $("#tbody").html(data.view)
                    $("#total").val(numeral(data.goodreceipt.total).format("0,0"))
                    $("#discount").val(numeral(data.goodreceipt.discount).format("0,0"))
                    $("#after_discount").val(numeral(data.goodreceipt.after_discount).format("0,0"))
                    $("#tax").val(numeral(data.goodreceipt.tax).format("0,0"))
                    $("#grand_total").val(numeral(data.goodreceipt.grand_total).format("0,0"))
                });
            }).on('select2:clear', function() {
                $("#thead").html(`
                    <tr>
                        <th>Items</th>
                        <th class="text-center" style="width: 10%">Unit</th>
                        <th class="text-end" style="width: 15%">Price</th>
                        <th class="text-end" style="width: 10%">Qty</th>
                        <th class="text-end" style="width: 15%">Amount</th>
                        <th style="width: 50px" class="text-center"></th>
                    </tr>
                    <tr>
                        <th>
                            <select class="form-select" id="add_item_id" name="add_item_id"
                                data-placeholder="Choose item">

                            </select>
                        </th>
                        <th class="text-center">
                            <input type="text" class="form-control" id="add_unit" name="add_unit"
                                readonly>
                        </th>
                        <th class="text-end">
                            <input type="text" class="form-control text-end" id="add_price"
                                name="add_price">
                        </th>
                        <th class="text-end">
                            <input type="text" class="form-control text-end" id="add_qty"
                                name="add_qty">
                        </th>
                        <th class="text-end">
                            <input type="text" class="form-control text-end" id="add_sub_total"
                                name="add_sub_total" readonly>
                        </th>
                        <th class="text-center">
                            <button type="button" class="btn btn-outline-primary"
                                onclick="add()">Add</button>
                        </th>
                    </tr>`);
                $("#tbody").html("")
                to_select_add()
                to_price()
                to_qty()
                $("#total").val("0")
                $("#discount").val("0")
                $("#after_discount").val("0")
                $("#tax").val("0")
                $("#grand_total").val("0")
            });
        }

        function to_qty() {
            $("#add_qty").on('blur', function() {
                if (parseFloat(numeral(this.value).format("0,0")) < 1) {
                    Swal.fire({
                        title: "Warning!",
                        text: "Value must not be less than 1",
                        icon: "warning"
                    });
                    $(this).val(numeral(1).format("0,0"))
                    return false;
                }
                $(this).val(numeral(this.value).format("0,0.0"))

                count_sub_total()
            })
        }

        function to_price() {
            $("#add_price").on('blur', function() {
                $(this).val(numeral(this.value).format("0,0"))

                count_sub_total()
            })
        }
    </script>
@endpush
