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
     * Returns whether this location provider is available.
     *
     * This implementation is always available.
     *
     * @return bool always true
     */
    public function isAvailable()
    {
        return false;
    }

    /**
     * Returns whether this location provider is working correctly.
     *
     * This implementation is always working correctly.
     *
     * @return bool always true
     */
    public function isWorking()
    {
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
        $enableLanguageToCountryGuess = Config::getInstance()->Tracker['enable_language_to_country_guess'];
        
        if (empty($info['lang'])) {
            $info['lang'] = Common::getBrowserLanguage();
        }
        $country = Common::getCountry($info['lang'], $enableLanguageToCountryGuess, $info['ip']);
        
        $location = array(
            parent::COUNTRY_CODE_KEY => $country
        );
        $this->completeLocationResult($location);
        
        return $location;
    }
}