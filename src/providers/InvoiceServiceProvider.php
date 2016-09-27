<?php //strict

namespace Invoice\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use Invoice\Methods\InvoicePaymentMethod;
use Invoice\Helper\InvoiceHelper;


/**
 * Class InvoiceServiceProvider
 * @package Invoice\Providers
 */
 class InvoiceServiceProvider extends ServiceProvider
 {
     public function register()
     {

     }

     public function boot(InvoiceHelper $paymentHelper,
                          PaymentMethodContainer $payContainer)
     {
       $paymentHelper->createMopIfNotExists();

       $payContainer->register('plenty_invoice::INVOICE', InvoicePaymentMethod::class,
           [ \Plenty\Modules\Basket\Events\Basket\AfterBasketChanged::class,
             \Plenty\Modules\Basket\Events\Basket\AfterBasketCreate::class]);
     }
 }
