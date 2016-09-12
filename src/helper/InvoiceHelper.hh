<?hh //strict

namespace Invoice\Helper;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Payment\Method\Models\PaymentMethod;

class InvoiceHelper
{
    private PaymentMethodRepositoryContract $paymentMethodRepository;

    public function __construct(PaymentMethodRepositoryContract $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function createMopIfNotExists():void
    {
        if($this->getMop() == 'no_paymentmethod_found')
        {
            $paymentMethodData = array( 'pluginKey' => 'plenty_invoice',
                'paymentKey' => 'INVOICE',
                'name' => 'Rechnung');

            $this->paymentMethodRepository->createPaymentMethod($paymentMethodData);
        }
    }

    public function getMop():mixed
    {
        $paymentMethods = $this->paymentMethodRepository->allForPlugin('plenty_invoice');

        if( !is_null($paymentMethods) )
        {
            foreach($paymentMethods as $paymentMethod)
            {
                if($paymentMethod->paymentKey == 'INVOICE')
                {
                    return $paymentMethod->id;
                }
            }
        }

        return 'no_paymentmethod_found';
    }

}
