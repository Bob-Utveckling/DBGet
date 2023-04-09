

<?php
include ("./generalWorks.php");
include ("./settings.php");


if (!function_exists('DBGet')) {
	function DBGet($shouldReturnALiveCopy=NULL, $tableName=NULL, $whichRows=NULL, $elemId=NULL, $echoOrReturn=NULL) {
		
		$echoedResult = "";
		//**//echo "<script>alert('apply DBGet with: $shouldReturnALiveCopy, $tableName, $whichRows, $elemId, $echoOrReturn');</script>";
		$tableName = isset($_POST['tableName']) ? $_POST['tableName'] : $tableName;
		$enableSelecting = isset($_POST['enableSelecting']) ? $_POST['enableSelecting'] : 'No record selecting information sent in';
		$whichRows = isset($_POST['whichRows']) ? $_POST['whichRows'] : $whichRows;
		$shouldReturnALiveCopy = isset($_POST['returnALiveCopy']) ? $_POST['returnALiveCopy'] : $shouldReturnALiveCopy;
		
		if ($shouldReturnALiveCopy=="yes") { //part 1 of 2
			$elemId = isset ($_POST['elemId']) ? $_POST['elemId'] : $elemId;

			//$echoedResult .= "<div id='$elemId' style='border:8px solid green; resize: both; overflow: auto;' contenteditable='true'>";
			//while adding the rest of the content below, also add a live reload button:
			$echoedResult .= "<div style='font-size:10px;'>Live data starts here - table:$tableName; which records:$whichRows; id: $elemId;</div>";
			$echoedResult .= "<Button id='liveButton' onClick=reloadLiveData('$tableName','$whichRows','$elemId') style='height:100px; cursor: pointer; margin:10px; background-color:lightgreen;'>Reload This Live Data</Button>";
		}
		
		//function return table field names, and table rows. use for structure tables
		$echoedResult .= "<Style> body {/*font-family:Tahoma;*/}</Style> Showing table:";
		$echoedResult .= "<span style='font-size:20px;'>" . $tableName . "</span>";
		//*** meta_show_recommended_other_tables
		global $conn;
		global $show_titles_for_fields_in_structure_tables;
		
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			echo "error with database connection";
		}

		$sql = "SELECT * FROM `" . $tableName . "`;";
		if ($result = $conn->query($sql)) {
			$rowCount = mysqli_num_rows($result);
		$echoedResult .= "<br><span style='font-size:12px;'>There are $rowCount records in this table.</span>";
		}
		
		$echoedResult .= "<table>";
		$echoedResult .= "<thead>";
		$echoedResult .= "<tr>";
		
		
		//    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tableName . "' ORDER BY ORDINAL_POSITION;";
		$sql = "SHOW COLUMNS FROM `" . $tableName . "`;";
		$result = $conn->query($sql);
		$count = -1; $fieldNames = array(); $arrayOfLocationOfFieldsWithWinLinks = array();
		while ($rowOfFieldNames = mysqli_fetch_array($result)) {
			$count ++;
			$fieldNames[$count] = $rowOfFieldNames['Field'];
			if (strpos($rowOfFieldNames['Field'], settings_term_to_recognize_a_field_that_includes_windows_links() ) !== false) {	
				array_push($arrayOfLocationOfFieldsWithWinLinks, $count);
			$echoedResult .= "<td class='td1'>" . rtrim($rowOfFieldNames['Field'], settings_term_to_recognize_a_field_that_includes_windows_links()) .
					settings_html_text_for_fields_with_windows_links() .
					"</td>";
			}
			else {
				$echoedResult .= "<td class='td1'> " . $rowOfFieldNames['Field'] . 
				"</td>";
			}
		}
		$echoedResult .= "</tr>";
		$echoedResult .= "</thead>";
		
		if ($whichRows != '') { 
			$sql = "SELECT * FROM `" . $tableName . "` WHERE id IN ($whichRows) ;";
		} else {
			$sql = "SELECT * FROM `" . $tableName . "`;";
		}
		
		$result = $conn->query($sql);
		//echo "TBODY HERE";
		$echoedResult .= "<tbody>";
		while ($row = $result->fetch_assoc()) {
			$color = getRandomColor();
			
			$theRecordId = $row[$fieldNames[0]];
			$echoedResult .= "<tr id='row".$theRecordId."' style='background-color:" . $color . "'>";
			for ($i=0; $i<=$count; $i++) {

				if ($i == 0) {
					//this is the td field for the record id and the edit link
					$enableSelecting == "yes" ? $selectRowOrNot = "<input type='checkbox' name='whichRecord' value='$theRecordId' onClick='highlightAndSelectRow($theRecordId);'>" : $selectRowOrNot = '';
				   $echoedResult .= "<td id='structureTablesTds'>" . $theRecordId .  "<br>" . $selectRowOrNot .	"</td>";
				}
				else {
				
					if (in_array($i, $arrayOfLocationOfFieldsWithWinLinks)) {
						//this is presentation for field that is mentioned to have windows file and folder link
						$row [ $fieldNames[$i] ] = recognizeLocalWinFileLinksInTheTextAndReturn ($row[ $fieldNames[$i] ]);
						$row[ $fieldNames[$i] ] = replaceSqlQueryEndOfLineWithHtmlBr ( $row[ $fieldNames[$i] ] );	
						$echoedResult .= "<td id='structureTablesTds'>";
						
						if ($show_titles_for_fields_in_structure_tables == "yes") {
							$echoedResult .= "<span style='border:1px solid orange; background-color:  #cda7f3 ; color:  #7e0eef ; font-style:italic; font-size:12px;  '>$fieldNames[$i]</span>";							
							//this did not work: echo "<span id='structure_field_names'>".$fieldNames[$i]."</span>";
						}

						
						//if (!empty($row[ $fieldNames[$i] ])) { //This has a bug and even though field is empty, still shows the below
						if (($row[ $fieldNames[$i] ] != "")) { //This has a bug and even though field is empty, still shows the below
							$echoedResult .= "<img src='./images/folder.png' style='float:left;'><br><br>";
							$echoedResult .= $row[ $fieldNames[$i] ];
						}
						
						$echoedResult .= "</td>";
						//another option: echo "<td style=\"background-image: " . bgImageForFieldsWithWinLinks() . "\">" . 
					}
					else {
						//this is a normal record field
						//[24aug2022 19.55] (1) not a good idea because < and > are replaced in all records you  have <video>, <image>, <ul>, etc. maybe a better solution is to mark any < and > code with a ^, i.e ^< and >^ when you enter them and then say: find these and have them as &lt; and &gt; etc
						//(1) $row[ $fieldNames[$i] ] = replaceLessThanGreaterThanSignsInHtmlPresentation( $row[ $fieldNames[$i] ] );
						$row[ $fieldNames[$i] ] = replaceSqlQueryEndOfLineWithHtmlBr ( $row[ $fieldNames[$i] ] );				
						$echoedResult .= "<td id='structureTablesTds'>";
						
						if ($show_titles_for_fields_in_structure_tables == "yes") {
							$echoedResult .= "<span style='border:1px solid orange; background-color:  #cda7f3 ; color:  #7e0eef ; font-style:italic; font-size:12px;  '>$fieldNames[$i]</span>";
							//this did not work: echo "<span id='structure_field_names'>".$fieldNames[$i]."</span>";
						}
						$echoedResult .= "<p>" . $row[ $fieldNames[$i] ];
						$echoedResult .= "</td>";
					}
				}
			}
			$echoedResult .= "</tr>";
		}
			$echoedResult .= "</tbody>";
			$echoedResult .= "</table>";
		
		if ($shouldReturnALiveCopy=="yes") { //part 2 of 2
			$echoedResult .= "<div style='font-size:10px;'>Live data ends here</div>";
			//$echoedResult .= "</div>";
		}
		
		if ($echoOrReturn=="return") { return $echoedResult; }
		else 						 { echo $echoedResult; }
	}
}

isset($_POST['start']) ? DBGet() : null ;

?>