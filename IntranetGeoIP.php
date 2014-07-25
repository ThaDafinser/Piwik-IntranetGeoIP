<?php
/**
 * @author https://github.com/ThaDafinser
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\IntranetGeoIP;

use Piwik\Plugin;
use Piwik\IP;
use Piwik\Log;
use Piwik\Notification;

class IntranetGeoIP extends Plugin
{

    /**
     *
     * @return string
     */
    private function getDataExampleFilePath()
    {
        return __DIR__ . '/data.example.php';
    }

    /**
     *
     * @return string
     */
    private function getDataFilePath()
    {
        return PIWIK_INCLUDE_PATH . '/config/IntranetGeoIP.data.php';
    }

    /**
     *
     * @see Piwik\Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'Tracker.newVisitorInformation' => 'logIntranetSubNetworkInfo'
        );
    }

    /**
     *
     * @see \Piwik\Plugin::activate()
     */
    public function activate()
    {
        if (! file_exists($this->getDataFilePath()) && file_exists($this->getDataExampleFilePath())) {
            copy($this->getDataExampleFilePath(), $this->getDataFilePath());
        }
        
        $notification = new Notification('Please edit the file ' . $this->getDataFilePath() . ' and fill in your data');
        $notification->raw = true;
        $notification->context = Notification::CONTEXT_INFO;
        Notification\Manager::notify('IntranetGeoIp_DATA_ERROR', $notification);
        
        return;
    }

    /**
     * Called by event `Tracker.newVisitorInformation`
     *
     * @see getListHooksRegistered()
     */
    public function logIntranetSubNetworkInfo(&$visitorInfo)
    {
        if (! file_exists($this->getDataFilePath())) {
            Log::error('Plugin IntranetGeoIP does not work. File is missing: ' . $this->getDataFilePath());
            return;
        }
        
        $data = include $this->getDataFilePath();
        if ($data === false) {
            // no data file found
            // @todo ...inform the user/ log something
            Log::error('Plugin IntranetGeoIP does not work. File is missing: ' . $this->getDataFilePath());
            return;
        }
        
        foreach ($data as $value) {
            if (isset($value['networks']) && IP::isIpInRange($visitorInfo['location_ip'], $value['networks'])) {
                // values with the same key are not overwritten by right!
                // http://www.php.net/manual/en/language.operators.array.php
                if (isset($value['visitorInfo'])) {
                    $visitorInfo = $value['visitorInfo'] + $visitorInfo;
                }
                return;
            }
        }
        
        // if nothing was matched, you can define default values if you want to
        if (isset($data['noMatch']) && isset($data['noMatch']['visitorInfo'])) {
            $visitorInfo = $data['noMatch']['visitorInfo'] + $visitorInfo;
            return;
        }
    }
}
