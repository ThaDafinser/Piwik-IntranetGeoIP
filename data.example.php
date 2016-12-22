<?php
/**
 * @author https://github.com/ThaDafinser
 */
/**
 * Here you can add your subnetworks and their location based informations
 * 
 * visitorInfo can be extended to all available fields inside the `log_visit` table of piwik
 */
return [
    /*
     * If the IP was not matched, apply these data to visitorInfo
     * You can also apply here all possible visitorInformation data if you want
     */
    'noMatch' => [
        'visitorInfo' => [
            // Provider requires the "Provider" Plugin to be active. (Disabled by default in Version 2.15 and above)
            //'location_country' => 'at',
        ]
    ],
    
    /*
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
    ]
    */
    //add more blocks live above
];
