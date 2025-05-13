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
                        href="{{ route('room-types.index') }}">
                        Room Types
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('room-types.update', $room_type->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ $room_type->name }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price"
                            value="{{ Illuminate\Support\Number::format($room_type->price) }}">
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('room-types.index') }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $("#price").on('keyup', function() {
            $(this).val(numeral(this.value).format("0,0"))
        })
    </script>
@endpush
