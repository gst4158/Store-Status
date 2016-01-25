//===========================================================//
// This is a simple version without use of wordpress
// SOURCE: http://stackoverflow.com/questions/14904864/determine-if-business-is-open-closed-based-on-business-hours
//===========================================================//

$storeSchedule = [
    'Sun' => ['12:00 AM' => '01:00 AM'],
    'Mon' => ['09:00 AM' => '12:00 AM'],
    'Tue' => ['09:00 AM' => '12:00 AM'],
    'Wed' => ['09:00 AM' => '12:00 AM'],
    'Thu' => ['09:00 AM' => '12:00 AM'],
    'Fri' => ['09:00 AM' => '12:00 AM'],
    'Sat' => ['12:00 AM' => '01:00 AM']
];

// FUNCTION:: checks store status based off of inputed time values
function storeStatus($storeSchedule) {

	// setup variables
	$hours		= ( $storeSchedule ? $storeSchedule : null );	
	if ( !$hours ) return false;
	
	// current or user supplied UNIX timestamp
	$timestamp = $timestamp = time();
	$currentDay = date('Y-m-d', strtotime(current_time('mysql')));
	
	// default status
	$status = 'closed';

	// get current time object
	$currentTime = (new DateTime())->setTimestamp($timestamp);

	// loop through time ranges for current day
	foreach ($storeSchedule[date('D', $timestamp)] as $startTime => $endTime) {
	
		// create time objects from start/end times
		// *note* we have to use ->getTimestamp() function b/c we're going from pm to am sometimes
		$startTime = DateTime::createFromFormat('Y-m-d h:i A', $currentDay.' '.$startTime)->getTimestamp();
		$endTime   = DateTime::createFromFormat('Y-m-d h:i A', $currentDay.' '.$endTime)->getTimestamp();
		$currentTime = $currentTime->getTimestamp();
		
		// if endtime goes into next day, add time to it for correct adjustment	
		if ( $startTime > $endTime ) { 
			$endTime += 24 *3600; 
		} 
		
		// check if current time is within a range
		if (($startTime < $currentTime) && ($currentTime < $endTime)) {
			$status = 'open';
			break;
		}
	}
		
	return $status; // return store status
	
}
