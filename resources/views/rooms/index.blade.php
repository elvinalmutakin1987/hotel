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
                <li class="breadcrumb-item active" aria-current="page">Rooms</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">

                <a type="button" class="btn btn-outline-primary mb-3" href="{{ route('rooms.create') }}"> Add New </a>

                <div class="d-flex flex-row gap-2 mb-3">
                    <div class="flex-fill w-100">
                        <select class="form-select" id="roomtype_id" name="roomtype_id" data-placeholder="Choose type">
                            <option>All</option>
                            @foreach ($room_type as $d)
                                <option value="{{ $d->id }}"
                                    {{ request()->get('roomtype_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-fill w-100">
                        <select class="form-select flex-fill" id="status" name="status"
                            data-placeholder="Choose status">
                            <option>All</option>
                            <option value="Available" {{ request()->get('status') == 'Available' ? 'selected' : '' }}>
                                Available</option>
                            <option value="Occupied" {{ request()->get('status') == 'Occupied' ? 'selected' : '' }}>
                                Occupied</option>
                            <option value="Reserved" {{ request()->get('status') == 'Reserved' ? 'selected' : '' }}>
                                Reserved</option>
                            <option value="Housekeeping" {{ request()->get('status') == 'Housekeeping' ? 'selected' : '' }}>
                                Housekeeping</option>
                            <option value="Maintenance" {{ request()->get('status') == 'Maintenance' ? 'selected' : '' }}>
                                Maintenance</option>
                            <option value="Out of Order"
                                {{ request()->get('status') == 'Out of Order' ? 'selected' : '' }}>
                                Out
                                of Order</option>
                            <option value="Blocked" {{ request()->get('status') == 'Blocked' ? 'selected' : '' }}>Blocked
                            </option>
                            <option value="Check-in" {{ request()->get('status') == 'Check-in' ? 'selected' : '' }}>
                                Check-in</option>
                            <option value="Check-out" {{ request()->get('status') == 'Check-out' ? 'selected' : '' }}>
                                Check-out</option>
                        </select>
                    </div>
                    <div class="flex-fill w-100">
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Search rooms..." value="{{ request()->get('search') }}">
                    </div>
                </div>

                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($room->count() == 0)
                            <tr>
                                <td colspan="4" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($room as $d)
                                <tr>
                                    <td>{{ $d->number }}</td>
                                    <td>{{ $d->roomtype->name }}</td>
                                    <td>
                                        @if ($d->status == 'Available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($d->status == 'Occupied')
                                            <span class="badge bg-info">Occupied</span>
                                        @elseif($d->status == 'Reserved')
                                            <span class="badge bg-primary">Reserved</span>
                                        @elseif($d->status == 'Housekeeping')
                                            <span class="badge bg-warning">Housekeeping</span>
                                        @elseif($d->status == 'Maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @elseif($d->status == 'Out of Order')
                                            <span class="badge bg-danger">Out of Order</span>
                                        @elseif($d->status == 'Blocked')
                                            <span class="badge bg-dark">Blocked</span>
                                        @elseif($d->status == 'Check-in')
                                            <span class="badge text-bg-light">Check-in</span>
                                        @elseif($d->status == 'Check-out')
                                            <span class="badge bg-secondary">Check-out</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                            href="{{ route('rooms.edit', $d->id) }}">
                                            Edit
                                        </a>
                                        <form class="d-inline" action="{{ route('rooms.destroy', $d->id) }}" method="POST"
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
                    {{ $room->links('pagination::bootstrap-5') }}
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
            $('#roomtype_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            }).on('change', function() {
                search()
            });

            $('#status').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            }).on('change', function() {
                search()
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
                '{!! route('rooms.index', ['search' => '_search', 'roomtype_id' => '_roomtype_id', 'status' => '_status']) !!}'
            url = url.replace('_search', $("#search").val())
            url = url.replace('_roomtype_id', $("#roomtype_id").val())
            url = url.replace('_status', $("#status").val())
            window.open(url, '_self')
        }

        $(document).keyup(function(e) {
            if ($("#search").is(":focus") && (e.keyCode == 13)) {
                search()
            }
        });
    </script>
@endpush
