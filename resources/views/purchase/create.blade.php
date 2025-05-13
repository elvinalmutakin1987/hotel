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
                        href="{{ route('purchase.index') }}">
                        Purchase
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('purchase.store') }}" method="post">
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
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
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
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 ms-auto">
                            <div class="mb-3 row align-items-center">
                                <label for="grand_total" class="form-label fw-bold col-4">Grand Total</label>
                                <div class="col-6 fw-bold text-end">
                                    <input type="text" class="form-control text-end" id="grand_total" name="grand_total"
                                        readonly value="0">
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
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('purchase.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function() {
            $('#supplier_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            });

            $('#add_item_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                ajax: {
                    url: '{{ route('purchase.get_item') }}',
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
                var url = "{!! route('purchase.get_item_by_id', ['item_id' => '_item_id', 'supplier_id' => '_supplier_id']) !!}";
                url = url.replace('_item_id', this.value);
                url = url.replace('_supplier_id', $("#supplier_id").val());
                $.get(url, function(data) {
                    if (data.itemprice) {
                        $("#add_price").val(numeral(data.itemprice.price).format("0,0"));
                    } else {
                        $("#add_price").val(numeral(0).format("0,0"));
                    }
                    $("#add_unit").val(data.item.unit);
                    $("#add_qty").val(1);
                    count_sub_total();
                });
            });
        });

        $("#add_price").on('blur', function() {
            $(this).val(numeral(this.value).format("0,0"))

            count_sub_total()
        })

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

        function count_grand_total() {
            let grandTotal = 0;
            $('#table1 tbody tr').each(function() {
                const subTotalText = numeral($(this).find('td:eq(4)').text()).format("0");
                const subTotal = parseFloat(subTotalText);
                if (!isNaN(subTotal)) {
                    grandTotal += subTotal;
                }
            });
            $('#grand_total').val(numeral(grandTotal).format("0,0"));
        }
    </script>
@endpush
