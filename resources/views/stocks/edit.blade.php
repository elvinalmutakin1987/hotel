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
                        href="{{ route('stocks.index') }}">
                        Stocks
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('stocks.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $item->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <select class="form-select" id="unit" name="unit" data-placeholder="Choose unit">
                            @foreach ($unit as $d)
                                <option value="{{ $d['name'] }}"{{ $d['name'] == $item->unit ? 'selected' : '' }}>
                                    {{ $d['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="text" class="form-control" id="stock" name="stock"
                            value="{{ Illuminate\Support\Number::format($item->stock) }}">
                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="border border rounded-3 p-3 mb-3">
                                <legend class="float-none w-auto fs-5">Purchase Price</legend>
                                <div class="mb-3">
                                    <table id="table1" class="table mt-3">
                                        <thead class="table-group-divider">
                                            <tr>
                                                <th>Supplier</th>
                                                <th width="15%">Price</th>
                                                <th class="text-end" width="5%">#</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <select class="form-select" id="supplier_list" name="supplier_list"
                                                        data-placeholder="Choose unit">
                                                        @foreach ($supplier as $d)
                                                            <option value="{{ $d->id }}">{{ $d->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control" id="item_price"
                                                        name="item_price" value="0">
                                                </th>
                                                <th class="text-center">
                                                    <button type="button" class="btn btn-block btn-outline-primary"
                                                        onclick="add()">Add</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->itemprice as $d)
                                                <tr>
                                                    <td>{{ $d->supplier->name }}
                                                        <input type="hidden" name="supplier_id[]"
                                                            value="{{ $d->supplier_id }}">
                                                    </td>
                                                    <td>{{ Illuminate\Support\Number::format($d->price) }}
                                                        <input type="hidden" name="price[]" value="{{ $d->price }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-block btn-outline-danger delete-row"
                                                            id="delete">Delete</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('stocks.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {
            $('#unit').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            });

            $('#item_unit').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            });

            $('#supplier_list').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            });

            $('#supplier_list').val(null).trigger('change');
        });

        $("#stock").on('keyup', function() {
            $(this).val(numeral(this.value).format("0,0"))
        })

        $("#item_price").on('keyup', function() {
            $(this).val(numeral(this.value).format("0,0"))
        })

        function add() {
            var supplier_id = $("#supplier_list").val();
            var supplier_name = $("#supplier_list option:selected").text();
            var unit = $("#item_unit").val();
            var price = $("#item_price").val();
            var tbody = $("#table1 > tbody");

            var rowHTML = `
                <tr>
                    <td>
                        ${supplier_name}
                        <input type="hidden" name="supplier_id[]" value="${supplier_id}">
                    </td>
                    <td>
                        ${price}
                        <input type="hidden" name="price[]" value="${price}">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger delete-row">Delete</button>
                    </td>
                </tr>`;

            tbody.append(rowHTML);

            $("#item_price").val("0");
            $('#supplier_list').val(null).trigger('change');
        }


        $("#table1").on("click", ".delete-row", function() {
            $(this).closest("tr").remove();
        });
    </script>
@endpush
