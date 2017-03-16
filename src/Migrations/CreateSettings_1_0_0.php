<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 23/2/17
 * Time: 15:54
 */

namespace Invoice\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;

use Plenty\Modules\System\Models\Webstore;
use Invoice\Models\Settings;
use Invoice\Services\SettingsService;


class CreateSettings_1_0_0
{

    use \Plenty\Plugin\Log\Loggable;

    /** @var  Settings[] */
    private $existingSettings;

    public function run(Migrate $migrate, DataBase $db)
    {
//        try
//        {
            $migrate->createTable(Settings::class);
        /*}
        catch(\Exception $e)
        {
            echo "\n HALLOOOO \n\n";
            $this->existingSettings = $db->query(Settings::MODEL_NAMESPACE)->get();
            $migrate->deleteTable(Settings::class);
            $migrate->createTable(Settings::class);
        }*/

        $this->setInitialSettings();

//        $this->updateDbWithOldSettings($db);
    }

    private function setInitialSettings()
    {
        /** @var SettingsService $service */
        $service = pluginApp(SettingsService::class);
        $clients = $service->getClients();

        foreach(Settings::AVAILABLE_LANGUAGES as $lang)
        {
            foreach ($clients as $plentyId)
            {
                $service->createInitialSettingsForPlentyId($plentyId, $lang);
            }
        }
    }

//    private function updateDbWithOldSettings(DataBase $db)
//    {
//        /** @var Settings[] $newSettings */
//        $newSettings = $db->query(Settings::MODEL_NAMESPACE)->get();
//
//        /** @var Settings $setting */
//        foreach($newSettings as $setting)
//        {
//            /** @var Settings $oldSetting */
//            foreach($this->existingSettings as $oldSetting)
//            {
//                if($oldSetting->name == $setting->name && $oldSetting->plentyId == $setting->plentyId && $oldSetting->lang == $setting)
//                {
//                    $setting->value = $oldSetting->value;
//                    $db->save($setting);
//                }
//            }
//        }
//
//    }

}