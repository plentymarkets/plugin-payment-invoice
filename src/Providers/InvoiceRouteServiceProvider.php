<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 23/2/17
 * Time: 15:48
 */

namespace Invoice\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;
use Plenty\Plugin\Routing\ApiRouter;

class InvoiceRouteServiceProvider extends RouteServiceProvider
{

    /**
     * @param Router $router
     */
    public function map(Router $router , ApiRouter $apiRouter)
    {
       $apiRouter->version(['v1'], ['middleware' => ['oauth']],
            function ($routerApi)
            {
                /** @var ApiRouter $routerApi*/
                $routerApi->get('payment/invoice/settings/{plentyId}/{lang}', ['uses' => 'Invoice\Controllers\SettingsController@loadSettings']);
                $routerApi->put('payment/invoice/settings', ['uses' => 'Invoice\Controllers\SettingsController@saveSettings']);
            });
    }

}