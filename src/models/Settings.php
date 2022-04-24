<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\models;

use dispositiontools\persistiveutm\Persistiveutm;

use Craft;
use craft\base\Model;

use craft\commerce\elements\Order;

/**
 * Persistiveutm Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */

    public $enablePersistiveUtm = false;
    public $useJs = true;
    public $inlineJs = true;
    public $activateUser = false;
    public $activateFreeform = false;
    public $activateCommerce = false;
    public $cookieLength = 60*60*24*7;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
     /**
      * @inheritdoc
      */
     public function defineRules(): array
     {
         $rules = parent::defineRules();

        // $rules[] = [['cookieLength'], 'required'];

         return $rules;
     }
}
