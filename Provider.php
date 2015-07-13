<?php
namespace Piwik\Plugins\IntranetGeoIP;

use Piwik\Common;
use Piwik\Config;
use Piwik\Piwik;
use Piwik\Plugins\UserCountry\LocationProvider;

final class Provder extends LocationProvider
{

    const ID = 'IntranetGeoIP';

    const TITLE = 'IntranetGeoIP';

    /**
     * Returns information about this location provider.
     * Contains an id, title & description:
     *
     * array(
     * 'id' => 'default',
     * 'title' => '...',
     * 'description' => '...'
     * );
     *
     * @return array
     */
    public function getInfo()
    {
        $desc = Piwik::translate('UserCountry_DefaultLocationProviderDesc1') . ' ' . Piwik::translate('UserCountry_DefaultLocationProviderDesc2', array(
            '<strong>',
            '<em>',
            '</em>',
            '</strong>'
        )) . '<p><em><a href="http://piwik.org/faq/how-to/#faq_163" rel="noreferrer"  target="_blank">' . Piwik::translate('UserCountry_HowToInstallGeoIPDatabases') . '</em></a></p>';
        
        return array(
            'id' => self::ID,
            'title' => self::TITLE,
            'description' => $desc,
            'order' => 20
        );
    }

    /**
     * This implementation is available, as soon as the plugin is installed!
     *
     * @return bool
     */
    public function isAvailable()
    {
        return true;
    }

    /**
     * Test if the data file is around
     *
     * @return bool
     */
    public function isWorking()
    {
        if(!file_exists(IntranetGeoIP::getDataFilePath())){
            return 'Configuration file is missing: ' . IntranetGeoIP::getDataFilePath();
        }
        
        $config = include IntranetGeoIP::getDataFilePath();
        
        if(count($config) === 1){
            return 'Only default configuration given. Please edit the configuration file: ' . IntranetGeoIP::getDataFilePath();
        }

        return true;
    }

    /**
     * Returns an array describing the types of location information this provider will
     * return.
     *
     * This provider supports the following types of location info:
     * - continent code
     * - continent name
     * - country code
     * - country name
     *
     * @return array
     */
    public function getSupportedLocationInfo()
    {
        return array(
            self::CONTINENT_CODE_KEY => true,
            self::CONTINENT_NAME_KEY => true,
            self::COUNTRY_CODE_KEY => true,
            self::COUNTRY_NAME_KEY => true
        );
    }

    /**
     * Guesses a visitor's location using a visitor's browser language.
     *
     * @param array $info
     *            Contains 'ip' & 'lang' keys.
     * @return array Contains the guessed country code mapped to LocationProvider::COUNTRY_CODE_KEY.
     */
    public function getLocation($info)
    {
        // @todo this is only for testing!
        $location = array(
            parent::COUNTRY_CODE_KEY => 'at',
            parent::ISP_KEY => 'github.com',
            parent::ORG_KEY => 'OpenSource'
        );
        $this->completeLocationResult($location);
        
        return $location;
    }
}