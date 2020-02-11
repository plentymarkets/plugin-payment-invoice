<?php //strict

namespace Invoice\Helper;

use Invoice\Services\SettingsService;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Account\Contact\Models\Contact;
use Plenty\Modules\Account\Contact\Models\ContactAllowedMethodOfPayment;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Frontend\Services\AccountService;

/**
 * Class InvoiceHelper
 *
 * @package Invoice\Helper
 */
class InvoiceHelper
{
    /**
     * Load the ID of the payment method
     * Return the ID for the payment method
     *
     * @return int
     */
    public function getInvoiceMopId()
    {
        /**
         * Use the payment method id from the system
         */
        return 2;
    }

    /**
     * Return null if none of the conditions are accomplished otherwise false or true
     *
     * @param AccountService $accountService
     * @param SettingsService $settings
     * @return bool
     * @throws \Plenty\Exceptions\ValidationException
     */
    public function isInvoiceAvailableForCurrentLoggedInCustomer(
        AccountService $accountService,
        SettingsService $settings,
        Basket $basket
    )
    {

        if($accountService->getIsAccountLoggedIn() && $basket->customerId > 0) {

            /** @var ContactRepositoryContract $contactRepository */
            $contactRepository = pluginApp(ContactRepositoryContract::class);
            $contact = $contactRepository->findContactById($basket->customerId);

            if(!is_null($contact) && $contact instanceof Contact) {

                $allowed = $contact->allowedMethodsOfPayment->first(function($method) {
                    if($method instanceof ContactAllowedMethodOfPayment) {
                        if($method->methodOfPaymentId == $this->getInvoiceMopId() && $method->allowed) {
                            return true;
                        }
                    }
                });

                if($allowed) {
                    return true;
                }

                if(
                    (int)$settings->getSetting('quorumOrders') > 0
                    && $contact->orderSummary->orderCount < $settings->getSetting('quorumOrders')
                ) {
                    return false;
                }
            }

        } elseif ((int)$settings->getSetting('quorumOrders') > 0 && !$accountService->getIsAccountLoggedIn()) {
            return false;
        }

        return null;

    }
}
