@php
    $payment_total = 0;
@endphp
@foreach ($invoicepayment as $d)
    <tr>
        <td><a href="{{ route('invoicepayment.show', $d->id) }}" target="_blank">{{ $d->number }}</a></td>
        <td>{{ $d->date }}</td>
        <td class="text-end">{{ Illuminate\Support\Number::format((float) $d->payment_total) }}</td>
    </tr>
    @php
        $payment_total += (float) $d->payment_total;
    @endphp
@endforeach
<tr>
    <td colspan="2" class="text-end"><b>Total :</b></td>
    <td class="text-end">{{ Illuminate\Support\Number::format($payment_total) }}</td>
</tr>
<tr>
    <td colspan="2" class="text-end"><b>Remain Payment :</b></td>
    <td class="text-end">
        <b>{{ Illuminate\Support\Number::format((float) $invoice->grand_total - (float) $payment_total) }}</b>
    </td>
</tr>
