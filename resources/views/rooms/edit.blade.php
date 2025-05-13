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
                        href="{{ route('rooms.index') }}">
                        Rooms
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('rooms.update', $room->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="number" class="form-label">Room Number</label>
                        <input type="text" class="form-control @error('number') is-invalid @enderror" id="number"
                            name="number" value="{{ $room->number }}">
                        @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="roomtype_id" class="form-label">Room Type</label>
                        <select class="form-select" id="roomtype_id" name="roomtype_id" data-placeholder="Choose type">
                            @foreach ($room_type as $d)
                                <option value="{{ $d->id }}" {{ $room->roomtype_id == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" data-placeholder="Choose status">
                            <option value="Available" {{ $room->status == 'Available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="Occupied" {{ $room->status == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="Reserved" {{ $room->status == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="Housekeeping" {{ $room->status == 'Housekeeping' ? 'selected' : '' }}>
                                Housekeeping
                            </option>
                            <option value="Maintenance" {{ $room->status == 'Maintenance' ? 'selected' : '' }}>Maintenance
                            </option>
                            <option value="Out of Order" {{ $room->status == 'Out of Order' ? 'selected' : '' }}>Out of
                                Order
                            </option>
                            <option value="Blocked" {{ $room->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                            <option value="Check-in" {{ $room->status == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                            <option value="Check-out" {{ $room->status == 'Check-out' ? 'selected' : '' }}>Check-out
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('rooms.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {
            $('#roomtype_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            });

            $('#status').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            });
        });
    </script>
@endpush
