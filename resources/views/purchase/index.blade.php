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
                <li class="breadcrumb-item active" aria-current="page">Purchase</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">

                <a type="button" class="btn btn-outline-primary mb-3" href="{{ route('purchase.create') }}"> Add New </a>

                <div class="d-flex flex-row gap-2 mb-3">
                    <div class="flex-fill w-100">
                        <select class="form-select" id="supplier_id" name="supplier_id" data-placeholder="Choose supplier">
                            <option value="All" {{ 'null' == request()->get('supplier_id') ? 'selected' : '' }}>All
                            </option>
                            @foreach ($supplier as $d)
                                <option value="{{ $d->id }}"
                                    {{ $d->id == request()->get('supplier_id') ? 'selected' : '' }}>{{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-fill w-100">
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ request()->get('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="flex-fill w-100">
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ request()->get('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="flex-fill w-100">
                        <input type="text" id="search" name="search" class="form-control mb-3"
                            placeholder="Search by number..." value="{{ request()->get('search') }}">
                    </div>
                </div>

                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Number</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($purchase->count() == 0)
                            <tr>
                                <td colspan="6" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($purchase as $d)
                                <tr>
                                    <td><a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                            href="{{ route('purchase.show', $d->id) }}">{{ $d->number }}</a></td>
                                    <td>{{ $d->date }}</td>
                                    <td>{{ $d->supplier->name }}</td>
                                    <td>{{ Illuminate\Support\Number::format((float) $d->grand_total) }}</td>
                                    <td>
                                        @if ($d->status == 'Draft')
                                            <span class="badge text-bg-primary"> {{ $d->status }}</span>
                                        @else
                                            <span class="badge text-bg-success"> {{ $d->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status == 'Submit')
                                            <a class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                                href="{{ route('purchase.print', $d->id) }}">
                                                Print
                                            </a>
                                        @else
                                            <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                                href="{{ route('purchase.edit', $d->id) }}">
                                                Edit
                                            </a>
                                            <form class="d-inline" action="{{ route('purchase.destroy', $d->id) }}"
                                                method="POST" id="form-delete{{ $d->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <a class="link-danger link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                    href="#" data-id="{{ $d->id }}"
                                                    onclick="delete_data({{ $d->id }}); return false;">
                                                    Delete
                                                </a>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <nav>
                    {{ $purchase->links('pagination::bootstrap-5') }}
                </nav>

            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        @if (session('message'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('message') }}",
                    icon: "success"
                });
            });
        @endif

        $(document).ready(function() {
            $('#supplier_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                allowClear: true,
            }).on('change', function() {
                search()
            });

            $("#start_date").on('change', function() {
                search()
            })

            $("#end_date").on('change', function() {
                search()
            })

            $(document).keyup(function(e) {
                if ($("#search").is(":focus") && (e.keyCode == 13)) {
                    search()
                }
            });
        });

        function delete_data(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you won't be able to recover this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete' + id).submit();
                }
            });
        }

        function search() {
            var url =
                '{!! route('purchase.index', [
                    'start_date' => '_start_date',
                    'end_date' => '_end_date',
                    'supplier_id' => '_supplier_id',
                    'search' => '_search',
                ]) !!}'
            url = url.replace('_start_date', $("#start_date").val())
            url = url.replace('_end_date', $("#end_date").val())
            url = url.replace('_supplier_id', $("#supplier_id").val())
            url = url.replace('_search', $("#search").val())
            window.open(url, '_self')
        }
    </script>
@endpush
