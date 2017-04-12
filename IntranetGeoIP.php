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

    const DATA_EXAMPLE_FILE_PATH = __DIR__ . '/data.example.php';
    const DATA_FILE_PATH = PIWIK_INCLUDE_PATH . '/config/IntranetGeoIP.data.php';

    public function isTrackerPlugin()
    {
        return true;
    }

    /**
     *
     * @return string
     */
    private function getDataExampleFilePath()
    {
        return self::DATA_EXAMPLE_FILE_PATH;
    }

    /**
     *
     * @return string
     */
    private function getDataFilePath()
    {
        return self::DATA_FILE_PATH;
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
     * @see \Piwik\Plugin::uninstall()
     */
    public function uninstall()
    {
        if (file_exists($this->getDataFilePath())) {
            unlink($this->getDataFilePath());
        }
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
        if (!file_exists($this->getDataFilePath()) && file_exists($this->getDataExampleFilePath())) {
            copy($this->getDataExampleFilePath(), $this->getDataFilePath());
        }

        $notification = new Notification('Please edit the file ' . $this->getDataFilePath() . ' and fill in your data');
        $notification->raw = true;
        $notification->context = Notification::CONTEXT_INFO;
        Notification\Manager::notify('IntranetGeoIp_DATA_ERROR', $notification);

        return;
    }

    /**
     * @param string|null $visitorIP
     * @return array
     */
    public static function getResult($visitorIP = null)
    {
        if ($visitorIP!='0.0.0.0') {
            return self::getNewResult($visitorIP);
        }

        return [];
    }

    /**
     * @param string $visitorIP
     * @return array
     */
    private static function getNewResult($visitorIP)
    {
        if (!file_exists(IntranetGeoIP::DATA_FILE_PATH)) {
            Log::error('Plugin IntranetGeoIP does not work. File is missing: ' . IntranetGeoIP::DATA_FILE_PATH);
            return [];
        }

        $data = include IntranetGeoIP::DATA_FILE_PATH;
        if ($data === false) {
            // no data file found
            // @todo ...inform the user/ log something
            Log::error('Plugin IntranetGeoIP does not work. File is missing: ' . IntranetGeoIP::DATA_FILE_PATH);
            return [];
        }
        if (!is_array($data)) {
            Log::error('Your data file seems to be not valid. The content is: ' . print_r($data, true) . ', File used: ' . IntranetGeoIP::DATA_FILE_PATH);
            return [];
        }

        $ip = Network\IP::fromStringIP($visitorIP);

        foreach ($data as $value) {
            if (isset($value['networks']) && $ip->isInRanges($value['networks']) === true) {
                // values with the same key are not overwritten by right!
                // http://www.php.net/manual/en/language.operators.array.php

                if (isset($value['visitorInfo'])) {
                    return $value['visitorInfo'];
                }

                return [];
            }
        }

        // if nothing was matched, you can define default values if you want to
        if (isset($data['noMatch']) && isset($data['noMatch']['visitorInfo'])) {
            return $data['noMatch']['visitorInfo'];
        }

        return [];
    }
}
