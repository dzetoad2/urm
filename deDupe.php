<?php 

require_once 'urm/functions.php';
require_once 'deDupeClasses.php';

/*
 * Finds entries that might be duplicates.
 */
function doFindCloseMatches(){
	DeDupeUtilities::findCloseMatches();
	
}
function clearFacilityComparisonTable(){
  $r = mysql_query('TRUNCATE  table  facilityComparison');
  if($r===false) die('error truncating');
}

clearFacilityComparisonTable();
doFindCloseMatches();






/*
 * 
 * select distance, row1id, row2id, row1.facilityTypeAbbrev as row1Type, row2.facilityTypeAbbrev as row2Type,   
 *    row1.name, row1.address,  row2.name, row2.address

from facilitycomparison_backup2 as facilitycomparison

left join facility as  row1
    on row1.id = facilitycomparison.row1id

left join facility as row2
   on row2.id = facilitycomparison.row2id

where distance < 18  order by distance asc
 */

/*
 * select distance, row1id, row2id, row1.facilityTypeAbbrev, row2.facilityTypeAbbrev,   row1.name, row1.address,  row2.name, row2.address

from facilitycomparison

left join facility as  row1
    on row1.id = facilitycomparison.row1id

left join facility as row2
   on row2.id = facilitycomparison.row2id

where distance < 18  order by distance asc
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * SELECT distance, facilityTypeAbbrev, row1id, row2id, 
 * facility.id, facility.name, facility.address, 
 * facility.city, facility.state FROM `facilitycomparison` 
 * join facility 
 * on facility.id = facilitycomparison.row1id  
 * or facility.id = facilitycomparison.row2id 
 * where distance < 20  order by row1id asc
 * 
 * 
 */



/*
 *  *** i used this query to get the list of close matches i sent to bill / steve.
 * 
 * select row1id, row2id, facility.id, zip1, distance, facilityTypeAbbrev, name from  facility join facilityComparison

on facility.id = facilityComparison.row1id 

or   facility.id = facilityComparison.row2id

 where distance < 12 

order by zip1
 * 
 * 
 * 
 * 
 * select row1id, row2id, zip1, distance, facilityTypeAbbrev, name from facilitycomparison join facility

on facility.id = facilityComparison.row1id


 where distance < 12 
 
 
 
 count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 22
count of rows in this group: 2
count of rows in this group: 13
count of rows in this group: 10
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 6
count of rows in this group: 1
count of rows in this group: 16
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 2
count of rows in this group: 14
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 5
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 8
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 2
count of rows in this group: 10
count of rows in this group: 11
count of rows in this group: 20
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 6
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 13
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 20
count of rows in this group: 29
count of rows in this group: 9
count of rows in this group: 6
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 14
count of rows in this group: 4
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 12
count of rows in this group: 27
count of rows in this group: 10
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 10
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 11
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 8
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 4
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 8
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 7
count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 10
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 13
count of rows in this group: 6
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 10
count of rows in this group: 13
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 16
count of rows in this group: 16
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 6
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 13
count of rows in this group: 16
count of rows in this group: 20
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 17
count of rows in this group: 15
count of rows in this group: 11
count of rows in this group: 22
count of rows in this group: 11
count of rows in this group: 11
count of rows in this group: 13
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 10
count of rows in this group: 19
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 11
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 14
count of rows in this group: 6
count of rows in this group: 14
count of rows in this group: 8
count of rows in this group: 12
count of rows in this group: 14
count of rows in this group: 6
count of rows in this group: 9
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 18
count of rows in this group: 27
count of rows in this group: 12
count of rows in this group: 24
count of rows in this group: 5
count of rows in this group: 13
count of rows in this group: 13
count of rows in this group: 9
count of rows in this group: 9
count of rows in this group: 2
count of rows in this group: 11
count of rows in this group: 1
count of rows in this group: 7
count of rows in this group: 1
count of rows in this group: 12
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 18
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 10
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 1
count of rows in this group: 16
count of rows in this group: 9
count of rows in this group: 17
count of rows in this group: 12
count of rows in this group: 8
count of rows in this group: 1
count of rows in this group: 17
count of rows in this group: 7
count of rows in this group: 15
count of rows in this group: 17
count of rows in this group: 8
count of rows in this group: 18
count of rows in this group: 6
count of rows in this group: 12
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 13
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 9
count of rows in this group: 10
count of rows in this group: 12
count of rows in this group: 16
count of rows in this group: 15
count of rows in this group: 16
count of rows in this group: 14
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 15
count of rows in this group: 10
count of rows in this group: 6
count of rows in this group: 12
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 16
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 9
count of rows in this group: 9
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 10
count of rows in this group: 8
count of rows in this group: 8
count of rows in this group: 15
count of rows in this group: 7
count of rows in this group: 3
count of rows in this group: 13
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 18
count of rows in this group: 25
count of rows in this group: 11
count of rows in this group: 9
count of rows in this group: 5
count of rows in this group: 8
count of rows in this group: 11
count of rows in this group: 7
count of rows in this group: 12
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 22
count of rows in this group: 10
count of rows in this group: 14
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 5
count of rows in this group: 13
count of rows in this group: 13
count of rows in this group: 19
count of rows in this group: 19
count of rows in this group: 17
count of rows in this group: 1
count of rows in this group: 12
count of rows in this group: 3
count of rows in this group: 9
count of rows in this group: 10
count of rows in this group: 12
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 13
count of rows in this group: 10
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 19
count of rows in this group: 20
count of rows in this group: 18
count of rows in this group: 10
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 13
count of rows in this group: 8
count of rows in this group: 11
count of rows in this group: 5
count of rows in this group: 23
count of rows in this group: 3
count of rows in this group: 7
count of rows in this group: 3
count of rows in this group: 13
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 10
count of rows in this group: 8
count of rows in this group: 8
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 11
count of rows in this group: 9
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 19
count of rows in this group: 12
count of rows in this group: 22
count of rows in this group: 2
count of rows in this group: 14
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 11
count of rows in this group: 8
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 15
count of rows in this group: 6
count of rows in this group: 11
count of rows in this group: 12
count of rows in this group: 10
count of rows in this group: 14
count of rows in this group: 15
count of rows in this group: 10
count of rows in this group: 10
count of rows in this group: 16
count of rows in this group: 2
count of rows in this group: 9
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 14
count of rows in this group: 10
count of rows in this group: 15
count of rows in this group: 11
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 9
count of rows in this group: 5
count of rows in this group: 15
count of rows in this group: 8
count of rows in this group: 6
count of rows in this group: 9
count of rows in this group: 10
count of rows in this group: 8
count of rows in this group: 2
count of rows in this group: 13
count of rows in this group: 3
count of rows in this group: 9
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 11
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 4
count of rows in this group: 21
count of rows in this group: 19
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 14
count of rows in this group: 14
count of rows in this group: 50
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 6
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 10
count of rows in this group: 14
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 13
count of rows in this group: 10
count of rows in this group: 21
count of rows in this group: 1
count of rows in this group: 10
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 3
count of rows in this group: 12
count of rows in this group: 17
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 8
count of rows in this group: 6
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 4
count of rows in this group: 6
count of rows in this group: 12
count of rows in this group: 4
count of rows in this group: 15
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 6
count of rows in this group: 14
count of rows in this group: 6
count of rows in this group: 15
count of rows in this group: 5
count of rows in this group: 12
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 15
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 17
count of rows in this group: 10
count of rows in this group: 6
count of rows in this group: 10
count of rows in this group: 6
count of rows in this group: 15
count of rows in this group: 10
count of rows in this group: 9
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 20
count of rows in this group: 13
count of rows in this group: 13
count of rows in this group: 31
count of rows in this group: 44
count of rows in this group: 18
count of rows in this group: 12
count of rows in this group: 18
count of rows in this group: 1
count of rows in this group: 11
count of rows in this group: 18
count of rows in this group: 30
count of rows in this group: 15
count of rows in this group: 14
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 4
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 10
count of rows in this group: 12
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 11
count of rows in this group: 5
count of rows in this group: 11
count of rows in this group: 24
count of rows in this group: 25
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 16
count of rows in this group: 17
count of rows in this group: 4
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 9
count of rows in this group: 3
count of rows in this group: 33
count of rows in this group: 11
count of rows in this group: 35
count of rows in this group: 1
count of rows in this group: 12
count of rows in this group: 8
count of rows in this group: 10
count of rows in this group: 14
count of rows in this group: 5
count of rows in this group: 11
count of rows in this group: 23
count of rows in this group: 20
count of rows in this group: 11
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 11
count of rows in this group: 8
count of rows in this group: 3
count of rows in this group: 6
count of rows in this group: 6
count of rows in this group: 57
count of rows in this group: 2
count of rows in this group: 17
count of rows in this group: 23
count of rows in this group: 18
count of rows in this group: 10
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 11
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 32
count of rows in this group: 4
count of rows in this group: 11
count of rows in this group: 21
count of rows in this group: 12
count of rows in this group: 19
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 16
count of rows in this group: 12
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 8
count of rows in this group: 11
count of rows in this group: 6
count of rows in this group: 25
count of rows in this group: 2
count of rows in this group: 16
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 18
count of rows in this group: 2
count of rows in this group: 6
count of rows in this group: 12
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 4
count of rows in this group: 8
count of rows in this group: 10
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 1
count of rows in this group: 8
count of rows in this group: 2
count of rows in this group: 2
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 8
count of rows in this group: 6
count of rows in this group: 6
count of rows in this group: 4
count of rows in this group: 12
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 15
count of rows in this group: 14
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 24
count of rows in this group: 19
count of rows in this group: 12
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 16
count of rows in this group: 3
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 3
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 14
count of rows in this group: 1
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 7
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 1
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 3
count of rows in this group: 1
count of rows in this group: 12
count of rows in this group: 21
count of rows in this group: 1
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 30
count of rows in this group: 10
count of rows in this group: 1
count of rows in this group: 2
count of rows in this group: 4
count of rows in this group: 7
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 3
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 11
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 21
count of rows in this group: 1
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 18
count of rows in this group: 10
count of rows in this group: 14
count of rows in this group: 3
count of rows in this group: 10
count of rows in this group: 13
count of rows in this group: 7
count of rows in this group: 16
count of rows in this group: 5
count of rows in this group: 2
count of rows in this group: 10
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 7
count of rows in this group: 7
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 10
count of rows in this group: 11
count of rows in this group: 3
count of rows in this group: 1
count of rows in this group: 26
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 1
count of rows in this group: 6
count of rows in this group: 8
count of rows in this group: 5
count of rows in this group: 5
count of rows in this group: 13
count of rows in this group: 11
count of rows in this group: 5
count of rows in this group: 14
count of rows in this group: 9
count of rows in this group: 11
count of rows in this group: 11
count of rows in this group: 6
count of rows in this group: 18
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 12
count of rows in this group: 10
count of rows in this group: 11
count of rows in this group: 4
count of rows in this group: 2
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 1
count of rows in this group: 9
count of rows in this group: 14
count of rows in this group: 12
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 7
count of rows in this group: 6
count of rows in this group: 10
count of rows in this group: 5
count of rows in this group: 1
count of rows in this group: 10
count of rows in this group: 7
count of rows in this group: 10
count of rows in this group: 1
count of rows in this group: 9
count of rows in this group: 7
count of rows in this group: 5
count of rows in this group: 4
count of rows in this group: 2
final total: 6610
starttime: Thu, 12 Jan 2012 16:31:44 -0300, endtime: Thu, 12 Jan 2012 16:48:39 -0300, num of comparisons: 0
 
 
 */