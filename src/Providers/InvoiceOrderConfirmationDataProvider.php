<?php

namespace Invoice\Providers;

use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Templates\Twig;

use Invoice\Helper\InvoiceHelper;
use Invoice\Services\SessionStorageService;
use Invoice\Services\SettingsService;
/**
 * Class InvoiceOrderConfirmationDataProvider
 * @package Invoice\Providers
 */
class InvoiceOrderConfirmationDataProvider
{
    public function call(   Twig $twig, SettingsService $settings, InvoiceHelper $invoiceHelper,
                            SessionStorageService $service, $args)
    {
        $mop = $service->getOrderMopId();
        $orderId = null;
        $content = '';

        /*
         * Load the method of payment id from the order
         */
        $order = $args[0];
        if($order instanceof Order) {
            $orderId = $order->id;
            foreach ($order->properties as $property) {
                if($property->typeId == 3) {
                    $mop = $property->value;
                    break;
                }
            }
        } elseif(is_array($order)) {
            $orderId = $order['id'];
            foreach ($order['properties'] as $property) {
                if($property['typeId'] == 3) {
                    $mop = $property['value'];
                    break;
                }
            }
        }

        if($mop ==$invoiceHelper->getInvoiceMopId())
        {
            $lang = $service->getLang();
            if($settings->getSetting('showBankData', $lang))
            {
                $content .= $twig->render('Invoice::BankDetails');
            }

            if($settings->getSetting('showDesignatedUse', $lang))
            {
                $content .=  $twig->render('Invoice::DesignatedUse', ['orderId'=>$orderId]);
            }
        }

        return $content;
    }
}