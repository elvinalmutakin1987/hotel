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
                        href="{{ route('checkin.index') }}">
                        Check In
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Reservation Number</th>
                            <th class="w-25 text-center">Room</th>
                            <th class="w-25 text-center">Room Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($reservation->count() == 0)
                            <tr>
                                <td colspan="3" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($reservation as $d)
                                <tr>
                                    <td>
                                        @if ($d->room->status == 'Available')
                                            <form class="d-inline" action="{{ route('checkin.update', $d->id) }}"
                                                method="POST" id="form-delete{{ $d->id }}">
                                                @csrf
                                                @method('PUT')
                                                <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                    href="#" data-id="{{ $d->id }}"
                                                    onclick="check_in({{ $d->id }}); return false;">
                                                    {{ $d->number }}
                                                </a>
                                            </form>
                                        @else
                                            {{ $d->number }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $d->room->number }}</td>
                                    <td class="text-center">
                                        @if ($d->room->status == 'Available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($d->room->status == 'Occupied')
                                            <span class="badge bg-info">Occupied</span>
                                        @elseif($d->room->status == 'Reserved')
                                            <span class="badge bg-primary">Reserved</span>
                                        @elseif($d->room->status == 'Housekeeping')
                                            <span class="badge bg-warning">Housekeeping</span>
                                        @elseif($d->room->status == 'Maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @elseif($d->room->status == 'Out of Order')
                                            <span class="badge bg-danger">Out of Order</span>
                                        @elseif($d->room->status == 'Blocked')
                                            <span class="badge bg-dark">Blocked</span>
                                        @elseif($d->room->status == 'Check-in')
                                            <span class="badge text-bg-light">Check-in</span>
                                        @elseif($d->room->status == 'Check-out')
                                            <span class="badge bg-secondary">Check-out</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <a type="button" class="btn btn-outline-secondary" href="{{ route('checkin.index') }}">Back</a>
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

        function check_in(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Confirming will change the room status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, check-in it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete' + id).submit();
                }
            });
        }
    </script>
@endpush
