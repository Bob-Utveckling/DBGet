DBGet_php_script2 = "http://localhost/barekat/DBGet_show_All_or_Selected_RowsOfATable_EnableSelecting.php";

    /*This section for the live button ================================= */

    function liveReloadButton(getTableName,getRecords, getElemId) {
        return "<Button id='liveButton' onClick=reloadLiveData('"+getTableName+"','"+getRecords+"','"+getElemId+"') style='height:100px; cursor: pointer; margin:10px; background-color:lightgreen;'>Reload This Live Data</Button>";
    } 


    function reloadLiveData(getTableName,getRecords,getElemId) {
        //alert("reloadLiveData - received " + getTableName + " " + getRecords + " " + getElemId);
        //alert("reload given records: " + getRecords);
        //document.getElementById(getElemId).innerHTML = "123";
        
        xhr = new XMLHttpRequest()
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                theContent = xhr.responseText;
                //document.getElementById(getElemId).remove()
                
                //placeHolder = document.createElement("div");
                //placeHolder.innerHTML = theContent;
                //alert ("elem with id: " + getElemId + " innerHTML: " + document.getElementById(getElemId).innerHTML);
                document.getElementById(getElemId).style="border:5px solid blue;";
                document.getElementById(getElemId).innerHTML = theContent;
                        //"<div style='font-size:10px;'>Live data starts here - table:"+getTableName+"; which records:"+getRecords+"; id: " + getElemId + ";</div>" +
                        //liveReloadButton(getTableName,getRecords, getElemId) + theSelectedRowsOfTable +
                        //"<div style='font-size:10px;'>Live data ends here</div>";
            }
        }
        formData = new FormData();	
        formData.append("returnALiveCopy", "yes");
        formData.append("tableName", getTableName);
        formData.append("whichRows", getRecords);
        formData.append("elemId", getElemId);
        formData.append("start","yes");
        xhr.open("POST", DBGet_php_script2);
        xhr.send(formData);
    
    }

    /* ====================================== up to here ================ */
