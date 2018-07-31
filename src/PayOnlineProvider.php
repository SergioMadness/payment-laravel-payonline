<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\services\ReceiptService;
use professionalweb\payment\contracts\PaymentFacade;
use professionalweb\payment\interfaces\PayOnlineService;
use professionalweb\payment\drivers\payonline\PayOnlineDriver;
use professionalweb\payment\drivers\payonline\PayOnlineProtocol;
use professionalweb\payment\contracts\ReceiptService as IReceiptService;

/**
 * PayOnline payment provider
 * @package professionalweb\payment
 */
class PayOnlineProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        app(PaymentFacade::class)->registerDriver(PayOnlineService::PAYMENT_PAYONLINE, PayOnlineService::class);
    }

    /**
     * Bind two classes
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PayOnlineService::class, function ($app) {
            return (new PayOnlineDriver(config('payment.payonline')))->setTransport(
                new PayOnlineProtocol(config('payment.payonline.merchantId'), config('payment.payonline.secretKey'))
            );
        });
        $this->app->bind(PayService::class, function ($app) {
            return (new PayOnlineDriver(config('payment.payonline')))->setTransport(
                new PayOnlineProtocol(config('payment.payonline.merchantId'), config('payment.payonline.secretKey'))
            );
        });
        $this->app->bind(PayOnlineDriver::class, function ($app) {
            return (new PayOnlineDriver(config('payment.payonline')))->setTransport(
                new PayOnlineProtocol(config('payment.payonline.merchantId'), config('payment.payonline.secretKey'))
            );
        });
        $this->app->bind('\professionalweb\payment\PayOnline', function ($app) {
            return (new PayOnlineDriver(config('payment.payonline')))->setTransport(
                new PayOnlineProtocol(config('payment.payonline.merchantId'), config('payment.payonline.secretKey'))
            );
        });
        $this->app->bind(IReceiptService::class, function () {
            return new ReceiptService(config('payment.payonline.merchantId'), config('payment.payonline.secretKey'));
        });
        $this->app->bind('\Payment', Payment::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PayService::class, PayOnlineDriver::class, '\professionalweb\payment\PayOnline', '\Payment', IReceiptService::class];
    }
}