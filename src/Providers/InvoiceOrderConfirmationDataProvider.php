<?php

namespace Invoice\Providers;

use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Plugin\ConfigRepository;
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

        $content = '';

        if($mop ==$invoiceHelper->getInvoiceMopId())
        {
            $lang = $service->getLang();
            if($settings->getSetting('showBankData', $lang))
            {
                $content .= $twig->render('Invoice::BankDetails');
            }

            if($settings->getSetting('showDesignatedUse', $lang))
            {
                $content .=  $twig->render('Invoice::DesignatedUse', array());
            }
        }

        return $content;
    }
}