<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\services;

use dispositiontools\persistiveutm\Persistiveutm;
use dispositiontools\persistiveutm\models\Utmtracking as UtmtrackingModel;
use dispositiontools\persistiveutm\records\Utmtracking as UtmtrackingRecord;
use Craft;
use craft\base\Component;

/**
 * Reports Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class Reports extends Component
{
    // Public Methods
    // =========================================================================





    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->reports->getDataSummary($options)
     *
     * @return mixed
     */
    public function getDataSummary($options = array())
    {
        $stats = [];
        $formatedStats = [];
        $dataTypes = [];
        $elementTypes = [];

        $dataType = "utmCampaign";

        if(array_key_exists("dataType",$options ))
        {
          $dataType = $options['dataType'];
        }

        $recordsQuery = UtmtrackingRecord::find()->select(['COUNT(*) AS countCampaign',$dataType,'elementType']);

        if(array_key_exists("limit",$options ))
        {
          $recordsQuery->limit($options['limit']);
        }

        if(array_key_exists("elementType",$options ))
        {
          $recordsQuery->limit($options['limit']);
        }

        if(array_key_exists("dateStart",$options ))
        {
          $recordsQuery->andWhere([’>’, ‘dateCreated’, $options['dateStart']]);
        }
        if(array_key_exists("dateEnd",$options ))
        {
          $recordsQuery->andWhere([’<’, ‘dateCreated’, $options['dateEnd']]);
        }


        $recordsQuery->groupBy([$dataType,'elementType']);

        $records = $recordsQuery->createCommand()->queryAll();

        // Clean data
        foreach($records as $record)
        {

                $stat = $record;


                if( !in_array($stat[$dataType], $dataTypes) )
                {
                  $dataTypes[] = $stat[$dataType];
                }

                switch($stat['elementType'])
                {
                    case 'Solspace\Freeform\Elements\Submission':
                      $stat['elementType'] = "Submission";
                      break;
                    case 'Solspace\Freeform\Elements\SpamSubmission':
                      $stat['elementType'] = "Spam";
                      break;
                    case 'craft\commerce\elements\Order':
                      $stat['elementType'] = "Order";
                      break;
                    case 'craft\elements\User':
                      $stat['elementType'] = "User";
                      break;
                }

                if( !array_key_exists($stat['elementType'], $elementTypes) )
                {
                  $elementTypes[ $stat['elementType'] ] = 0;
                }

                if($stat[$dataType] == '')
                {
                  $stat[$dataType] = null;
                }


            $stats[] = $stat;
        }



        // Create the base object
        $createStats = [];
        $baseStats = [
          'total' => 0,
          'User' => 0,
          'Spam' => 0,
          'Order' => 0,
          'Submission' => 0,
        ];

        foreach($dataTypes as $dataTypesItem)
        {
          $createStats[ $dataTypesItem ] = $baseStats;
        }

        foreach($stats as $stat)
        {
          $createStats[$stat[$dataType]][$stat['elementType']] = $createStats[$stat[$dataType]][$stat['elementType']] + $stat['countCampaign'];
          $createStats[$stat[$dataType]]['total'] = $createStats[$stat[$dataType]]['total'] + $stat['countCampaign'];
        }

        $finalStats = [];
        foreach($createStats as $dataName => $createStat)
        {
            $finalStats[] = [
              $dataType => $dataName,
              'total' => $createStat['total'],
              'user'=>$createStat['User'],
              'ffSpam'=>$createStat['Spam'],
              'ffSubmission'=>$createStat['Submission'],
              'order'=>$createStat['Order'],
            ];
        }





        return $finalStats;
    }




}
