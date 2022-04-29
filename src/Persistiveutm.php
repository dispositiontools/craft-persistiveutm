<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm;

use dispositiontools\persistiveutm\services\Tracking as TrackingService;
use dispositiontools\persistiveutm\services\Reports as ReportsService;
use dispositiontools\persistiveutm\variables\PersistiveutmVariable;
use dispositiontools\persistiveutm\models\Settings;
use dispositiontools\persistiveutm\widgets\Utmcampaigns as UtmcampaignsWidget;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use craft\web\View;

use craft\commerce\elements\Order;
use Solspace\Freeform\Services\SubmissionsService;
use Solspace\Freeform\Events\Submissions\SubmitEvent;
use craft\events\ElementEvent;
use craft\services\Elements;
use craft\elements\User;
/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 *
 * @property  TrackingService $tracking
 * @property  ReportsService $reports
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Persistiveutm extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Persistiveutm::$plugin
     *
     * @var Persistiveutm
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '0.0.1';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Persistiveutm::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Add in our console commands
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'dispositiontools\persistiveutm\console\controllers';
        }

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['persistiveutm/dashboard'] = 'persistiveutm/reports/dashboard';
                $event->rules['cpActionTrigger2'] = 'persistiveutm/reports/do-something';
            }
        );


        $settings = $this->getSettings();



        // Template Hook
        Craft::$app->view->hook(
            'persistiveutm',
            [$this, 'onRegisterPersistiveUtmHook']
        );

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        if ( $settings->activateCommerce && class_exists('craft\commerce\elements\Order')) {


              Event::on(
                  Order::class,
                  Order::EVENT_AFTER_COMPLETE_ORDER,
                  function (Event $event) {
                      if (
                          !($event->sender->duplicateOf && $event->sender->getIsCanonical() && !$event->sender->updatingFromDerivative) &&
                          !$event->sender->resaving
                      ) {
                          // ...
                          if($event->sender)
                          {
                              $order = $event->sender;
                              Persistiveutm::$plugin->tracking->onOrderComplete($order);
                          }

                      }
                  }
              );

          }



        if ($settings->activateFreeform && class_exists('Solspace\Freeform\Services\SubmissionsService')) {
            Event::on(
              SubmissionsService::class,
              SubmissionsService::EVENT_AFTER_SUBMIT,
              function (SubmitEvent $event) {
                $form = $event->getForm();
                $submission = $event->getSubmission();

                // Perform some actions here
                Persistiveutm::$plugin->tracking->onFreeformSubmissionEvent($submission);

              }
            );
        }


          if ($settings->activateUser) {
            // Elements
            Event::on(
                Elements::class,
                Elements::EVENT_AFTER_SAVE_ELEMENT,
                function(ElementEvent $event) {

                    if ($event->element instanceof User) {
                         if ( $event->isNew )
                         {
                             if ($this->getSettings()->activateUser )
                             {
                               Persistiveutm::$plugin->tracking->onNewUser($event->element);
                             }
                         }
                    }

                }
            );

          }



/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'persistiveutm',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }



    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'persistiveutm/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }


    /**
       * Process 'persistiveutm' hook
       *
       * @param array $context
       *
       * @return string
       *
       * @throws LoaderError
       * @throws RuntimeError
       * @throws SyntaxError
       * @throws Exception
       */
      public function onRegisterPersistiveUtmHook(&$context): string
      {
          $craft = Craft::$app;
          $settings = $this->getSettings();
          $output = "";
          //return $settings->enablePersistiveUtm;
          if($settings->enablePersistiveUtm )
          {
            if($settings->useJs)
            {
              $oldTemplateMode = $craft->getView()->getTemplateMode();
              $craft->getView()->setTemplateMode(View::TEMPLATE_MODE_CP);
              $output .= $craft->getView()->renderTemplate('persistiveutm/_output/javascript');
              $craft->getView()->setTemplateMode($oldTemplateMode);
            }

            if(!$settings->useJs)
            {
                // set things
            }

          }

          return $output;
      }








}
