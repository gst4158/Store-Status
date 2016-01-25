//===========================================================//
// Wordpress version using ACF fields to get hours
// SOURCE: http://stackoverflow.com/questions/14904864/determine-if-business-is-open-closed-based-on-business-hours
//===========================================================//

// FUNCTION:: checks store status based off of inputed time values
function storeStatus() {

	// setup variables
	$hours		= ( get_field('field_hours_repeater', 'option') ? get_field('field_hours_repeater', 'option') : null );	
	if ( !$hours ) return false;
	
	$dayCode	= strtolower(date('l', strtotime(current_time('mysql'))));
	$dayKey		= ( $dayCode == 'friday' || $dayCode == 'saturday' || $dayCode == 'sunday' ? $dayCode : 'weekday' );
	
	// get start/close times
	foreach( $hours as $item ) {
		if ( $item['hours_repeat_day'] == $dayKey ) {
			$startTime = $item['hours_repeat_start'];
			$closeTime = $item['hours_repeat_close'];
		}
	};

	// set today's schedule
	$storeSchedule = [
		date('D', strtotime(current_time('mysql'))) => [$startTime => $closeTime]
	];
	
	// current or user supplied UNIX timestamp
	$timestamp = strtotime(current_time('mysql'));
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
