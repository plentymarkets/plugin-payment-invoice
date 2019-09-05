<?php
namespace Invoice\Assistants;

use Invoice\Assistants\DataSources\AssistantDataSource;
use Invoice\Assistants\SettingsHandlers\InvoiceAssistantSettingsHandler;
use Invoice\Services\SettingsService;
use Plenty\Modules\Order\Shipping\Countries\Contracts\CountryRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Services\WizardProvider;
use Plenty\Plugin\Application;

class InvoiceAssistant extends WizardProvider
{
    /** @var SettingsService */
    protected $settings;
    /**
     * @var string
     */
    private $language;

    /**
     * @var WebstoreRepositoryContract
     */
    private $webstoreRepository;

    /**
     * @var Webstore
     */
    private $mainWebstore;

    /**
     * @var Array
     */
    private $webstoreValues;

    /**
     * @var array
     */
    private $deliveryCountries;

    public function __construct(
        WebstoreRepositoryContract $webstoreRepository,
        SettingsService $settings
    ) {
        $this->webstoreRepository = $webstoreRepository;
        $this->settings = $settings;
    }

    protected function structure()
    {
        return [
            "title" => 'invoiceAssistant.assistantTitle',
            "shortDescription" => 'invoiceAssistant.assistantShortDescription',
            "iconPath" => $this->getIcon(),
            "settingsHandlerClass" => InvoiceAssistantSettingsHandler::class,
            'dataSource' => AssistantDataSource::class,
            "translationNamespace" => "Invoice",
            "key" => "payment-invoice-assistant",
            "topics" => ["payment"],
            "priority" => 990,
            "options" => [
                "config_name" => [
                    "type" => 'select',
                    'defaultValue' => $this->getMainWebstore(),
                    "options" => [
                        "name" => 'invoiceAssistant.storeName',
                        'required' => true,
                        'listBoxValues' => $this->getWebstoreListForm(),
                    ],
                ],
            ],
            "steps" => [
                "stepOne" => [
                    "title" => "invoiceAssistant.stepOneTitle",
                    "sections" => [
                        [
                            "title" => 'invoiceAssistant.shippingCountriesTitle',
                            "description" => 'invoiceAssistant.shippingCountriesDescription',
                            "form" => [
                                "shippingCountries" => [
                                    'type' => 'checkboxGroup',
                                    'defaultValue' => [],
                                    'options' => [
                                        'name' => 'invoiceAssistant.shippingCountries',
                                        'checkboxValues' => $this->getCountriesListForm(),
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.allowInvoiceForGuestTitle',
                            "form" => [
                                "allowInvoiceForGuest" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.assistantInvoiceForGuestCheckbox'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.invoiceAddressEqualShippingAddressTitle',
                            "description" => 'invoiceAssistant.invoiceEqualsShippingAddressDescription',
                            "form" => [
                                "invoiceEqualsShippingAddress" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.invoiceAddressEqualShippingAddress'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
                "stepTwo" => [
                    "title" => "invoiceAssistant.stepTwoTitle",
                    "sections" => [
                        [
                            "title" => 'invoiceAssistant.infoPageTitle',
                            "form" => [
                                "info_page_toggle" => [
                                    'type' => 'toggle',
                                    'options' => [
                                        'name' => 'invoiceAssistant.infoPageToggle',
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.infoPageTypeTitle',
                            "description" => 'invoiceAssistant.infoPageTypeDescription',
                            "condition" => 'info_page_toggle',
                            "form" => [
                                "info_page_type" => [
                                    'type' => 'select',
                                    'defaultValue' => '1',
                                    'options' => [
                                        'name' => 'invoiceAssistant.infoPageTypeName',
                                        'listBoxValues' => [
                                            [
                                                "caption" => 'invoiceAssistant.infoPageInternal',
                                                "value" => '1',
                                            ],
                                            [
                                                "caption" => 'invoiceAssistant.infoPageExternal',
                                                "value" => '2',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => 'invoiceAssistant.infoPageNameInternal',
                            "condition" => 'info_page_toggle && info_page_type == 1',
                            "form" => [
                                "internal_info_page" => [
                                    "type" => 'category',
                                    'defaultValue' => '',
                                    'isVisible' => "info_page_toggle && info_page_type == 1",
                                    "displaySearch" => true,
                                    "options" => [
                                        "name" => "invoiceAssistant.infoPageNameInternal"
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => '',
                            "condition" => 'info_page_toggle && info_page_type == 2',
                            "form" => [
                                "external_info_page" => [
                                    'type' => 'text',
                                    'defaultValue' => '',
                                    'options' => [
                                        'pattern'=> "(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})",
                                        'name' => 'invoiceAssistant.infoPageNameExternal',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                "stepThree" => [
                    "title" => 'invoiceAssistant.stepThreeTitle',
                    "sections" => [
                        [
                            "title" => 'invoiceAssistant.sectionLogoTitle',
                            "description" => 'invoiceAssistant.sectionLogoDescription',
                            "form" => [
                                "logo_type_external" => [
                                    'type' => 'toggle',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.logoTypeToggle',
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => 'invoiceAssistant.logoURLDescription',
                            "condition" => 'logo_type_external',
                            "form" => [
                                "logo_url" => [
                                    'type' => 'file',
                                    'defaultValue' => '',
                                    'showPreview' => true,
                                    'options' => [
                                        'name' => 'invoiceAssistant.logoURLTypeName'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.sectionPaymentMethodIconTitle',
                            "description" => 'invoiceAssistant.sectionPaymentMethodIconDescription',
                            "form" => [
                                "invoicePaymentMethodIcon" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => 'false',
                                    'options' => [
                                        'name' => 'invoiceAssistant.assistantPaymentMethodIconCheckbox'
                                    ]
                                ],
                            ],
                        ],
                    ]
                ],
                "stepFour" => [
                    "title" => 'invoiceAssistant.interface',
                    "sections" => [
                        [
                            "title" => 'invoiceAssistant.showBankDataTitle',
                            "description" => 'invoiceAssistant.showBankDataDescription',
                            "showFullDescription" => true,
                            "form" => [
                                "showBankData" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.showBankData'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => "invoiceAssistant.infoPageLimitInputTitle",
                            "form" => [
                                "limit_toggle" => [
                                    'type' => 'toggle',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.infoPageLimitInput',
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.quorumOrders',
                            "description" => 'invoiceAssistant.quorumOrdersDescription',
                            "condition" => "limit_toggle",
                            "form" => [
                                "quorumOrders" => [
                                    'type' => 'number',
                                    'defaultValue' => 0,
                                    'options' => [
                                        'name' => 'invoiceAssistant.quorumOrders',
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.minimumAmount',
                            "description" => 'invoiceAssistant.minimumAmountDescription',
                            "condition" => "limit_toggle",
                            "form" => [
                                "minimumAmount" => [
                                    'type' => 'double',
                                    'isPriceInput' => true,
                                    'defaultValue' => 0,
                                    'options' => [
                                        'name' => 'invoiceAssistant.minimumAmount',
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.maximumAmount',
                            "condition" => "limit_toggle",
                            "description" => 'invoiceAssistant.maximumAmountDescription',
                            "form" => [
                                "maximumAmount" => [
                                    'type' => 'double',
                                    'isPriceInput' => true,
                                    'defaultValue' => 0,
                                    'options' => [
                                        'name' => 'invoiceAssistant.maximumAmount',
                                    ],
                                ],
                            ],
                        ],

                        [
                            "title" => 'invoiceAssistant.showDesignatedUseTitle',
                            "form" => [
                                "showDesignatedUse" => [
                                    'type' => 'toggle',
                                    'defaultValue' => true,
                                    'options' => [
                                        'name' => 'invoiceAssistant.showDesignatedUse'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.designatedUseTitle',
                            "condition" => 'showDesignatedUse',
                            "description" => 'invoiceAssistant.designatedUseDescription',
                            "form" => [
                                "designatedUse" => [
                                    'type' => 'text',
                                    'defaultValue' => "%s",
                                    'options' => [
                                        'name' => 'invoiceAssistant.designatedUse',
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    private function getLanguage()
    {
        if ($this->language === null) {
            $this->language =  \Locale::getDefault();
        }

        return $this->language;
    }

    /**
     * @return string
     */
    private function getIcon()
    {
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('invoice').'/images/icon.png';

        return $icon;
    }

    private function getMainWebstore(){
        if($this->mainWebstore === null) {
            /** @var WebstoreRepositoryContract $webstoreRepository */
            $webstoreRepository = pluginApp(WebstoreRepositoryContract::class);

            $this->mainWebstore = $webstoreRepository->findById(0)->storeIdentifier;
        }
        return $this->mainWebstore;
    }

    /**
     * @return array
     */
    private function getWebstoreListForm()
    {
        if($this->webstoreValues === null)
        {
            $webstores = $this->webstoreRepository->loadAll();
            /** @var Webstore $webstore */
            foreach ($webstores as $webstore) {
                $this->webstoreValues[] = [
                    "caption" => $webstore->name,
                    "value" => $webstore->storeIdentifier,
                ];
            }

            usort($this->webstoreValues, function ($a, $b) {
                return ($a['value'] <=> $b['value']);
            });
        }

        return $this->webstoreValues;
    }

    /**
     * @return array
     */
    private function getCountriesListForm()
    {
        if ($this->deliveryCountries === null) {
            /** @var CountryRepositoryContract $countryRepository */
            $countryRepository = pluginApp(CountryRepositoryContract::class);
            $countries = $countryRepository->getCountriesList(true, ['names']);
            $this->deliveryCountries = [];
            $systemLanguage = $this->getLanguage();
            foreach($countries as $country) {
                $name = $country->names->where('lang', $systemLanguage)->first()->name;
                $this->deliveryCountries[] = [
                    'caption' => $name ?? $country->name,
                    'value' => $country->id
                ];
            }
            // Sort values alphabetically
            usort($this->deliveryCountries, function($a, $b) {
                return ($a['caption'] <=> $b['caption']);
            });
        }
        return $this->deliveryCountries;
    }
}
?>
