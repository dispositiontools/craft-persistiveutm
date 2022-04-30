<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\controllers;

use dispositiontools\persistiveutm\Persistiveutm;

use Craft;
use craft\web\Controller;

/**
 * Reports Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class ReportsController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/persistiveutm/reports
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the ReportsController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/persistiveutm/reports/dashboard
     *
     * @return mixed
     */
    public function actionDashboard()
    {
        $result = 'Welcome to the ReportsController actionDoSomething() method';

        $trackingData = Persistiveutm::$plugin->tracking->getTrackingDetails(["limit"=>10]);
        $campaignStats =  Persistiveutm::$plugin->reports->getDataSummary(['dataType'=>'utmCampaign']);
        $sourceStats =  Persistiveutm::$plugin->reports->getDataSummary(['dataType'=>'utmSource']);
        $mediumStats =  Persistiveutm::$plugin->reports->getDataSummary(['dataType'=>'utmMedium']);
        $referrerStats =  Persistiveutm::$plugin->reports->getDataSummary(['dataType'=>'referrerDomain']);

        return $this->renderTemplate(
            'persistiveutm/_cp/dashboard',
            [
                'title'            => 'Persistive UTM',
                'trackingData'    =>   $trackingData,
                'campaignStats' =>  $campaignStats,
                'sourceStats' =>  $sourceStats,
                'mediumStats' =>  $mediumStats,
                'referrerStats' =>  $referrerStats,
                'settings' =>  Persistiveutm::$plugin->getSettings()
            ]
        );
        //print_r($trackingData);

        return $result;
    }
}
