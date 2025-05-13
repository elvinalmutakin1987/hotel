@php
    use Illuminate\Support\Number;
@endphp

<style>
    .table-main {
        font-family: Arial, Helvetica, sans-serif;
        width: 100%;
        border-collapse: collapse;
    }

    .table-detail {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid;
    }

    .w-10 {
        width: 10%;
        vertical-align: top;
    }

    .w-20 {
        width: 20%;
        vertical-align: top;
    }

    .w-30 {
        width: 30%;
        vertical-align: top;
    }

    .w-40 {
        width: 40%;
        vertical-align: top;
    }

    .w-50 {
        width: 50%;
        vertical-align: top;
    }

    .w-60 {
        width: 60%;
        vertical-align: top;
    }

    .w-70 {
        width: 70%;
        vertical-align: top;
    }

    .w-80 {
        width: 80%;
        vertical-align: top;
    }

    .w-90 {
        width: 90%;
        vertical-align: top;
    }

    .w-100 {
        width: 100%;
        vertical-align: top;
    }

    .underline {
        text-decoration: underline;
    }

    .font-10 {
        font-size: 10pt !important;
    }

    .float-right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .text-bold {
        font-weight: bold
    }

    .header-table {
        font-weight: bold;
        border-top: 1px solid;
        border-bottom: 1px solid;
    }

    .garis-bawah {
        border-bottom: 1px solid;
    }

    .garis-atas {
        border-top: 1px solid;
    }

    .row-detail {
        border-bottom: 1px solid;
    }

    .tinggi-tr {
        height: 70px
    }

    @media print {
        @page {
            size: A4 !important;
            margin: 15px !important;
        }

        .page {
            font-size: 12pt !important;
        }

        thead {
            display: table-header-group !important;
        }

        tfoot {
            display: table-footer-group !important;
        }

        button {
            display: none !important;
        }

        body {
            margin: 0 !important;
        }
    }
</style>
<table class="table-main">
    <tr>
        <td>
            <table class="table-main">
                <tr>
                    <td class="w-70 font-10">
                        {{ env('COMPANY') }} <br>
                        {{ env('COMPANY_ADDRESS') }}
                    </td>
                    <td class="w-30 float-right">
                        <b class="underline">Purchase Order </b><br>
                        No: {{ $purchase->number }}
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td class="w-70">
                        <table class="w-100 font-10">
                            <tr>
                                <td width="100">Date</td>
                                <td width="10">:</td>
                                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td width="100">Supplier</td>
                                <td width="10">:</td>
                                <td>{{ $purchase->supplier->name }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-30">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table-main font-10">
                <tr class="header-table">
                    <td width="50px">No.</td>
                    <td>Item</td>
                    <td width="100">Unit</td>
                    <td class="float-right" width="75">Price</td>
                    <td class="float-right" width="75">Qty</td>
                    <td class="float-right" width="75">Amount</td>
                </tr>
                @foreach ($purchase->purchasedetail as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->item->name }}</td>
                        <td>{{ $d->unit }}</td>
                        <td class="float-right">{{ Number::format((float) $d->price) }}</td>
                        <td class="float-right">{{ Number::format((float) $d->qty, precision: 1) }}</td>
                        <td class="float-right">{{ Number::format((float) $d->sub_total) }}</td>
                    </tr>
                @endforeach
                <tr class="garis-atas">
                    <td class="float-right text-bold" colspan="5">Grand Total</td>
                    <td class="float-right">{{ Number::format((float) $purchase->grand_total) }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <table class="table-main">
                <tr>
                    <td class="w-50 font-10">
                        <table class="table-detail">
                            <tr class="table-detail">
                                <td width="30%" class="table-detail center">Created By</td>
                                <td width="30%" class="table-detail center">Approved By</td>
                            </tr>
                            <tr class="table-detail">
                                <td class="table-detail tinggi-tr"></td>
                                <td class="table-detail tinggi-tr"></td>
                            </tr>
                            <tr class="table-detail">
                                <td class="table-detail">&nbsp;</td>
                                <td class="table-detail">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
