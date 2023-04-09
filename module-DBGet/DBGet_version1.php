<script>
    DBGet_php_script1 = "http://localhost/barekat/DBGet_loadListOfAllowedTables.php";
	DBGet_php_script2 = "http://localhost/barekat/DBGet_show_All_or_Selected_RowsOfATable_EnableSelecting.php";
    flagUrl = "http://localhost/barekat/module-DBGet/livedata.png";
</script>
<script src="jquery-3.6.4.min.js"></script>

<html>
    <div id="sqlInteractArea" style="/*display:none;*/ width:90%; padding:10px; font-size:12px; font-family:Tahoma; border:1px solid darkgreen; background-color: lightblue;">
        <div>
            <button id="liveButton" onclick="toggleMakeLive()">Make Live</button>
        </div>
        <div id="liveFlagSection"></div>
        
        What would you like to do? Choose to:<br>
        <form>           
            <input type="radio" name="select_all_or_some_from_table" onClick="_1_getAnEntireTable(); document.getElementById('copyNclose').style.display='none';">Get an entire table<br>
            <input type="radio" name="select_all_or_some_from_table" onClick="_2_getRecordsOfASelectTable(); document.getElementById('copyNclose').style.display='none';">Get selected records from a table
        </form>
        <div id="messageHolder1" style="padding-top:10px;"></div>
        
        <div id="AreaToSelectATable" style="display:none;">
            <select id="DropdownToSelectATable" style="" name="listOfTallTables">
                <option value="select a table">Select a table</option>
            </select>
            <div id="placeForButtonToSelectSomeRecords"></div>
            <div id="messageHolder2" style="padding-top: 10px; background-color: blanchedalmond; display: none;">
                When ready, click the button below:
                <button onClick="insertAllRecordsForSelectedTable()" >Continue</button>
            </div>            
        </div>

        <div id="placeWhereSelectedRowsIDIsShown"></div>
        <div id="divWhereSelectionOfRecordsOccur" style="display:none; width:100%; height:500px; overflow: scroll;"></div>
        <div id="divForButtonWithRecordSelections" style="display: none;">
            <input type="button" id="button1" onClick="insertSelectedRecordsForTable(tableToWorkWith); document.getElementById('copyNclose').style.display='block'; document.getElementById('divWhereSelectionOfRecordsOccur').style.display='none'; document.getElementById('button1').style.display='none';" style="width:200px; height:100px;" value="Insert Selected Records">
        </div>
        <div id="copyNclose" style="display:none;"><img id="copyNclose" src="./copyNclose.png" onclick="copyNclose()"></div>
        <div id="finalResultsArea" style="border:1px solid blue; /*width:800px; overflow:scroll;*/"></div>
    
    </div>
    
</html>


<script>

    /*var script = document.createElement('script');
    script.src = 'jquery-3.6.4.min.js';
    document.getElementsByTagName('head')[0].appendChild(script);
    */

    get_all_records_or_some_records_of_a_table = "not known yet";
    theSelectedRowsIds = []; //the function highlightAndSelectRow will update this array for keeping track of records selected by user
    tableToWorkWith = "";
    isItLiveData = false; //this variable is set when the user starts requesting live data. This variable carries on the request all the way down
    
    function CopyToClipboard(containerid) {
        if (document.selection) {
            //alert("document.selection")
            var range = document.body.createTextRange()
            range.moveToElementText(document.getElementById(containerid));
            range.select().createTextRange();
            document.execCommand("copy");
        } else if (window.getSelection) {
            //alert("window.getSelection")
            var range0 = document.createRange();
            range0.selectNode(document.getElementById('liveButton'));
            window.getSelection().addRange(range0);

            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
            document.execCommand("copy");
            alert("Text copied. Paste the copied content into your work area. This window will now close");
        }
    }


    function copyNclose() {
        CopyToClipboard("finalResultsArea");
        window.close();

    }

    function insertSelectedRecordsForTable(tableName) {
        //alert("now insert records from"+tableName+" -- records: " + theSelectedRowsIds);
        
        xhr = new XMLHttpRequest()
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                theSelectedRowsOfTable = xhr.responseText;
                if (isItLiveData) {
                    //alert("live data. records: " + theSelectedRowsIds);
                    appendLiveSectionAndHaveUpdateButton(theSelectedRowsOfTable, "finalResultsArea", tableName, theSelectedRowsIds )
                } else {
                document.getElementById("finalResultsArea").style.cssText+="display:block;";
                document.getElementById("finalResultsArea").innerHTML = theSelectedRowsOfTable;
                }
            }
        }	
    
        formData = new FormData();	
        formData.append("tableName", tableName);
        formData.append("whichRows", theSelectedRowsIds);
        formData.append("start","yes");
    
        xhr.open("POST", DBGet_php_script2);
        xhr.send(formData);
    
    }
    
    //this function is called when PHP script requests row highlighting after user clicks to select a row
    function highlightAndSelectRow(rowId) {
        //alert(rowId);
        if (theSelectedRowsIds.indexOf(rowId) == -1) {
            //add
            theSelectedRowsIds.push(rowId);
        } else {
            //remove
            index = theSelectedRowsIds.indexOf(rowId);
            theSelectedRowsIds.splice(index, 1);
            //alert ("remove -- now: " + theSelectedRowsIds);
        }
        document.getElementById("row"+rowId).style.backgroundColor="lightgreen";
        document.getElementById("placeWhereSelectedRowsIDIsShown").innerHTML = "Selected records: " + theSelectedRowsIds;
    }
    
    function getRecords(getTableName, getEnableSelecting) {
    
    }
    
    function getTheRecordsToSelectFromThisTable(tableName) {
        tableToWorkWith = tableName;
        theSelectedRowsIds = [];
        document.getElementById("placeWhereSelectedRowsIDIsShown").innerHTML = "No records selected. Select from the records below.";
        xhr = new XMLHttpRequest();
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                recordsToSelect = xhr.responseText;
                document.getElementById("divWhereSelectionOfRecordsOccur").innerHTML =recordsToSelect;
            }
        }	
    
        formData = new FormData();	
        formData.append("tableName", tableName);
        formData.append("enableSelecting", "yes");
        formData.append("start","yes");
        
        xhr.open("POST", DBGet_php_script2);
        xhr.send(formData);
    
    }
    


    countLiveSections = 0; //not used
    randomNumberForLiveSection = Math.floor(Math.random() * (999999 - 0 + 1) + 0); //between 0 and 999999
        function appendLiveSectionAndHaveUpdateButton(getContent, getParentElemId, getTableName, getWhichRecords) {
            //alert("received: getContent\n"+getParentElemId+"\n"+getTableName+"\n"+getWhichRecords);
            countLiveSections += 1; //not used
            //create an element that is a div containing data
            liveElem = document.createElement("div");
            liveElem.style="border:8px solid green; resize: both; overflow: auto;";
            liveElem.contenteditable="true";
            liveElem.id = "liveContent"+randomNumberForLiveSection;
            //USEFUL BUTTON: liveElem.innerHTML = liveReloadButton(getTableName,getWhichRecords,liveElem.id);
            liveElem.innerHTML = "<div style='font-size:10px;'>Live data starts here - table:"+getTableName+"; which records:"+getWhichRecords+"; id: " + liveElem.id + ";</div>";
            liveElem.innerHTML += "<img src='"+flagUrl+"' width=100 data-live=\"yes\" data-tableName='"+getTableName+"' data-whichRecords='"+getWhichRecords+"'>";
            liveElem.innerHTML += getContent;
            liveElem.innerHTML += "<div style='font-size:10px;'>Live data ends here</div>";
            document.getElementById(getParentElemId).innerHTML = "";
            document.getElementById(getParentElemId).style.display="block";
            document.getElementById(getParentElemId).appendChild(liveElem);
    
        }
    
        function keepLiveSectionUpdated(elem,getParentElemId,getTableName,getWhichRecords) {
            /*setInterval(function()
            {
                //liveElem.innerHTML+="live data from table "+getTableName+", records: " + getWhichRecords;
                //liveElem.innerHTML=getRecords(getTableName,"no");
                xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState = XMLHttpRequest.DONE) {
                        records = xhr.responseText;
                        liveElem.innerHTML = records;
                    }
                }	
                formData = new FormData();	
                formData.append("tableName", getTableName);
                formData.append("enableSelecting", "no");	
                xhr.open("POST", './dbget_show_All_or_Selected_RowsOfATable_EnableSelecting.php');
                xhr.send(formData);
                
            },
            1000)*/
        }
    
        function insertAllRecordsForSelectedTable() {
            xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    theWholeTableContent = xhr.responseText;
                    /* make the data that will be appended to finalResultsArea live if live data is requested
                    live data means:
                    - the div has a tag so when hitting this tag in viewing the record, it will just replace the div anew based on the features of the div, i.e. the attribute saying what table this is, and what records it has
                    - the same tag system is used when wanting to edit the table: it will remove the div node and loads it anew, say every 5 minutes
                    - the div is not editable
                    - the div has a unique id
                    - the div auto refreshes every 5 minutes
                    */
                    if (isItLiveData) { 
                        //alert("load once !!!");
                        //alert ("live data should be added");
                        appendLiveSectionAndHaveUpdateButton(theWholeTableContent, "finalResultsArea", tableName, "");
                    } else {	
                        document.getElementById("finalResultsArea").style.cssText+="display:block;";			
                        document.getElementById("finalResultsArea").innerHTML = theWholeTableContent;
                    }
                }
            }	
            tableName = document.getElementById("DropdownToSelectATable").value;
            document.getElementById("finalResultsArea").innerHTML = document.getElementById("DropdownToSelectATable").value;
            document.getElementById("copyNclose").style.display="inline-block";
            formData = new FormData();	
            formData.append("tableName", tableName);
            formData.append("enableSelecting", "no");
            formData.append("start","yes");
            xhr.open("POST", DBGet_php_script2);
            xhr.send(formData);
        }
    
    
    
        $('#DropdownToSelectATable').change(function() {
            //alert("change");
            var selectedTable = jQuery(this).val();
            if (get_all_records_or_some_records_of_a_table=="some records") {
                //alert (selectedTable);
                getTheRecordsToSelectFromThisTable(selectedTable);
                document.getElementById("divWhereSelectionOfRecordsOccur").style.display="block";
                document.getElementById("divForButtonWithRecordSelections").style.display="block";
                document.getElementById("placeWhereSelectedRowsIDIsShown").style.display="block";
            }
            else if (get_all_records_or_some_records_of_a_table=="all records") { 
                document.getElementById("divWhereSelectionOfRecordsOccur").style.display="none";               
                document.getElementById("messageHolder2").style.display="block";
            }
        })
        
        function fillTablesList(getTablesList) {
            //reset select drop down first
            document.getElementById("DropdownToSelectATable").innerHTML = "";        
            console.log("Now filling select list with:\n" + typeof(getTablesList));
            arrTablesList = getTablesList.split(",");
            
            for (i=0; i<arrTablesList.length; i++) {
                //console.log(arrTablesList[i])
                createdOption = document.createElement("option");
                createdOption.value= arrTablesList[i];
                console.log(arrTablesList[i]+"\n");
                createdOption.id = "tableOption" + i;
                createdOption.innerHTML = arrTablesList[i];
                document.getElementById("DropdownToSelectATable").appendChild(createdOption);
            }
        }
    
    
    function getListOfAllowedTables(get_user_role) {
        formData = new FormData();	
        formData.append("userRole", get_user_role);
        
        xhr = new XMLHttpRequest()
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState = XMLHttpRequest.DONE) {
                tablesList = xhr.responseText;
                fillTablesList(tablesList);
                //document.getElementById("placeForButtonToSelectSomeRecords").innerHTML="<Button>Show the records to choose from</Button>"
            }
        }	
    
        xhr.open("POST", DBGet_php_script1);
        xhr.send(formData);
    
    }
    
    function _2_getRecordsOfASelectTable() {
            document.getElementById("AreaToSelectATable").style.display="block";        
            document.getElementById("messageHolder1").innerHTML="Select a table from the list, which will show its records. You can then select your records.";
            document.getElementById("finalResultsArea").style.cssText+="display:none;";
            document.getElementById("messageHolder2").style.cssText+="display:none;";

            document.getElementById("divWhereSelectionOfRecordsOccur").style.display="none";
            document.getElementById("divForButtonWithRecordSelections").style.display="none";
            document.getElementById("placeWhereSelectedRowsIDIsShown").style.display="none";
            get_all_records_or_some_records_of_a_table = "some records";
            getListOfAllowedTables("admin");
    
        }
    
        function _1_getAnEntireTable() {
            //alert("get an entire table");
            document.getElementById("finalResultsArea").style.cssText+="display:none;";
            document.getElementById("AreaToSelectATable").style.display="block";
            document.getElementById("messageHolder1").innerHTML="Select your table from the list, which selects its entire records.";
            document.getElementById("divWhereSelectionOfRecordsOccur").style.display="none";
            document.getElementById("divForButtonWithRecordSelections").style.display="none";
            document.getElementById("placeWhereSelectedRowsIDIsShown").style.display="none";
            get_all_records_or_some_records_of_a_table = "all records";            
            getListOfAllowedTables("admin");	
        }
    
        function toggleMakeLive() {
            if (isItLiveData==true) {
                isItLiveData = false;
                document.getElementById("liveFlagSection").innerHTML = "";
                document.getElementById("liveButton").innerHTML="Make live";
            } else {
                isItLiveData = true;
                document.getElementById("liveFlagSection").innerHTML = "<img src='"+flagUrl+"' width=100> <p style='background-color:lightgreen;'>(Select data anew for this to take effect )";
                document.getElementById("liveButton").innerHTML="Make static";
            }
        }

        function DBGet_startSQLInteract(liveDataBoolean) {
            isItLiveData = liveDataBoolean;	

            if (liveDataBoolean == true) {
                document.getElementById("liveFlagSection").innerHTML = "<img src='"+flagUrl+"' width=100>";
            } else {

                document.getElementById("liveFlagSection").innerHTML = "";
            }
        }

</script>

<?php
    isset($_GET['liveDataEntryRequested']) ? $startWithThis =  "<script>toggleMakeLive();</script>" :  $startWithThis='';
    echo $startWithThis;
?>
