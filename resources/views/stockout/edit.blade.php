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
                        href="{{ route('stockout.index') }}">
                        Stock Out
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $stockout->number }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('stockout.update', $stockout->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::parse($stockout->date)->format('Y-m-d') }}">
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
                                        <th class="text-end" style="width: 10%">Qty</th>
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
                                            <input type="text" class="form-control text-end" id="add_qty"
                                                name="add_qty">
                                        </th>
                                        <th class="text-center">
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="add()">Add</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockout->stockoutdetail as $d)
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
                                                {{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}
                                                <input type="hidden" name="qty[]"
                                                    value="{{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}">
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-row">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('stockout.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function() {
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
                var url = "{!! route('stockout.get_item_by_id', ['item_id' => '_item_id']) !!}";
                url = url.replace('_item_id', this.value);
                $.get(url, function(data) {
                    $("#add_unit").val(data.item.unit);
                });
            });
        });

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
        })

        function add() {
            var item_id = $("#add_item_id").val();
            var item_name = $("#add_item_id option:selected").text();
            var unit = $("#add_unit").val();
            var qty = $("#add_qty").val();
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
                        ${qty}
                        <input type="hidden" name="qty[]" value="${qty}">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger delete-row">Delete</button>
                    </td>
                </tr>`;

            tbody.append(rowHTML);

            $("#add_unit").val("");
            $("#add_qty").val("1");
            $('#add_item_id').val(null).trigger('change');
        }

        $("#table1").on("click", ".delete-row", function() {
            $(this).closest("tr").remove();
        });
    </script>
@endpush
