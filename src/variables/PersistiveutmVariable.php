<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates 
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\variables;

use dispositiontools\persistiveutm\Persistiveutm;

use Craft;

/**
 * persistiveutm Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.persistiveutm }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class PersistiveutmVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.persistiveutm.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.persistiveutm.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
