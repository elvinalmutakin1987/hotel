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
                <li class="breadcrumb-item active" aria-current="page">Stocks</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">

                <a type="button" class="btn btn-outline-primary mb-3" href="{{ route('stocks.create') }}"> Add New </a>

                <input type="text" id="search" name="search" class="form-control mb-3" placeholder="Search item..."
                    value="{{ request()->get('search') }}">

                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Name</th>
                            {{-- <th>Unit</th> --}}
                            <th>Stock</th>
                            <th class="text-center w-25">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($item->count() == 0)
                            <tr>
                                <td colspan="4" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($item as $d)
                                <tr>
                                    <td>{{ $d->name }}</td>
                                    {{-- <td>{{ $d->unit }}</td> --}}
                                    <td>{{ Illuminate\Support\Number::format($d->stock) }}</td>
                                    <td class="text-center">
                                        <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                            href="{{ route('stocks.edit', $d->id) }}">
                                            Edit
                                        </a>
                                        <form class="d-inline" action="{{ route('stocks.destroy', $d->id) }}" method="POST"
                                            id="form-delete{{ $d->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <a class="link-danger link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                href="#" data-id="{{ $d->id }}"
                                                onclick="delete_data({{ $d->id }}); return false;">
                                                Delete
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <nav>
                    {{ $item->links('pagination::bootstrap-5') }}
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
            var url = '{{ route('stocks.index', ['search' => '_search']) }}'
            url = url.replace('_search', $("#search").val())
            window.open(url, '_self')
        }

        $(document).keyup(function(e) {
            if ($("#search").is(":focus") && (e.keyCode == 13)) {
                search()
            }
        });
    </script>
@endpush
