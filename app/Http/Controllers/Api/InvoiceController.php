<?php

namespace App\Http\Controllers\Api;

use App\Helpers\YandexKassaHelper;
use App\Http\Requests\StoreInvoice;
use App\Models\Invoice;
use App\Notifications\NewInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Common\Exceptions\BadApiRequestException;
use YandexCheckout\Common\Exceptions\ForbiddenException;
use YandexCheckout\Common\Exceptions\InternalServerError;
use YandexCheckout\Common\Exceptions\NotFoundException;
use YandexCheckout\Common\Exceptions\ResponseProcessingException;
use YandexCheckout\Common\Exceptions\TooManyRequestsException;
use YandexCheckout\Common\Exceptions\UnauthorizedException;

class InvoiceController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $schools = Invoice::orderBy('id', 'desc')->get();
        return $this->sendResponse($schools->toArray(), 'Invoices retrieved successfully.');
    }

    /**
     * @param StoreInvoice $request
     * @return JsonResponse
     */
    public function store(StoreInvoice $request)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::create($request->input());
        $invoice->notify(new NewInvoice());
        return $this->sendResponse($invoice->toArray(), 'Invoice created successfully.');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $invoice = Invoice::find($id);
        if (is_null($invoice)) {
            return $this->sendError('Invoice not found.');
        }
        return $this->sendResponse($invoice->toArray(), 'Invoice retrieved successfully.');
    }

    /**
     * Store payment attempt. Create Yandex Kassa payment URI and redirect user to it (commented)
     *
     * @param Request $request
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function pay(Request $request)
    {
        /** @var Invoice $invoice */
        $invoice = $request->invoice;

        $redirectUrl = (new YandexKassaHelper())->create($invoice->amount, $invoice->description, $invoice->id);

        echo $redirectUrl;

//        return Redirect::to($redirectUrl);
    }

    /**
     * Yandex Kassa webhock
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function check()
    {
        (new YandexKassaHelper())->check();
    }
}
