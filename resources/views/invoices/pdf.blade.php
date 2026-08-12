<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 40px 48px 48px 48px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .logo {
            height: 30px;
            margin: 0 0 26px 0;
        }

        .parties {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 34px;
        }

        .parties td {
            vertical-align: top;
            width: 50%;
        }

        .parties .to {
            padding-left: 40px;
        }

        .party-label {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-weight: 700;
            font-size: 10.5px;
            line-height: 11.5px;
            margin: 0;
            padding: 0;
            color: #111111;
        }

        .party-body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            color: #555555;
            font-size: 10.5px;
            font-weight: 400;
            line-height: 11.5px;
            margin: 0;
            padding: 0;
        }

        .title-block {
            margin: 0 0 8px 0;
        }

        .invoice-title {
            font-size: 42px;
            font-weight: 400;
            letter-spacing: 0.8px;
            color: #4a4a4a;
            line-height: 1;
            margin: 0 0 8px 0;
        }

        table.title-meta {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 0 14px 0;
        }

        table.title-meta td {
            vertical-align: middle;
            padding: 0;
        }

        table.title-meta td.period-cell {
            width: 32%;
        }

        table.title-meta td.date-cell {
            width: 62%;
            text-align: left;
            padding-left: 0;
        }

        table.title-meta td.meta-spacer {
            width: 6%;
        }

        .invoice-period {
            font-size: 13px;
            color: #4BBECB;
            font-weight: 400;
            line-height: 1.2;
            margin: 0;
        }

        .invoice-date {
            font-size: 11.5px;
            color: #333333;
            white-space: nowrap;
            line-height: 1.2;
            margin: 0;
            text-align: left;
        }

        /* Table indented to align with Date (same left edge) */
        .invoice-table-wrap {
            width: 62%;
            margin-left: 32%;
            margin-bottom: 16px;
        }

        .invoice-footer-wrap {
            width: 62%;
            margin-left: 32%;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
            margin: 0;
            border: none;
        }

        table.items thead th {
            background-color: #4BBECB;
            color: #ffffff;
            font-weight: 700;
            font-size: 11.5px;
            padding: 7px 10px;
            border: none;
            border-top: 1px solid #111111;
            border-bottom: 1px solid #111111;
            vertical-align: middle;
        }

        table.items thead th.desc {
            text-align: left;
            width: 46%;
            padding-left: 10px;
        }

        table.items thead th.spacer {
            width: 8%;
            padding: 0;
        }

        table.items thead th.qty {
            text-align: center;
            width: 10%;
        }

        table.items thead th.unit {
            text-align: right;
            width: 18%;
            padding-right: 10px;
        }

        table.items thead th.total {
            text-align: right;
            width: 18%;
            padding-right: 10px;
        }

        table.items tbody td {
            padding: 10px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #111111;
            font-size: 11.5px;
            color: #222222;
            background: #ffffff;
        }

        table.items tbody td.desc {
            text-align: left;
            white-space: pre-line;
            padding-left: 10px;
            border-right: 1px dashed #b0b0b0;
        }

        table.items tbody td.spacer {
            padding: 0;
            border-right: 1px dashed #b0b0b0;
        }

        table.items tbody td.qty {
            text-align: center !important;
            border-right: 1px dashed #b0b0b0;
        }

        table.items tbody td.unit {
            text-align: right !important;
            white-space: nowrap;
            padding-right: 10px;
            border-right: 1px dashed #b0b0b0;
        }

        table.items tbody td.total {
            text-align: right !important;
            white-space: nowrap;
            padding-right: 10px;
        }

        table.items tr.summary td {
            padding-top: 7px;
            padding-bottom: 7px;
            border-right: none !important;
        }

        table.items tr.summary td.label {
            text-align: right !important;
            padding-right: 10px;
            color: #222222;
            font-weight: 400;
            border-right: 1px dashed #b0b0b0 !important;
        }

        table.items tr.summary.grand td.label,
        table.items tr.summary.grand td.total {
            font-weight: 700;
        }

        .amount-words {
            font-size: 13px;
            font-weight: 700;
            color: #111111;
            margin: 16px 0 18px;
            line-height: 1.2;
        }

        .signoff {
            font-size: 12px;
            color: #222222;
            line-height: 1.35;
            margin: 0;
        }

        .signoff .rule {
            display: block;
            width: 14px;
            border-top: 1.5px solid #111111;
            margin: 14px 0 6px 0;
            height: 0;
            line-height: 0;
            font-size: 0;
        }

        .signoff .closing {
            display: block;
            font-weight: 400;
            font-size: 12px;
            color: #222222;
            margin: 0;
        }

        .signoff-name {
            display: block;
            width: 100%;
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            color: #111111;
            margin: 22px 0 0 0;
            line-height: 1.2;
        }

        .payment {
            margin-top: 38px;
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 11px;
        }

        .payment-title {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 6px 0;
            padding: 0;
            color: #111111;
            line-height: 11.5px;
        }

        .payment-account {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #555555;
            margin: 0 0 2px 0;
            padding: 0;
            line-height: 11.5px;
        }

        .payment-account .value {
            color: #111111;
            font-weight: 700;
        }

        .payment-bank {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #808080;
            font-weight: 400;
            line-height: 11.5px;
            margin: 0;
            padding: 0 0 0 14px;
        }

        .payment-bank .label {
            color: #808080;
            font-weight: 400;
        }

        .payment-bank .value {
            color: #111111;
            font-weight: 700;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/invoice-logo.png');
        $currency = $invoice->currency ?: 'US$';
        $fmtMoney = function (float $amount, bool $withCents = true) use ($currency): string {
            return $currency.number_format($amount, $withCents ? 2 : 0);
        };
        $fmtQty = function ($qty): string {
            $formatted = number_format((float) $qty, 2, '.', '');

            return rtrim(rtrim($formatted, '0'), '.');
        };
    @endphp

    @if (is_file($logoPath))
        <img class="logo" src="{{ $logoPath }}" alt="codrix">
    @endif

    @php
        $fromLines = array_values(array_filter([
            $invoice->from_company_name,
            filled($invoice->from_registration_no) ? 'Registration No: '.$invoice->from_registration_no : null,
            ...preg_split("/\r\n|\n|\r/", (string) $invoice->from_address),
            filled($invoice->from_email) ? 'Email: '.$invoice->from_email : null,
            filled($invoice->from_mobile) ? 'Mobile: '.$invoice->from_mobile : null,
        ], fn ($line) => filled(trim((string) $line))));

        $toLines = array_values(array_filter([
            $invoice->to_name,
            $invoice->to_phone,
            ...preg_split("/\r\n|\n|\r/", (string) $invoice->to_address),
            $invoice->to_company,
        ], fn ($line) => filled(trim((string) $line))));
    @endphp

    <table class="parties">
        <tr>
            <td>
                <div class="party-label">FROM:</div>
                <div class="party-body">{!! nl2br(e(implode("\n", array_map('trim', $fromLines))), false) !!}</div>
            </td>
            <td class="to">
                <div class="party-label">TO:</div>
                <div class="party-body">{!! nl2br(e(implode("\n", array_map('trim', $toLines))), false) !!}</div>
            </td>
        </tr>
    </table>

    <div class="title-block">
        <div class="invoice-title">INVOICE</div>
        <table class="title-meta" cellspacing="0" cellpadding="0">
            <tr>
                <td class="period-cell">
                    <div class="invoice-period">{{ $invoice->invoice_date?->format('M-Y') }}</div>
                </td>
                <td class="date-cell">
                    <div class="invoice-date">Date: {{ $invoice->invoice_date?->format('d-M-Y') }}</div>
                </td>
                <td class="meta-spacer">&nbsp;</td>
            </tr>
        </table>
    </div>

    <div class="invoice-table-wrap">
        <table class="items" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="desc">Description</th>
                    <th class="spacer">&nbsp;</th>
                    <th class="qty">Qty</th>
                    <th class="unit">Unit Price</th>
                    <th class="total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td class="desc">{{ $item->description }}</td>
                        <td class="spacer">&nbsp;</td>
                        <td class="qty">{{ $fmtQty($item->qty) }}</td>
                        <td class="unit">{{ $fmtMoney((float) $item->unit_price, true) }}</td>
                        <td class="total">{{ $fmtMoney((float) $item->total, false) }}</td>
                    </tr>
                @endforeach

                <tr class="summary">
                    <td class="desc">&nbsp;</td>
                    <td class="spacer">&nbsp;</td>
                    <td class="qty">&nbsp;</td>
                    <td class="label">Subtotal</td>
                    <td class="total">{{ $fmtMoney((float) $invoice->subtotal, false) }}</td>
                </tr>
                <tr class="summary grand">
                    <td class="desc">&nbsp;</td>
                    <td class="spacer">&nbsp;</td>
                    <td class="qty">&nbsp;</td>
                    <td class="label">Total</td>
                    <td class="total">{{ $fmtMoney((float) $invoice->total, false) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="invoice-footer-wrap">
        @if (filled($invoice->amount_in_words))
            <div class="amount-words">{{ $invoice->amount_in_words }}</div>
        @endif

        <div class="signoff">
            <span class="rule">&nbsp;</span>
            <span class="closing">{{ $invoice->closing_text ?: 'Yours sincerely,' }}</span>
        </div>
    </div>

    <div class="signoff-name">{{ $invoice->sign_off ?: 'Xcodrix' }}</div>

    @if ($invoice->payment_method_name || $invoice->payment_iban || $invoice->payment_bank_name)
        <div class="payment">
            <div class="payment-title">
                PAYMENT METHOD @if ($invoice->payment_method_name){{ strtoupper($invoice->payment_method_name) }}@endif
            </div>
            @if ($invoice->payment_account_title)
                <div class="payment-account">
                    &bull; Account Title / Name: <span class="value">{{ $invoice->payment_account_title }}</span>
                </div>
            @endif
            <div class="payment-bank">
                @php $bankRows = []; @endphp
                @if ($invoice->payment_bank_name)
                    @php $bankRows[] = '&bull; <span class="label">Bank Name:</span> <span class="value">'.e($invoice->payment_bank_name).'</span>'; @endphp
                @endif
                @if ($invoice->payment_iban)
                    @php $bankRows[] = '&bull; <span class="label">IBAN Number:</span> <span class="value">'.e($invoice->payment_iban).'</span>'; @endphp
                @endif
                @if ($invoice->payment_swift_bic)
                    @php $bankRows[] = '&bull; <span class="label">SWIFT / BIC Code:</span> <span class="value">'.e($invoice->payment_swift_bic).'</span>'; @endphp
                @endif
                @if ($invoice->payment_branch_code)
                    @php $bankRows[] = '&bull; <span class="label">Branch Code / Address:</span> <span class="value">'.e($invoice->payment_branch_code).'</span>'; @endphp
                @endif
                {!! implode('<br>', $bankRows) !!}
            </div>
        </div>
    @endif
</body>
</html>
