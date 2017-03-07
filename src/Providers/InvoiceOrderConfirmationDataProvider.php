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
    public function call(   Twig $twig,
                            SettingsService $settings,
                            BasketRepositoryContract $basketRepositoryContract,
                            InvoiceHelper $invoiceHelper,
                            SessionStorageService $service,
                            $args)
    {
        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();

        $content = '';

        if($basket->methodOfPaymentId == 0)
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