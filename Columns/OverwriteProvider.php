<?php
namespace Piwik\Plugins\IntranetGeoIP\Columns;

use Piwik\Plugin\Manager;
use Piwik\Tracker\Visitor;
use Piwik\Tracker\Action;
use Piwik\Tracker\Request;

class OverwriteProvider extends AbstractBase
{

    protected $columnName = 'location_provider';

    public function onNewVisit(Request $request, Visitor $visitor, $action)
    {
        if (! Manager::getInstance()->isPluginInstalled('Provider')) {
            return false;
        }
        
        return parent::onNewVisit($request, $visitor, $action);
    }
}
