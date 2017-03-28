<?php
/**
 * @author https://github.com/ThaDafinser
 */
namespace Piwik\Plugins\IntranetGeoIP\Tracker;

use Piwik\Network\IPUtils;
use Piwik\Plugins\IntranetGeoIP\IntranetGeoIP;
use Piwik\Tracker\Request;
use Piwik\Tracker\RequestProcessor;
use Piwik\Tracker\Visit\VisitProperties;

/**
 * Class IntranetGeoIPRequestProcessor
 * @package Piwik\Plugins\IntranetGeoIP\Tracker
 */
class IntranetGeoIPRequestProcessor extends RequestProcessor
{
    /**
     * @param VisitProperties $visitProperties
     * @param Request $request
     */
    public function onNewVisit(VisitProperties $visitProperties, Request $request)
    {
        $vProperties = $visitProperties->getProperties();
        $visitorInfo = IntranetGeoIP::getResult(IPUtils::binaryToStringIP($vProperties['location_ip']));

        foreach ($visitorInfo as $key => $val) {
            $visitProperties->setProperty($key, $val);
        }
    }
}
