# Piwik IntranetGeoIp Plugin

## Description

Piwik plugin to locate all locale data of a user based on the IP address/subnetwork (country, region, city, latitude, longitude, provider, ...)

***Please use it only for INTRANET tracking*** everything else just dont make sense :-)

## FAQ

__What does this plugin do?__

It adds visitor information based on the matched IP address from your configuration. Not more and not less.
The database schema and UI stays untouched, so all Piwik statistics can be used like you would use a internet GeoIP database.


__How to configure/install this plugin / the networks?__

Upload the plugin in the .zip format. 
(You may need to add "enable_plugin_upload = 1" on the your config/config.ini.php file under [General])

After installation and activation of the plugin, open the file `piwik/config/IntranetGeoIP.data.php`

You can their add your location information and their subnetworks.

See the file `piwik/config/IntranetGeoIP.data.php` or see the readme on github https://github.com/ThaDafinser/IntranetGeoIp


__What statistics are available?__

If you create a full configuration data file, you'll see
* Visitor -> Realtime visitor map
* Visitor -> Location and provider
* and many more...(in generall all statistics are available like using a internet GeoIP database)


__Why there stands provider "unknown" in my visitor log?__

If your installation is stock, all visitors will get this "flag" to show you, what IPs are not matched.
You can adjust or remove this, by changing the "noMatch" block in your `IntranetGeoIP.data.php` file.
If you remove the complete block, none matched visitors will be skipped by this plugin.
But you can also fill all possible visitorInfos like you are used for matched IP addresses.


__Can i use this plugin with a internet GeoIP database side by side?__

Yes you can.
Just remove or comment out the `noMatch` block in your configuration file.

__Note about the configuration?__

Inside the array key `visitorInfo` you can freely add/remove all available columns from the `log_visit` table you want.
The keys below are just a suggestion, since they are the only one which make sense currently IMO.
All available fields, see here: http://developer.piwik.org/guides/persistence-and-the-mysql-backend#visits

Inside they key `networks` add all subnetworks which apply to this location.

```php
return [
    /*
     * If the IP was not matched, apply these data to visitorInfo
     * You can also apply here all possible visitorInformation data if you want
     */
    'noMatch' => [
        'visitorInfo' => [
            // Provider requires the "Provider" Plugin to be active. (Disabled by default in Version 2.15 and above)
            //'location_provider' => 'unknown'
        ]
    ],
    
    [
        'visitorInfo' => [
            //ISO-3166 alpha-2 code http://en.wikipedia.org/wiki/ISO_3166-1
            'location_country' => 'at',
            
            //the region code (i take them from piwik/libs/MaxMindGeoIp/geoipregionvars.php
            'location_region' => '08',
            
            //should be freetext
            'location_city' => 'Muntlix',
            
            //get this from a picker, e.g. http://www.tytai.com/gmap/
            'location_latitude' => '47.282024',
            'location_longitude' => '9.662304',
            
            //enter your company name or do it based on your domain hierarchy
            // Provider requires the "Provider" Plugin to be active. (Disabled by default in Version 2.15 and above)
            //'location_provider' => 'myCompany'
        ],
        'networks' => [
            //enter here all subnetworks for this location
            //use a subnetwork calculator, e.g. http://jodies.de/ipcalc
            '10.59.0.0/19',
            '170.56.251.200/29'
        ]
    ],
    
    //add more blocks live above
];
```
