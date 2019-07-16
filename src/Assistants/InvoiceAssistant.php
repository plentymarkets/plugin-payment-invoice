<?php
namespace Invoice\Assistants;

use Invoice\Assistants\SettingsHandlers\InvoiceAssistantSettingsHandler;
use Plenty\Modules\Order\Shipping\Countries\Contracts\CountryRepositoryContract;
use Plenty\Modules\Wizard\Services\WizardProvider;
use Plenty\Plugin\Application;

class InvoiceAssistant extends WizardProvider
{
    /**
     * @var string
     */
    private $language;

    /**
     * @var array
     */
    private $deliveryCountries;

    protected function structure()
    {
        return [
            "title" => 'invoiceAssistant.assistantTitle',
            "shortDescription" => 'invoiceAssistant.assistantShortDescription',
            "iconPath" => $this->getIcon(),
            "settingsHandlerClass" => InvoiceAssistantSettingsHandler::class,
            "translationNamespace" => "Invoice",
            "key" => "payment-invoice-assistant",
            "topics" => ["payment"],
            "priority" => 500,
            "steps" => [
                "stepOne" => [
                    "title" => "invoiceAssistant.stepOneTitle",
                    "sections" => [
                        [
                            "title" => 'invoiceAssistant.shippingCountriesTitle',
                            "description" => 'invoiceAssistant.shippingCountriesDescription',
                            "form" => [
                                "countries" => [
                                    'type' => 'checkboxGroup',
                                    'defaultValue' => [],
                                    'options' => [
                                        "required" => false,
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
                            "title" => 'invoiceAssistant.showBankDataTitle',
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
                            "title" => 'invoiceAssistant.invoiceAddressEqualShippingAddressTitle',
                            "description" => '',
                            "form" => [
                                "showBankData" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'invoiceAssistant.invoiceAddressEqualShippingAddress'
                                    ]
                                ],
                            ],
                        ],
                    ],
/*                    "sections" => [
                        [
                            "title" => "Sprache",
                            "form" => [
                                "lang" => [
                                    "type" => "select",
                                    "options" => [
                                        "name" => "invoiceAssistant.language",
                                        "listBoxValues" => [
                                            [
                                                "value" =>   'de',
                                                "caption" => 'invoiceAssistant.german',
                                            ],
                                            [
                                                "value" =>   'en',
                                                "caption" => 'invoiceAssistant.english'
                                            ],
                                            [
                                                "value" =>   'bg',
                                                "caption" => 'invoiceAssistant.bulgarian',
                                            ],
                                            [
                                                "value" =>   'fr',
                                                "caption" => 'invoiceAssistant.french'
                                            ],
                                            [
                                                "value" =>   'it',
                                                "caption" => 'invoiceAssistant.italian'
                                            ],
                                            [
                                                "value" =>   'es',
                                                "caption" => 'invoiceAssistant.spanish'
                                            ],
                                            [
                                                "value" =>   'tr',
                                                "caption" => 'invoiceAssistant.turkish'
                                            ],
                                            [
                                                "value" =>   'nl',
                                                "caption" => 'invoiceAssistant.dutch'
                                            ],
                                            [
                                                "value" =>   'pl',
                                                "caption" => 'invoiceAssistant.polish',
                                            ],
                                            [
                                                "value" =>   'pt',
                                                "caption" => 'invoiceAssistant.portuguese'
                                            ],
                                            [
                                                "value" =>   'nn',
                                                "caption" => 'invoiceAssistant.norwegian'
                                            ],
                                            [
                                                "value" =>   'da',
                                                "caption" => 'invoiceAssistant.danish'
                                            ],
                                            [
                                                "value" =>   'se',
                                                "caption" => 'invoiceAssistant.swedish'
                                            ],
                                            [
                                                "value" =>   'cz',
                                                "caption" => 'invoiceAssistant.czech'
                                            ],
                                            [
                                                "value" =>   'ro',
                                                "caption" => 'invoiceAssistant.romanian'
                                            ],
                                            [
                                                "value" =>   'ru',
                                                "caption" => 'invoiceAssistant.russian'
                                            ],
                                            [
                                                "value" =>   'sk',
                                                "caption" => 'invoiceAssistant.slovak'
                                            ],
                                            [
                                                "value" =>   'cn',
                                                "caption" => 'invoiceAssistant.chinese'
                                            ],
                                            [
                                                "value" =>   'vn',
                                                "caption" => 'invoiceAssistant.vietnamese'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "title" => "Name",
                            "description" => "",
                            "form" => [
                                "name" => [
                                    "type" => "text"
                                ]
                            ]
                        ],
                        [
                            "title" => "Infoseite",
                            "form" => [
                                "infoPage" => [
                                    "type" => "select",
                                    "options" => [
                                        "name" => "invoiceAssistant.infoPage",
                                        "listBoxValues" => [
                                            [
                                                "value" =>   0,
                                                "caption" => '',
                                            ],
                                            [
                                                "value" =>   1,
                                                "caption" => 'invoiceAssistant.internalInfoPage',
                                            ],
                                            [
                                                "value" =>   2,
                                                "caption" => 'invoiceAssistant.externalInfoPage'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ]*/
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
                                        'name' => 'invoiceAssistant.infoPageCategoryInput',
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
                                    'defaultValue' => 'internal',
                                    'options' => [
                                        "required" => false,
                                        'name' => 'invoiceAssistant.infoPageTypeName',
                                        'listBoxValues' => [
                                            [
                                                "caption" => 'invoiceAssistant.infoPageInternal',
                                                "value" => 'internal',
                                            ],
                                            [
                                                "caption" => 'invoiceAssistant.infoPageExternal',
                                                "value" => 'external',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => 'invoiceAssistant.infoPageNameInternal',
                            "condition" => 'info_page_toggle && info_page_type == "internal"',
                            "form" => [
                                "internal_info_page" => [
                                    "type" => 'category',
                                    'defaultValue' => '',
                                    'isVisible' => "info_page_toggle == true && info_page_type == 'internal'",
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
                            "condition" => 'info_page_toggle && info_page_type == "external"',
                            "form" => [
                                "external_info_page" => [
                                    'type' => 'text',
                                    'defaultValue' => '',
                                    'options' => [
                                        'required'=> false,
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
                                    'showPreview' => true
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.sectionPaymentMethodIconTitle',
                            "description" => 'invoiceAssistant.sectionPaymentMethodIconDescription',
                            "form" => [
                                "debitPaymentMethodIcon" => [
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
                            "title" => "invoiceAssistant.infoPageLimitInputTitle",
                            "form" => [
                                "limit_toggle" => [
                                    'type' => 'toggle',
                                    'options' => [
                                        'name' => 'invoiceAssistant.infoPageLimitInput',
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.quorumOrders',
                            "description" => '',
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
                            "description" => '',
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
                            "description" => '',
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
                                    'options' => [
                                        'name' => 'invoiceAssistant.showDesignatedUse'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'invoiceAssistant.designatedUse',
                            "condition" => 'showDesignatedUse',
                            "description" => '',
                            "form" => [
                                "designatedUse" => [
                                    'type' => 'text',
                                    'defaultValue' => "",
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

        if ($this->getLanguage() != 'de') {
            return $app->getUrlPath('invoice').'/images/icon_en.png';
        }

        return $app->getUrlPath('invoice').'/images/icon.png';
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
