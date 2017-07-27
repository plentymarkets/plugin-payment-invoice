<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 26/7/17
 * Time: 12:19
 */

namespace Invoice\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Invoice\Models\Settings;
use Invoice\Services\SettingsService;

/** This migration adds "description" and "infoPageType" to the Settings */
class CreateSettings_1_1_0
{
    use \Plenty\Plugin\Log\Loggable;

    /** @var  DataBase */
    private $db;

    public function __construct(DataBase $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $this->createNewSettings();
    }

    private function createNewSettings()
    {
        /** @var SettingsService $service */
        $service = pluginApp(SettingsService::class);
        $clients = $service->getClients();


        foreach ($clients as $plentyId)
        {
            /** @var Settings[] $storedSettings */
            $storedSettings = $this->db->query(Settings::MODEL_NAMESPACE)
                ->where('plentyId', '=', $plentyId)->get();

            $this->createDescriptionSettings( $plentyId, $storedSettings);
            $this->createInfoTypeSettings( $plentyId, $storedSettings);
        }
    }

    private function createDescriptionSettings( $plentyId, array $storedSettings)
    {
        foreach(Settings::AVAILABLE_LANGUAGES as $lang)
        {
            $storedDescription = $this->getSettingFromDataset($storedSettings, "description", $lang);

            if($storedDescription == null)
            {
                /** @var Settings $newSetting */
                $newSetting = pluginApp(Settings::class);
                $newSetting->plentyId   = $plentyId;
                $newSetting->name       = "description";
                $newSetting->value      = "";
                $newSetting->lang       = $lang;
                $newSetting->updatedAt  = date('Y-m-d H:i:s');

                $this->db->save($newSetting);
            }
        }
    }

    private function getSettingFromDataset($storedSettings, $settingName, $lang)
    {
        /** @var Settings $setting */
        foreach($storedSettings as $setting)
        {
            if($setting->name == $settingName && $setting->lang == $lang)
            {
                return $setting;
            }
        }
        return null;
    }

    private function createInfoTypeSettings( $plentyId, array $storedSettings)
    {
        /** @var Settings $storedInfoType */
        $storedInfoType = $this->getSettingFromDataset($storedSettings, "infoPageType", '');

        if(($storedInfoType != null && $storedInfoType->lang != '') || $storedInfoType == null)
        {
            $this->saveInfoPageTypeIfNotExists($plentyId);
        }
        else
        {
            $this->saveInfoPageTypeForAllLanguages($plentyId, $storedInfoType);
        }

        if($storedInfoType != null)
        {
            $this->db->delete($storedInfoType);
        }

    }

    private function saveInfoPageTypeForAllLanguages($plentyId, Settings $storedInfoType = null)
    {

        foreach(Settings::AVAILABLE_LANGUAGES as $lang)
        {

            /** @var Settings $newSetting */
            $newSetting = pluginApp(Settings::class);
            $newSetting->plentyId   = $plentyId;
            $newSetting->name       = "infoPageType";

            if($storedInfoType != null && $storedInfoType->value != 0)
            {
                $newSetting->value = $storedInfoType->value;
            }
            else
            {
                $newSetting->value = Settings::SETTINGS_DEFAULT_VALUES[$lang]['infoPageType'];
            }

            $newSetting->lang       = $lang;
            $newSetting->updatedAt  = date('Y-m-d H:i:s');

            $this->db->save($newSetting);

        }
    }

    private function isLanguageStored($storedInfoTypes, $lang)
    {
        /** @var Settings $sit */
        foreach($storedInfoTypes as $sit)
        {
            if($sit->lang == $lang)
            {
                return true;
            }
        }
        return false;
    }

    private function saveInfoPageTypeIfNotExists($plentyId)
    {
        /** @var Settings[] $storedInfoTypes */
        $storedInfoTypes = $this->db->query(Settings::MODEL_NAMESPACE)
            ->where('plentyId', '=', $plentyId)
            ->where('name',     '=', 'infoPageType')->get();

        foreach(Settings::AVAILABLE_LANGUAGES as $lang)
        {
            $langStored = $this->isLanguageStored($storedInfoTypes, $lang);

            if(!$langStored)
            {
                /** @var Settings $newSetting */
                $newSetting = pluginApp(Settings::class);
                $newSetting->plentyId   = $plentyId;
                $newSetting->name       = "infoPageType";
                $newSetting->value      = Settings::SETTINGS_DEFAULT_VALUES[$lang]['infoPageType'];
                $newSetting->lang       = $lang;
                $newSetting->updatedAt  = date('Y-m-d H:i:s');

                $this->db->save($newSetting);
            }
        }
    }

}