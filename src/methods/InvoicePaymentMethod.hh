<?hh // strict

namespace Invoice\Methods;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;

/**
 * Class InvoicePaymentMethod
 * @package Invoice\Methods
 */
class InvoicePaymentMethod extends PaymentMethodService
{
    /**
     * @return bool
     */
    public function isActive():bool
    {
        return true;
    }
}
