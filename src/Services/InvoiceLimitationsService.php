<?php

namespace Invoice\Services;

use Invoice\Helper\SettingsHelper;
use Plenty\Modules\Account\Contact\Models\Contact;
use Plenty\Modules\Account\Contact\Models\ContactAllowedMethodOfPayment;
use Plenty\Modules\Frontend\Contracts\CurrencyExchangeRepositoryContract;
use Plenty\Modules\Order\Models\OrderAmount;

/**
 * Class InvoiceLimitationsService
 *
 * @author emmanouil.stafilarakis <emmanouil.stafilarakis@plentymarkets.com>
 *
 * @package Invoice\Services
 */
class InvoiceLimitationsService
{
    /**
     * Get whether all payment method limitations are respected or not.
     * 
     * @param SettingsHelper $settingsHelper    The setting helper 
     * @param bool           $isGuest           Whether or not the current customer is a guest or not
     * @param OrderAmount    $amount            The total amount
     * @param int            $shippingCountryId The ID of the shipping country
     * @param int            $billingAddressId  The ID of the billing address
     * @param int            $deliveryAddressId The ID of the delivery address
     * @param Contact|null   $contact           The contact instance, if customer is not a quest
     * 
     * @return bool
     */
    public function respectsAllLimitations(
        SettingsHelper $settingsHelper,
        bool $isGuest,
        OrderAmount $amount,
        int $shippingCountryId,
        int $billingAddressId = null,
        int $deliveryAddressId = null,
        Contact $contact = null
    ): bool
    {
        //  First: Check the activated countries
        if(!$settingsHelper->isCountryActive($shippingCountryId)) {
            //  Payment has not activated the requested country
            //  Possible reasons are:
            //  1. No country is configured at all
            //  2. The requested country is not activated
            //  => if guest return false, else check payment allowance for contact
            if($isGuest || !$this->explicitlyAllowedFor($contact)) {
                return false;
            }
        }
        
        //  Second: Check guests
        if($isGuest && !$settingsHelper->guestsAllowed()) {
            return false;
        }
        
        //  Third: Check the addresses
        //          Addresses are equal when: $deliveryAddressId === null || $billingAddressId === $deliveryAddressId
        if($settingsHelper->shouldHaveIdenticalAddresses() && $deliveryAddressId !== null && $billingAddressId !== $deliveryAddressId) {
            return false;
        }
        
        //  Forth: Check minimum order count
        $minOrderCount = $settingsHelper->minimumOrderCount();
        if($minOrderCount > 0) {
            if($isGuest || is_null($contact)) {
                //  Guests have no orders, the current order is the first one
                return false;
            }
            $orderCount = $contact->orderSummary->orderCount;
            if($minOrderCount > $orderCount) {
                return false;
            }
        }
        
        if($amount->invoiceTotal > 0.0) {
            /** @var CurrencyExchangeRepositoryContract $currencyService */
            $currencyService = pluginApp(CurrencyExchangeRepositoryContract::class);
            
            //  Fifth: Check minimum amount
            $minAmount = (float)$currencyService->convertFromDefaultCurrency($amount->currency, $settingsHelper->minimumAmount());
            if($minAmount > 0.0 && $minAmount > $amount->invoiceTotal) {
                return false;
            }
            
            //  Sixth: Check maximum amount
            $maxAmount = (float)$currencyService->convertFromDefaultCurrency($amount->currency, $settingsHelper->maximumAmount());
            if($maxAmount > 0.0 && $maxAmount < $amount->invoiceTotal) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get whether or not the invoice payment method is excplicitly allowed for the contact.
     * 
     * @param Contact|null $contact
     * 
     * @return bool
     */
    private function explicitlyAllowedFor(Contact $contact = null): bool
    {
        if(is_null($contact)) {
            return false;
        }
        /** @var ContactAllowedMethodOfPayment $allowed */
        $allowed = $contact->allowedMethodsOfPayment->where('methodOfPaymentId', '=', 2)->first();
        return $allowed->allowed == 1;
    }
}

