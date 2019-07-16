<?php
namespace Invoice\Assistants\SettingsHandlers;
use Plenty\Modules\Wizard\Contracts\WizardSettingsHandler;

class InvoiceAssistantSettingsHandler implements WizardSettingsHandler
{
    /**
     * @param array $parameter
     * @return bool
     */
    public function handle(array $parameter)
    {
        $data = $parameter['data'];

        return true;
    }
}
