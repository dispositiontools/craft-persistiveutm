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

/**
 * Utmreports Model
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
class Utmreports extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $someAttribute = 'Some Default';

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
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
