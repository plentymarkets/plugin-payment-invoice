<?php

namespace Invoice\Helper;

use Invoice\Services\SettingsService;

/**
 * Class SettingsHelper
 *
 * @author emmanouil.stafilarakis <emmanouil.stafilarakis@plentymarkets.com>
 *
 * @package Invoice\Helper
 */
class SettingsHelper
{
    /**
     * The current settings
     * 
     * @var SettingsService
     */
    private $settings;
    
    /**
     * The current plenty id.
     * 
     * @var int
     */
    private $plentyId;

    /**
     * SettingsHelper constructor.
     * 
     * @param SettingsService $settings The settings service
     * @param int             $plentyId The plenty ID
     */
    public function __construct(SettingsService $settings, int $plentyId)
    {
        $this->settings = $settings;
        $this->plentyId = $plentyId;
    }

    /**
     * Get whether the country is active or not
     * 
     * @param int $countryId    The country ID to be checked.
     * 
     * @return bool
     */
    public function isCountryActive(int $countryId): bool
    {
        $countries = $this->settings->getShippingCountriesByPlentyId($this->plentyId);
        return in_array($countryId, $countries);
    }

    /**
     * Get whether or not the payment method has active countries.
     * 
     * @return bool
     */
    public function hasActiveCountries(): bool
    {
        $countries = $this->settings->getShippingCountriesByPlentyId($this->plentyId);
        return count($countries) > 0;
    }

    /**
     * Get the minimum amount for using the payment method.
     * 
     * @return float
     */
    public function minimumAmount(): float
    {
        return (float)$this->getSettingFloatValue('minimumAmount');
    }
    
    /**
     * Get the maximum amount for using the payment method.
     * 
     * @return float
     */
    public function maximumAmount(): float
    {
        return (float)$this->getSettingFloatValue('maximumAmount');
    }
    
    /**
     * Get the minimum amount for using the payment method.
     * 
     * @return float
     */
    public function minimumOrderCount(): int
    {
        return (int)$this->getSettingIntValue('quorumOrders');
    }

    /**
     * Get whether the invoice payment is allowed for guests or not.
     * 
     * @return bool
     */
    public function guestsAllowed():bool
    {
        return $this->getSettingIntValue('disallowInvoiceForGuest', 1) != 1;
        
    }

    /**
     * Get whether the delivery address should equals the billing address or not.
     * 
     * @return bool
     */
    public function shouldHaveIdenticalAddresses(): bool
    {
        return $this->getSettingIntValue('invoiceEqualsShippingAddress', 0) != 0;
    }

    /**
     * Get an integer setting.
     * 
     * @param string $setting   The setting key
     * @param int    $default   The default value, if setting key is not set
     * 
     * @return int
     */
    private function getSettingIntValue(string $setting, int $default = 0): int
    {
        $settings = $this->settings->getSettingsForPlentyId($this->plentyId, 'de');
        return isset($settings[$setting]) ? (int)$settings[$setting] : $default;
    }

    /**
     * Get a float setting.
     * 
     * @param string $setting   The setting key
     * @param float  $default   The default value, if setting key is not set
     * 
     * @return float
     */
    private function getSettingFloatValue(string $setting, float $default = 0.0): float
    {
        $settings = $this->settings->getSettingsForPlentyId($this->plentyId, 'de');
        return isset($settings[$setting]) ? (float)$settings[$setting] : $default;
    }
}

