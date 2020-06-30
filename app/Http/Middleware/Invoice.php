<?php

namespace App\Http\Middleware;

use App\Models\Invoice as InvoiceModel;
use Closure;
use Illuminate\Http\Request;

class Invoice
{
    /**
     * Handle an incoming request and add $invoice instance
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $groupId = $request->route('id');
        /** @var InvoiceModel $invoice */
        $invoice = InvoiceModel::find($groupId);
        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice not found'
            ], 404);
        }
        if ($invoice->status !== InvoiceModel::STATUS_SENT) {
            return response()->json([
                'message' => 'Invoice already in use'
            ], 403);
        }
        $request->merge(['invoice' => $invoice]);
        return $next($request);
    }
}
