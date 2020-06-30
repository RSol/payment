<?php


namespace App\Helpers;


use App\Models\Invoice;
use App\Models\MerchantLog;
use Exception;
use Illuminate\Support\Facades\Config;
use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Common\Exceptions\BadApiRequestException;
use YandexCheckout\Common\Exceptions\ExtensionNotFoundException;
use YandexCheckout\Common\Exceptions\ForbiddenException;
use YandexCheckout\Common\Exceptions\InternalServerError;
use YandexCheckout\Common\Exceptions\NotFoundException;
use YandexCheckout\Common\Exceptions\ResponseProcessingException;
use YandexCheckout\Common\Exceptions\TooManyRequestsException;
use YandexCheckout\Common\Exceptions\UnauthorizedException;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use YandexCheckout\Model\PaymentInterface;

class YandexKassaHelper
{
    /**
     * @var Client
     */
    private $_client;

    /**
     * @var string
     */
    private $currency;

    public function __construct()
    {
        $config = Config::get('yandexkassa');

        $this->_client = new Client();
        $this->_client->setAuth($config['login'], $config['password']);

        $this->currency = $config['currency'];
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Create payment and return URI for redirect user to Yandex Kassa
     * @param $amount
     * @param $description
     * @param int $invoiceId
     * @param array $metadata
     * @param null $returnUrl
     * @return mixed|null
     * @throws Exception
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function create($amount, $description, $invoiceId, $metadata = [], $returnUrl = null)
    {
        $metadata['invoice_id'] = $invoiceId;

        if (!$returnUrl) {
            $returnUrl = url('/#/invoice/' . $invoiceId);
        }

        $payment = $this->_client->createPayment(
            [
                'amount' => [
                    'value' => $amount,
                    'currency' => $this->currency,
                ],
                'description' => $description,
                'metadata' => $metadata,
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $returnUrl,
                ],
            ],
            $this->uniqid()
        );

        $confirmation_url = $payment->confirmation->confirmation_url;

        MerchantLog::create([
            'system' => MerchantLog::SYSTEM_YANDEX,
            'invoice_id' => $invoiceId,
            'payment_id' => $payment->id,
            'confirmation_url' => $confirmation_url,
            'metadata' => json_encode($metadata),
        ]);

        return $confirmation_url;
    }

    /**
     * Method to check Yandex Kassa webhook and mark it in system
     * @throws Exception
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
        $request = json_decode(file_get_contents('php://input'), true);

        $notification = new NotificationWaitingForCapture($request);
        $payment = $notification->getObject();

        /**
         * is this payment registered in system
         */
        $log = MerchantLog::where('payment_id', $payment->id)
            ->where('system', MerchantLog::SYSTEM_YANDEX)
            ->first();
        if (!$log) {
            $this->_client->cancelPayment($payment->id, $this->uniqid());
            return;
        }

        /**
         * check payment amount
         */
        $amountFromYandex = (float) $payment->amount->getValue();
        $amountFromInvoice = (float) $log->invoice->amount;
        if ($amountFromInvoice !== $amountFromYandex) {
            $this->_client->cancelPayment($payment->id, $this->uniqid());
            return;
        }

        /**
         * Receive cancel payment from Yandex
         */
        if (!$payment->paid) {
            $log->status = MerchantLog::STATUS_CANCEL;
            $log->save();
            return;
        }

        $log->status = MerchantLog::STATUS_DONE;
        $log->payment_method = json_encode($payment->payment_method->jsonSerialize());
        $log->invoice->status = Invoice::STATUS_RECEIVED;
        $log->push();

        /**
         *
         */
        $this->_client->capturePayment(
            [
                'amount' => $log->amount,
                'currency' => $this->currency,
            ],
            $payment->id,
            $this->uniqid()
        );
    }

    /**
     * @return string
     */
    private function uniqid()
    {
        return uniqid('', true);
    }

    /**
     * @param $paymentId
     * @return PaymentInterface
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException|ExtensionNotFoundException
     */
    public function status($paymentId)
    {
        return $this->_client->getPaymentInfo($paymentId);
    }
}
