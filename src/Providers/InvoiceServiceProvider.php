<?php //strict

namespace Invoice\Providers;

use Invoice\Extensions\InvoiceTwigServiceProvider;
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
use Plenty\Plugin\Templates\Twig;

/**
 * Class InvoiceServiceProvider
 * @package Invoice\Providers
 */
 class InvoiceServiceProvider extends ServiceProvider
 {
     public function register()
     {
         $this->getApplication()->register(InvoiceRouteServiceProvider::class);
     }

     /**
      * Boot additional services for the payment method
      *
      * @param Twig $twig
      * @param InvoiceHelper $paymentHelper
      * @param PaymentMethodContainer $payContainer
      * @param Dispatcher $eventDispatcher
      */
     public function boot(  Twig $twig,
                            InvoiceHelper $paymentHelper,
                            PaymentMethodContainer $payContainer,
                            Dispatcher $eventDispatcher)
     {

         $twig->addExtension(InvoiceTwigServiceProvider::class);

         // Register the Invoice payment method in the payment method container
         $payContainer->register('plenty::INVOICE', InvoicePaymentMethod::class,
                                [ AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class ]
         );

         // Listen for the event that gets the payment method content
         $eventDispatcher->listen(GetPaymentMethodContent::class,
                 function(GetPaymentMethodContent $event) use( $paymentHelper)
                 {
                     if($event->getMop() == $paymentHelper->getInvoiceMopId())
                     {
                         $event->setValue('');
                         $event->setType('continue');
                     }
                 });

         // Listen for the event that executes the payment
         $eventDispatcher->listen(ExecutePayment::class,
             function(ExecutePayment $event) use( $paymentHelper)
             {
                 if($event->getMop() == $paymentHelper->getInvoiceMopId())
                 {
                     $event->setValue('<h1>Rechungskauf<h1>');
                     $event->setType('htmlContent');
                 }
             });
     }
 }
