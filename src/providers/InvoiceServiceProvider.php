<?php //strict

namespace Invoice\Providers;

use Plenty\Modules\Payment\Events\Checkout\ExecutePayment;
use Plenty\Modules\Payment\Events\Checkout\GetPaymentMethodContent;
use Plenty\Plugin\ServiceProvider;
use Invoice\Helper\InvoiceHelper;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use Plenty\Plugin\Events\Dispatcher;

use Invoice\Methods\InvoicePaymentMethod;

use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;

/**
 * Class InvoiceServiceProvider
 * @package Invoice\Providers
 */
 class InvoiceServiceProvider extends ServiceProvider
 {
     public function register()
     {

     }

     /**
      * boot function called if the plugin is aktive
      *
      * @param InvoiceHelper $paymentHelper
      * @param PaymentMethodContainer $payContainer
      * @param Dispatcher $eventDispatcher
      */
     public function boot(  InvoiceHelper $paymentHelper,
                            PaymentMethodContainer $payContainer,
                            Dispatcher $eventDispatcher)
     {
         $paymentHelper->createMopIfNotExists();

         $payContainer->register('plenty_invoice::INVOICE', InvoicePaymentMethod::class,
                                [ AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class ]
         );

         // Listen for the event that gets the payment method content
         $eventDispatcher->listen(GetPaymentMethodContent::class,
                 function(GetPaymentMethodContent $event) use( $paymentHelper)
                 {
                     if($event->getMop() == $paymentHelper->getPaymentMethod())
                     {
                         $event->setValue('');
                         $event->setType('continue');
                     }
                 });

         // Listen for the event that gets the payment method content
         $eventDispatcher->listen(ExecutePayment::class,
             function(ExecutePayment $event) use( $paymentHelper)
             {
                 if($event->getMop() == $paymentHelper->getPaymentMethod())
                 {
                     $event->setValue('<h1>Rechungskauf<h1>');
                     $event->setType('htmlContent');
                 }
             });
     }
 }