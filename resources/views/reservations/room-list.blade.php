<label for="room_id" class="form-label">Room</label> <br>
@foreach ($room_types as $rt)
    <fieldset class="border border rounded-3 p-2 mb-3">
        <legend class="float-none w-auto fs-6">{{ $rt->name }}</legend>
        @foreach ($rooms as $r)
            @if ($rt->id == $r->roomtype_id)
                <input type="radio" class="btn-check" id="room_id{{ $r->id }}" name="room_id" autocomplete="off"
                    onclick="room_click('{{ $r->id }}')" value="{{ $r->id }}">
                <label class="btn btn-outline-primary m-1" for="room_id{{ $r->id }}">{{ $r->number }}</label>
            @endif
        @endforeach
    </fieldset>
@endforeach
