<?php
/**
 * @author https://github.com/ThaDafinser
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\IntranetSubNetwork;

use Piwik\Plugin;
use Piwik\IP;

class IntranetGeoIp extends Plugin
{

    const DATA_FILE = 'data.php';

    const DATA_FILE_EXAMPLE = 'data.example.php';

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

    public function install()
    {
        $this->createDefaultDataFile();
        
        return;
    }

    public function activate()
    {
        $this->createDefaultDataFile();
        
        return;
    }

    private function createDefaultDataFile()
    {
        if (! file_exists(__DIR__ . '/' . self::DATA_FILE) && file_exists(__DIR__ . '/' . self::DATA_FILE_EXAMPLE)) {
            copy(__DIR__ . '/' . self::DATA_FILE_EXAMPLE, __DIR__ . '/' . self::DATA_FILE);
        }
    }

    /**
     * Called by event `Tracker.newVisitorInformation`
     *
     * @see getListHooksRegistered()
     *
     */
    public function logIntranetSubNetworkInfo(&$visitorInfo)
    {
        $data = include 'data.php';
        if ($data === false) {
            // no data file found
            // @todo ...inform the user/ log something
            return;
        }
        
        foreach ($data as $value) {
            if (IP::isIpInRange($visitorInfo['location_ip'], $value['networks'])) {
                // values with the same key are not overwritten by right!
                // http://www.php.net/manual/en/language.operators.array.php
                $visitorInfo = $value['visitorInfo'] + $visitorInfo;
                return;
            }
        }
        
        $visitorInfo['location_provider'] = 'unknown';
    }
}
