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
 * Tracking Service
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
class Tracking extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Persistiveutm::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }

    // Persistiveutm::$plugin->tracking->updateEmptyValues()
    public function updateEmptyValues( $type='utmCampaign' )
    {
        $records = UtmtrackingRecord::findAll([ $type => '']);
        echo count( $records );
       foreach($records as $record)
       {
         $model = new UtmtrackingModel($record);
         echo $model->id . "\n";
         if($model->utmCampaign == '')
         {
           $model->utmCampaign = null;
         }
         if($model->utmSource == '')
         {
           $model->utmSource = null;
         }
         if($model->utmMedium == '')
         {
           $model->utmMedium = null;
         }
         if($model->referrer == '')
         {
           $model->referrer = null;
         }
         $this->saveUtmtracking($model);
         unset($model);

       }

        return true;
    }


    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->getTrackingDetails()
     *
     * @return mixed
     */
    public function getTrackingDetails( $options = array() )
    {
        // Check our Plugin's settings for `someAttribute`
        /*
        if (Persistiveutm::$plugin->getSettings()->someAttribute)
        {
        }
        */
        $models = [];
        $recordsQuery = UtmtrackingRecord::find();

        if(array_key_exists("limit",$options ))
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


        $recordsQuery->orderBy("dateCreated DESC");
        $records = $recordsQuery->all();
        foreach($records as $record)
        {
            $model = new UtmtrackingModel($record);
            $models[] = $model->toArray();
        }
        return $models;
    }

    public function getUtmtrackingById($id)
    {
        $record = UtmtrackingRecord::findOne(
            [
                'id'     => $id,
            ]
        );
        $model = new UtmtrackingModel($record);

        return $model;
    }

    public function saveUtmtracking( $utmtrackingModel )
    {
        $record = UtmtrackingRecord::findOne(
            [
                'id'     => $utmtrackingModel->id,
            ]
        );
        if (!$record) {
            $record              = new UtmtrackingRecord();
        }
        $record->setAttributes($utmtrackingModel->getAttributes(), false);

        $save = $record->save();
        $model = new UtmtrackingModel($record);


        return $model;
    }



    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->onFreeformSubmissionEvent($submission);
     *
     * @return mixed
     */

    public function getUtmDetails()
    {
      $pageUrl =  null;
      if(Craft::$app->request)
      {
        $pageUrl = Craft::$app->request->pathInfo;
      }

      $utmDetails = [];

      $utmDetails['pageUrl'] = $pageUrl;

      if( isset($_COOKIE['pUtmCampaign']) )
      {
          $utmDetails['utmCampaign'] = $_COOKIE['pUtmCampaign'];
      }
      if( isset($_COOKIE['pUtmSource']) )
      {
          $utmDetails['utmSource'] = $_COOKIE['pUtmSource'];
      }
      if( isset($_COOKIE['pUtmMedium']) )
      {
          $utmDetails['utmMedium'] = $_COOKIE['pUtmMedium'];
      }
      if( isset($_COOKIE['pUtmReferrer']) )
      {
          $utmDetails['referrer'] = $_COOKIE['pUtmReferrer'];
      }
      if( isset($_COOKIE['pUtmLanding']) )
      {
          $utmDetails['landingPageUrl'] = $_COOKIE['pUtmLanding'];
      }

      return $utmDetails;

    }

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->onFreeformSubmissionEvent($submission);
     *
     * @return mixed
     */

    public function onFreeformSubmissionEvent($submission)
    {


        $utmDetails = $this->getUtmDetails();
        $utmDetails['elementId'] = $submission->id;
        $utmDetails['siteId'] = $submission->siteId;
        $utmDetails['typeId'] = $submission->formId;
        $utmDetails['elementType'] = get_class($submission);
        $utmtrackingModel = new UtmtrackingModel($utmDetails);
        $this->saveUtmtracking( $utmtrackingModel );

    }


    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->onOrderComplete($order);
     *
     * @return mixed
     */
    public function onOrderComplete($order)
    {

        $utmDetails = $this->getUtmDetails();
        $utmDetails['elementId'] = $order->id;
        $utmDetails['siteId'] = $order->siteId;
        $utmDetails['typeId'] = null;
        $utmDetails['elementType'] = get_class($order);
        $utmtrackingModel = new UtmtrackingModel($utmDetails);
        $this->saveUtmtracking( $utmtrackingModel );

    }



    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Persistiveutm::$plugin->tracking->onNewUser($element);
     *
     * @return mixed
     */
    public function onNewUser($element)
    {

        $utmDetails = $this->getUtmDetails();
        $utmDetails['elementId'] = $element->id;
        $utmDetails['siteId'] = 1;
        $utmDetails['typeId'] = 1;
        $utmDetails['elementType'] = get_class($element);
        $utmtrackingModel = new UtmtrackingModel($utmDetails);
        $this->saveUtmtracking( $utmtrackingModel );

    }



}
