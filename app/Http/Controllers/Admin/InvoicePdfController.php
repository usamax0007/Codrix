<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InvoicePdfController extends Controller
{
    public function __invoke(Invoice $invoice): Response|SymfonyResponse
    {
        $invoice->load('items');

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
        ])->setPaper('a4');

        $filename = $invoice->invoice_number.'.pdf';

        return $pdf->download($filename);
    }
}
