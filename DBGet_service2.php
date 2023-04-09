<?php

if (!function_exists('DBGet_processContentForLiveData')) {
	function DBGet_processContentForLiveData($this_content) {
				//echo "<script>alert('start');</script>";

				//1.find all occurences of the below needle:
				$needle = "Live data starts here";
				$lastPos = 0;
				$positions = array();				
				while (($lastPos = strpos($this_content, $needle, $lastPos)) !== false) {
					$positions[] = $lastPos;
					//echo "<script>alert('lastPos:' + $lastPos)</script>";
					$lastPos = $lastPos + strlen($needle);
				}
				$count_liveBoxes = count($positions); //count of how many live sections
				//echo "<script>alert('There are $count_liveBoxes live boxes');</script>";
				//echo "<script>alert('positions[1]: '+$positions[1])</script>";
				
				
				//2.the for loop has to iterate between each new position in the for loop
				//but the positions have to be updated everytime since we make change to $this_content
				//DBGet should be implemented on the $do_i as we go through the for loop but focus on the $do_i turn				
				$positions = array(); //var_dump($positions);
				$lastPos = 0;
				for ($do_i=0; $do_i<$count_liveBoxes; $do_i++) {
					$positions = array(); //var_dump($positions);
					$lastPos = 0;
					
					//echo "<script>alert('we are dealing with box $do_i');</script>";
					//2.1. get all new positions
					while (($lastPos = strpos($this_content, $needle, $lastPos)) !== false) {
						$positions[] = $lastPos;
						//**//echo "<script>alert('loop - lastPos:' + $lastPos);</script>";
						$lastPos = $lastPos + strlen($needle);
					}
					//echo "<script>alert('ii -- positions[2]: '+$positions[2])</script>";

					//2.2. considering count $do_i, get the $positions[$do_i] live div,
					//extract its details, remove from start to end, add the DBGet
					//Get the live box we are delaing with and extract from its tagline
					$offset = $positions[$do_i];
					//**//echo "<script>alert('Now dealing with box at $offset (box $do_i)');</script>";
					//extract live details for this box:
					if (strpos($this_content,'Live data starts here',$offset) && strpos($this_content,'Live data ends here',$offset)) {
						$tagLineAndSomeMoreContent = substr($this_content, $offset, 500); //500 is the length. should be enough for the tag line?
						//echo "'tagLineAndSomeMoreContent:' + $tagLineAndSomeMoreContent";
						$tableNameAndRecordsAndElemIdAndMoreContent = substr($tagLineAndSomeMoreContent, strpos($tagLineAndSomeMoreContent,":")+1, 500);
						//echo "-now we have 1: " . $tableNameAndRecordsAndElemIdAndMoreContent;
						$tableName = explode(";",$tableNameAndRecordsAndElemIdAndMoreContent)[0];
						//echo "-now we have 2: " . $tableName;
						$recordsWithExtraText = explode(";",$tableNameAndRecordsAndElemIdAndMoreContent)[1];
						$records = explode(":",$recordsWithExtraText)[1];
						//echo "<br>-now 3 - records: $records";
						$elemidInside = explode(";",$tableNameAndRecordsAndElemIdAndMoreContent)[2];
						$elemid = explode(":",$elemidInside)[1];
						$elemid = trim($elemid);
						//**//echo "<script>alert('table name: $tableName & records: $records & elemid: $elemid');</script>";
					
						//remove this box as we will add in the start position the new live box with update
						//echo "<h1>NOW DELETE THIS PAST LIVE BOX</h1>";
						$this_content = delete_all_between_and_return_original("Live data starts here","Live data ends here", $this_content, $offset);
			

						
						//add the new live box at the position of what was deleted
						$newLiveBox = DBGet("yes", $tableName, $records, $elemid, "return");
						$this_content = substr_replace ($this_content, $newLiveBox, $offset, 0);					
						//$this_content = $newLiveBox;					
						
						//$this_content = substr_replace ($this_content, "test", $offset, 0);					
					
					}				
				}		
		return $this_content;
	}
}
?>