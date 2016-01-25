# Store Status Checker

[![License](http://img.shields.io/badge/License-MIT-blue.svg)](http://opensource.org/licenses/MIT)

### Determines if store is currently closed or open with ability to check pm -> am conversions

By feeding the function a time based array we are able to compare values with the current time and determine if our store is open.

Example array: 
$storeSchedule = [
    'Sun' => ['12:00 AM' => '01:00 AM'],
    'Mon' => ['09:00 AM' => '12:00 AM'],
    'Tue' => ['09:00 AM' => '12:00 AM'],
    'Wed' => ['09:00 AM' => '12:00 AM'],
    'Thu' => ['09:00 AM' => '12:00 AM'],
    'Fri' => ['09:00 AM' => '12:00 AM'],
    'Sat' => ['12:00 AM' => '01:00 AM']
];

** Based on code found here: http://stackoverflow.com/questions/14904864/determine-if-business-is-open-closed-based-on-business-hours **
