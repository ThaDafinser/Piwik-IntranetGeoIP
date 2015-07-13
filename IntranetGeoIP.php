<?php
/**
 * @author https://github.com/ThaDafinser
 */
namespace Piwik\Plugins\IntranetGeoIP;

use Piwik\Plugin;
use Piwik\Notification;

// This is need, that this provider is detected in this plugin 
// @see https://github.com/piwik/piwik/blob/b95837534c6fc4c9dd63eef2c2e9d8bb343ca23e/plugins/UserCountry/LocationProvider.php#L152
require_once PIWIK_INCLUDE_PATH . '/plugins/IntranetGeoIP/Provider.php';

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
}
