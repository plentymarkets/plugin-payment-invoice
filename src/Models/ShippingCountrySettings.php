<?php

namespace Invoice\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * Class ShippingCountrySettings
 *
 * @property int $id
 * @property int $plentyId
 * @property int $shippingCountryId
 */
class ShippingCountrySettings extends Model
{
    const MODEL_NAMESPACE = 'Invoice\Models\ShippingCountrySettings';

    public $id;
    public $plentyId;
    public $shippingCountryId;


    /**
     * @return string
     */
    public function getTableName():string
    {
        return 'Invoice::shippingCountries';
    }
}