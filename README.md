# IntranetGeoIp

Piwik plugin to locate all locale data of a user based on the IP address/subnetwork (country, region, city, latitude, longitude, provider, ...)

## Installation
Please use the marketplace of piwik!

## Configuration
After installation and activation of the plugin, open the file `piwik/plugins/IntranetGeoIp/data.php`

You can their add your location information and their subnetworks.

### Note
Inside the array key `visitorInfo` you can (in theory...wont make sense) add all available columns from the `log_visit` table you want.
You also can remove some keys from `visitorInfo` if you don't want to apply it.

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
