<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 23/2/17
 * Time: 12:22
 */

namespace Invoice\Controllers;

use Invoice\Services\SettingsService;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

class SettingsController extends Controller
{

    public function loadSettings(Response $response, SettingsService $service, $plentyId, $lang)
    {
        return $response->json($service->getSettingsForPlentyId($plentyId, $lang));
    }

    public function saveSettings(Request $request, Response $response, SettingsService $service)
    {
        return $response->json($service->saveSettings($request->except(['plentyMarkets'])));
    }

}