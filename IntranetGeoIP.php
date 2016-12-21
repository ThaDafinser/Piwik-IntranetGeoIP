<?php
/**
 * @author https://github.com/ThaDafinser
 */
namespace Piwik\Plugins\IntranetGeoIP;

use Piwik\Plugin;
use Piwik\Network;
use Piwik\Log;
use Piwik\Notification;
use Piwik\Plugins\PrivacyManager\Config as PrivacyManagerConfig;
use Piwik\Tracker\Request as TrackerRequest;

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
     * @see Piwik\Plugin::registerEvents
     */
    public function registerEvents()
    {
        return array(
            'Tracker.newVisitorInformation' => 'logIntranetSubNetworkInfo'
        );
    }

    /**
     *
     * @see \Piwik\Plugin::install()
     */
    public function install()
    {
        return $this->copyDataFile();
    }

    /**
     *
     * @see \Piwik\Plugin::activate()
     */
    public function activate()
    {
        return $this->copyDataFile();
    }

    private function copyDataFile()
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
     *
     * @see \Piwik\Plugin::uninstall()
     */
    public function uninstall()
    {
        if (file_exists($this->getDataFilePath())) {
            unlink($this->getDataFilePath());
        }
    }

    /**
     * Called by event `Tracker.newVisitorInformation`
     *
     * @see registerEvents()
     */
    public function logIntranetSubNetworkInfo(&$visitorInfo, TrackerRequest $request)
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
        if (!is_array($data)) {
            Log::error('Your data file seems to be not valid. The content is: ' . print_r($data, true) . ', File used: ' . $this->getDataFilePath());
            return;
        }
        
        $privacyConfig = new PrivacyManagerConfig();
        
        $ipBinary = $request->getIp();
        if ($privacyConfig->useAnonymizedIpForVisitEnrichment === true) {
            $ipBinary = $visitorInfo['location_ip'];
        }
        
        $ip = Network\IP::fromBinaryIP($ipBinary);
        
        foreach ($data as $value) {
            if (isset($value['networks']) && $ip->isInRanges($value['networks']) === true) {
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
