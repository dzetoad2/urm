<?php 

class DeDupeUtilities {
	
	static public $debug = false;
	static public $lowestDist = 999;
	
	static function debug($o)
	{
		print '<pre>';
		print_r($o);
		print '</pre>';
	}
	
	static function findCloseMatches(){
		/*
		 * alg:  
		 *  theres 6010 items. we have to segment out the tasks into reasonable group cases. make it so we give a starting and ending index.
		 *  maybe string1 start and end,  string2 start and end.
		 *  -levenshtein threshold.
		 *  
		
		 *  
		 *  -make array of id,name+address. based on (s1 start/end,  s2 start/end)
		 *  -loop thru the array checking levenshtein.
		 */
		//self::doBatchFacilitySegment(0,428,0,5678, $arr1Type,$arr2Type); //
		
		date_default_timezone_set('America/Buenos_Aires');
		$startTime =  date('r');
		
		
		$numComparisons=0;
		 //   100 rows processed produced 4950 total rows.
		
		
		//self::doMoreFacilityComparisons($numComparisons);
		self::doAllFacilityComparisons();
		
		$endTime = date('r');
		
		echo 'starttime: '.$startTime.', endtime: '.$endTime.', num of comparisons: '.$numComparisons.'<br>';
		// is 428 of ltach,    5678 of HOSP.
	}
	static function doAllFacilityComparisons(){
	/* *  1.  Find the zip groups G.    G means a group with first 3 digits the sam,e like 750**.
		 *  2.  For each G, do a get of all of its rows (name+address, zip)
		 *  	3.  Run the levensth combination checker only on that group.
		 *  		4.  write to db the L distances of all. done.
		 */
	  $groups = self::getAllZipGroups();  // one group would be '750'. another '303'. etc. simple one-dimension array.
	  //if(count($groups)>1) die('wtf??');
	  self::debug($groups);
	  
	  $totc = 0;
	  foreach($groups as $g){ //for each group, do the combination algorithm with levenshtein and write to db.
	    $rows = self::getRowsOfGroup($g);	
	    //self::debug($rows);
	    $c = count($rows);
	    $totc+= self::getCombinationCount($c);
	    echo 'count of rows in this group: '.$c.'<br>';
	    self::doGroupCombinationComparison($rows);
	    
	  }
	  echo 'final total: '.$totc.'<br>';	  
	
	}
	static function getCombinationCount($c){
		//  combination:   n!(n-r!)/r!
	}
	static function doGroupCombinationComparison($rows){
		/* rows is an array of raw results from the db, its facility rows.
		 * we must compare each row to each other, combinations (not permutations).
		 * 
		 */
		$max = count($rows);
		//self::debug($rows);
		for($i=0;$i<$max-1;$i++){
		  for($j=$i+1;$j<$max;$j++){
		     $d = self::getLevenshteinDistance($rows[$i]['nameaddress'],$rows[$j]['nameaddress']);
		     //echo 'i: '.$i.', j: '.$j.', na1: '.$rows[$i]['nameaddress'].', na2: '.$rows[$j]['nameaddress'].', d: '.$d.'<br>';
		     //echo 'i: '.$i.', j: '.$j.', d: '.$d.'<br>';
		     $id1=$rows[$i]['id'];
		     $id2=$rows[$j]['id'];
		     $type1=$rows[$i]['facilityTypeAbbrev'];
		     $type2=$rows[$j]['facilityTypeAbbrev'];
		     $zip1 = $rows[$i]['zip'];
		     self::writeFacilityComparisonRow($id1,$id2,$type1,$type2,$zip1, $d);
		  }
		}
	
	}
	
	//get the actual rows in facility, for this group's 3digzip.
	static function getRowsOfGroup($g){
	   //the $g group is like '750', so find all with regexp ^750.
	   $r = mysql_query('select id, name, address, zip, facilityTypeAbbrev  from facility where zip  REGEXP  "^'.$g.'"     ;  ');
	   if($r===false) throw new Exception('getrowsofgroup: query fail');
	   $arr = array();
	   while($row = mysql_fetch_assoc($r)){
	     $row['nameaddress']=$row['name'].$row['address'];
	     unset($row['name']);
	     unset($row['address']);
	   	 $arr[]=$row;
	   }
	   return $arr;	   		
	}
	
	static function getAllZipGroups(){
	  // search thru db looking for all zip groups.
 	  $r = mysql_query('select distinct substring(zip, 1, 3) as subzip from facility order by subzip');
	  if($r===false) throw new Exception('getallzipgroups: query fail');
	  $groups = array();
	  while($row = mysql_fetch_assoc($r)){
	  	$subzip = $row['subzip'];
	    if(isset($groups[$subzip])){
	      throw new Exception('getallzipgroups:  subzip already in array! thus not distinct!');
	    }
	  	$groups[$subzip]=$subzip;
 	  }
	  
 	  
 	  
 	  return $groups;
//	  $ret = array();
//	  $ret[]='750';
//	  return $ret;
	}
	
	
	static function getLastRow(){
			$row_t = array();
			$row_t['row1']=0;
			$row_t['row2']=0;
			return $row_t;
	}


	static function getFacilityRowCount(){
		$r = mysql_query('select count(id) as c from facility');
		if($r===false) throw new Exception ('getfacilrowcount: query fail');
		$row = mysql_fetch_assoc($r);
		$c = $row['c'];
		
		//return 25;
		return $c;
		
		
	}
	static function doFacilityPair($row1,$row2){
		/* these are the two rows to compare.  so we get them using the LIMIT x,y in sql.
		 * 1st, get the info of each row.  row1, and row2. 
		 */
		if($row2%2000==0) echo 'row2 divisible by 2000. '.'<br/>';
	    $threshold = 12;
	
		$arr1 = self::getFacilInfo($row1);   //[id,nameaddress ] format.
		$arr2 = self::getFacilInfo($row2);
		$zip1FirstThreeDig = substr($arr1['zip'],0,3 );
		$zip2FirstThreeDig = substr($arr2['zip'],0,3);
		//if(self::$debug===true) echo 'zip1: '.$arr1['zip'].', zip2: '.$arr2['zip'].', zip1-3: '.$zip1FirstThreeDig.', zip2-3: '.$zip2FirstThreeDig.'<br>';
		$valid=true;
		if($zip1FirstThreeDig != $zip2FirstThreeDig)
		  return; //valid is false here, quit.
		$d=20;
		if($valid){
			  if(!isset($arr1['compareData']) || !isset($arr2['compareData']) || strlen($arr1['compareData'])<8 || strlen($arr2['compareData'])<8) 
			    throw new Exception('strlen < 8 of a compareData!');
		      $d = self::getLevenshteinDistance($arr1['compareData'],$arr2['compareData']);
		}
		
		if($valid && $d<$threshold ){
			// if(self::$debug===true)
		  //echo 'r1: '.$row1.', r2: '.$row2.', type: '.$arr1['typeId'].',  d: '.$d.'<br>';
		  self::writeFacilityComparisonRow($row1,$row2,$arr1['typeId'],$arr2['typeId'],$d);
		}
	}
	static function getFacilInfo($rownum){
		//    select ....    limit  2,1   -> gives the 3rd row by itself.  1 means only 1 of the rows.
		$r1 = mysql_query('select id,name,address, city,state, zip,   facilityTypeAbbrev  from facility  order by facilityTypeAbbrev  LIMIT '.$rownum.',  1  ;');
		if($r1===false) throw new Exception('getfacilidnameaddressarray:  r1 query fail');
		$idArr = array();
		$row = mysql_fetch_assoc($r1);
		$id = $row['id'];
		$compareData =   trim(  $row['name'].$row['address'].$row['city'].$row['state'] .$row['zip']);
		$type= $row['facilityTypeAbbrev'];
		$zip = $row['zip'];
		
		$idArr['id']=$id;
		$idArr['compareData']=trim($compareData);    
		$idArr['zip']=$zip;
		
		if($type==='HOSP'){
			$idArr['typeId']=1;
		}elseif($type==='PR'){
			$idArr['typeId']=3;
		}elseif($type==='LTACH'){
			$idArr['typeId']=2;
		}else{
			throw new Exception('error: type invalid, not one of the 3');
		}
			
		
		return $idArr;
	}
	static function getFacilIdNameAddressArray($limitStart,$limitEnd,$arrType){
		/*  'limit 0,2' gives two rows, not 3.     'limit 1,2' gives 
		 * 
		 */
		$r1 = mysql_query('select id,name,address from facility where facilityTypeAbbrev = "'.$arrType.'"  LIMIT '.$limitStart.', '.$limitEnd.' ;');
		if($r1===false) throw new Exception('getfacilidnameaddressarray:  r1 query fail');
		$idArr = array();
		while($row = mysql_fetch_assoc($r1)){
			$i = $row['id'];
			$idArr[$i]= $row['name'].$row['address'];
			//echo $row['id'].'<br>';
		}
		return $idArr;
	}
	
	static function writeFacilityComparisonRow($id1,$id2,$type1Str,$type2Str,$zip1, $distance){
	
	$type1Id=self::getTypeId($type1Str);
	$type2Id=self::getTypeId($type2Str);
	
		$sqlStr = 'insert into facilityComparison (row1id, row2id, row1TypeId, row2TypeId, zip1, distance)  
		                  values('.$id1.', '.$id2.','.$type1Id.', '.$type2Id.', '.$zip1.', '.$distance.'  ) ';
		$r = mysql_query($sqlStr);
		if($r===false){
			throw new Exception('writefacilcomparisonrow: query r  failed, mysqlerr: '.mysql_error().', sqlstr: '.$sqlStr);
		}
	}
	static function getTypeId($s){
	 if($s=='LTACH'){
	   return 2;
	 }elseif($s=='PR'){
	   return 3;
	 }elseif($s==='HOSP'){
	   return 1;
	 }else{
	   throw new Exception('gettypeid:  type not valid string');
	 }
	
	}
	static function getLevenshteinDistance($s1,$s2){
		if(trim($s1) == ''  || trim($s2) == '') throw new Exception('s1 or s2 in getlevenshtein was blank! id: '.$id);
		$s1 = strtolower($s1);
		$s2 = strtolower($s2);
		//echo 's1: '.$s1.', s2: '.$s2.'<br>';
		return levenshtein($s1,$s2);
	}
	
	static function getLevenshteinDistance_db($s1,$s2){
		/*
		 *  LTACH list is more up to date than AHAhosp list(HOSP).
		*  SELECT * FROM users ORDER BY levenshtein_distance(users.name, 'john')
		*
		* 	$o = 'uninit';
		*	$s1 = 'abcdef';
		* 	$s2 = 'abc def ghi';
		*   $rs = mysql_query("select count(id) from facility");
		*/
		$rs0 = mysql_query("CALL levenshtein('".$s1."','".$s2."',@outInt); ");
		// 	$rs0 = mysql_query("CALL levenshtein_ratio('abc def','abcdef',@outInt);");
		$rs = mysql_query("select @outInt;");
		if($rs0===false){
			throw new Exception('rs0 was false: error: '.mysql_error());
		}
		if($rs===false){
			throw new Exception('rs was false: error: '.mysql_error());
		}
		// 	if($rs === true){
		// 		echo 'rs was true';
		// 	}
		$row = mysql_fetch_assoc($rs);
		//		debug($row);
		//	echo 'outInt: '.$row['@outInt'].'<br>';
		return $row['@outInt'];
		//	echo '------------<br>';
	}
	static function findCloseMatchesTest(){
	   /*
		* for all the row (strings) in facility, find the levenshtein distance between it and every OTHER
		*  row.  Let the row string be its name and address concatenated together.
		* 1. get all the ids in facility.
		* 2. loop
		* 
		*
		*/
	}
}


//---------------------------------------
/*
 * notes:
 * 
 * 33 elements in group 750.    528 results total. is that right?    33 choose 2, so 33! /  (2 * 31!) =   (33*32) / (2) = 528!!! yessss
 * 
 * 
 * 
 * 
 * 
 */











	/* 20:35 -> 23:51    = 3:16  for 2000 rows.
	 * 
	 * was old, maybe useful now.
	 */
// 	static function  doBatchFacilitySegment($arr1Start,$arr1End,$arr2Start,$arr2End,
// 			                			  	$arr1Type,$arr2Type){ 
// 		/*
// 		 */
// 		for($x=$arr1Start;$x<=$arr1End;$x++){
// 			for($y=$arr2Start;$y<=$arr2End;$y++){
// 				self::doFacilitySegment($x,$x,$y,$y, $arr1Type,$arr2Type); //
// 			}
// 		}
// 	} 
 	
 	
 	
//	static function getLastRow_fromdb(){
//	
//		$r = mysql_query(' SELECT * FROM  facilityComparison    order by  row1  desc');
//		if($r===false) throw new Exception('getminrow: query fail');
//		$row = mysql_fetch_assoc($r); //get the last row , with the highest count.
//		$numrows = mysql_num_rows($r);
//		if($numrows===0){
//			$row_t = array();
//			$row_t['row1']=0;
//			$row_t['row2']=0;
//			return $row_t;
//		}
//		return $row;
//	}
//	static function doMoreFacilityComparisons($numComparisons){
//		/* alg:    go down the table comparison everything to everything else.  
//		 */
//		$row = self::getLastRow();    //gets the last entry in db of facilityComparison. (ordered by ascending row1)
//		$row1 = $row['row1'];   
//		$row2 = $row['row2'];
//		
//		$min = 0;
//		$max = self::getFacilityRowCount();//this func must give the count, NOT count-1.
//		echo 'before the if/elseif... conditional: check : row1: '.$row1.', row2: '.$row2.', max: '.$max.'<br>';
//		if($row1 < $max-2 && $row2 < $max-1){// this is most common case.
//			$row2++;
//		}elseif($row1 < $max-2 && $row2 == $max-1){
//			$row1++; $row2=$row1+1;
//		}elseif($row1 === $max-2 && $row2 == $max-1){
//			//quit, do nothing.
//			echo 'done!<br>';
//		}else{
//			throw new Exception('row1/row2 bounds dont make sense. error. row1: '.$row1.', row2: '.$row2.', max: '.$max);
//		}
//		$count=0;  //iterate from 0 up to numcomparisons. // 6610;   // total count.  last row num is 6609.
//		
//		
//					//for($x=$row1;$x<$max-1;$x++){  //x range from 0 to 6608(max-2)
//						//for($y=$row1+1;$y<$max;$y++){      // range from x+1 to 6609(max-1
//		$x=$row1;
//		$y=$row2;
//		while($x<$max-1){
//			while($y<$max){						
//				self::doFacilityPair($x,$y);
//				//echo 'x: '.$x.', y: '.$y.' ---';
//				//echo '<br>';
//				$count++;
//				if($count==$numComparisons) {
//					echo 'x: '.$x.', y: '.$y.'<br>';
//					return;
//				}
//				$y++;
//			}
//			$x++;
//			$y=$x+1;
//		}
//		
//	}