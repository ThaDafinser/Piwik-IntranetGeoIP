# Piwik IntranetGeoIp Plugin

## Description
Piwik plugin to locate all locale data of a user based on the IP address/subnetwork (country, region, city, latitude, longitude, provider, ...)

***Please use it only for INTRANET tracking*** everything else just dont make sense :-)

## FAQ

__How to configure the networks?__
See the file `piwik/plugins/IntranetGeoIp/data.php` or see the readme on github https://github.com/ThaDafinser/IntranetGeoIp

__Performance optimization__
Since we are in an intranet enviroment, we disable a lot of plugins...
Disable the ***Provider (Core)*** plugin, since you dont need it!
Read here the section about Tracker performance http://piwik.org/docs/optimize-how-to/#configure-your-server-for-speed


## Configuration
After installation and activation of the plugin, open the file `piwik/plugins/IntranetGeoIp/data.php`

You can their add your location information and their subnetworks.

### Note
Inside the array key `visitorInfo` you can freely add/remove all available columns from the `log_visit` table you want.
The keys below are just a suggestion, since they are the only one which make sense currently IMO.
All available fields, see here: http://developer.piwik.org/guides/persistence-and-the-mysql-backend#visits

Inside they key `networks` add all subnetworks which apply to this location.

```php
return [
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
            'location_provider' => 'myCompany'
        ],
        'networks' => [
            //enter here all subnetworks for this location
            '10.59.0.0/19',
            '170.56.251.200/29'
        ]
    ],
    
    //add more blocks live above
];
```
