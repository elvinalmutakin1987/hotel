@foreach ($goodreceipt->goodreceiptdetail as $d)
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
            {{ Illuminate\Support\Number::format((float) $d->price) }}
            <input type="hidden" name="price[]" value="{{ Illuminate\Support\Number::format((float) $d->price) }}">
        </td>
        <td class='text-end'>
            {{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}
            <input type="hidden" name="qty[]"
                value="{{ Illuminate\Support\Number::format((float) $d->qty, precision: 1) }}">
        </td>
        <td class='text-end'>
            {{ Illuminate\Support\Number::format((float) $d->sub_total) }}
            <input type="hidden" name="sub_total[]"
                value="{{ Illuminate\Support\Number::format((float) $d->sub_total) }}">
        </td>
        <td class="text-end">
            {{-- <button type="button" class="btn btn-sm btn-outline-danger delete-row">Delete</button> --}}
        </td>
    </tr>
@endforeach
