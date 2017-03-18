<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\IntranetGeoIP\Columns;

use Piwik\Tracker\Visitor;
use Piwik\Tracker\Request;
use Piwik\Plugins\UserCountry\Columns\Base as UserCountryBase;
use Piwik\Plugins\IntranetGeoIP\IntranetGeoIP;

abstract class AbstractBase extends UserCountryBase
{

    public function onNewVisit(Request $request, Visitor $visitor, $action)
    {
        $userInfo = $this->getUserInfo($request, $visitor);
        
        return $this->getLocationIntranetColumn($userInfo, $this->columnName);
    }

    protected function getLocationIntranetColumn(array $userInfo, $locationKey)
    {
        $result = IntranetGeoIP::getResult($userInfo);
        
        if (isset($result[$locationKey])) {
            return $result[$locationKey];
        }
        
        return false;
    }
}
