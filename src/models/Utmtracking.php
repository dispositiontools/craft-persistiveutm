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
 * Utmtracking Model
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
class Utmtracking extends Model
{
    // Public Properties
    // =========================================================================


    /**
     * Some model attribute
     *
     * @var int
     */
    public $id;


    /**
     * Some model attribute
     *
     * @var int
     */
    public $dateCreated;


    /**
     * Some model attribute
     *
     * @var int
     */
    public $dateUpdated;

    /**
     * Some model attribute
     *
     * @var int
     */
    public $uid;


    /**
     * Some model attribute
     *
     * @var int
     */
    public $siteId = 1;



    /**
     * Some model attribute
     *
     * @var int
     */
    public $elementId = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $utmCampaign = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $utmSource = null;

    /**
     * Some model attribute
     *
     * @var string
     */
    public $utmMedium = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $utmContent = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $utmTerm = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $parsedStatus = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $channel = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $referrerDomain = null;

    /**
     * Some model attribute
     *
     * @var string
     */
    public $referrer = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $pageUrl = null;


    /**
     * Some model attribute
     *
     * @var string
     */
    public $landingPageUrl = null;

    /**
     * Some model attribute
     *
     * @var string
     */
    public $elementType = null;


    /**
     * Some model attribute
     *
     * @var int
     */
    public $typeId = null;



    /**
     * Some model attribute
     *
     * @var int
     */
    public $userId = null;

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
