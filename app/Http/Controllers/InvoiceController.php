<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function get_all_invoice() {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return response() -> json([
            'invoices' => $invoices
        ],200);
    }

    public function search_invoice(Request $request) {
        $search = $request->get('s');
        if ($search != null) {
            $invoices = Invoice::with('customer')
                        ->where('id', 'LIKE', "%$search%")
                        ->get();    
            return response()->json([
                'invoices' => $invoices
            ],200);            
        } else {
            return  $this->get_all_invoice();
        }
    }

    public function create_invoice(Request $request) {
        $counter = Counter::where('Key', 'invoice')->first();
        $random = Counter::where('Key', 'invoice')->first();
        $invoice = Invoice::orderBy('id', 'DESC')->first();
        if ($invoice) {
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        } else {
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_amd_conditions' => 'Default Terms and Conditions',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quanitity' => 1,
            ]
        ];
        return response()->json($formData);
    }
}
