<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\console\controllers;

use dispositiontools\persistiveutm\Persistiveutm;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Tools Command
 *
 * The first line of this class docblock is displayed as the description
 * of the Console Command in ./craft help
 *
 * Craft can be invoked via commandline console by using the `./craft` command
 * from the project root.
 *
 * Console Commands are just controllers that are invoked to handle console
 * actions. The segment routing is plugin-name/controller-name/action-name
 *
 * The actionIndex() method is what is executed if no sub-commands are supplied, e.g.:
 *
 * ./craft persistiveutm/tools
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft persistiveutm/tools/do-something
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class ToolsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle persistiveutm/tools console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'something';

        echo "Welcome to the console ToolsController actionIndex() method\n";

        return $result;
    }

    /**
     * Handle persistiveutm/tools/clean-empty-values console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @return mixed
     */
    public function actionCleanEmptyValues()
    {
        $result = 'something';

        Persistiveutm::$plugin->tracking->updateEmptyValues('referrer');

        return $result;
    }


    /**
     * Handle persistiveutm/tools/parse-referrers console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @return mixed
     */
    public function actionParseReferrers()
    {
        $result = 'something';

        Persistiveutm::$plugin->tracking->parseReferrers();

        return $result;
    }
}
