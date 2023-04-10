# DBGet

To see the video demo of the project, click here: https://youtu.be/WN_tQGnT3Uk

For more information about the Database application itself that makes logging and managing knowledge organization easier, click here: https://bamshadit.com/knowledg

########## DESCRIPTION ##########

A module to retrieve and show live or static data from selected records or whole tables in PHP applications

 In PHP applications that interact with a database, there are times when you need reference to content available in a table or record while working with the content of another record somewhere else. As your data is related to other records of data, you would want reference and presentation of what exists in those tables.


You might be creating a record in a table that is similar to a document (or a report) that consists of data gathered and grouped across several tables. You might want live references to these records in your document because the entirety of what you are preparing has close connection to what is stored in these tables; All of this data should be visible in your document, but still keeping the groups of records up-to-date is important at all times without interfering with their content as you present them in your document.


This is where the solution provided here comes in picture. The module I have developed is called DBGet (For Database data retrieval -- or getting) and lets the user open a window where he chooses records of a table or a whole table, after which he he imports them into his document or table record where he is working -- while the data stays "live" and up-to-date at all times.


The data that is input now into the user work area keeps its reference to its original source and updates itself with every reload of the record or push of the button available next to the data. Rather than referencing data as a link, the data itself is present and live at all times, making it a part of the document the user is working on. 

########### INSTALLATION INSTRUCTIONS ##########

Files that are involved with the process:

./module-DBGet/DBGet_version1.php
./module-DBGet/DBGet_live.js
./module-DBGet/jquery-3.6.4.min.js

./module-DBGet/copyNclose.png
./momdule-DBGet/livedata.png


./DBGet_loadListOfAllowedTables.php
./DBGet_show_All_or_Selected_RowsOfATable_EnableSelecting.php
./DBGet_service1.php
./DBGet_service2.php

Indirectly and in the Knowledge Database application:
(available separately)

./MyHTMLBox5/2_Ribbon_functions_scriptTagIncluded.js
./menuAndDbAndLayout.php
./settings.php
./generalWorks.php

-------------------------------------------
How to install:

1. include the file below in a place where all files
are loaded, for example in file menuAndDbAndLayout.php

<script src="./module-DBGet/DBGet_live.js"></script>


2. In the file DBGet_live.js update the first line
to reflect the path to the file:

DBGet_show_All_or_Selected_RowsOfATable_EnableSelecting.php


3. In the file ./module-DBGet/DBGet_version1.php update
the location of the 3 files mentioned at the top of the
script, i.e. for:
DBGet_php_script1
DBGet_php_script2
flagUrl


4. require in the settings the following 3 files:

require_once ("./DBGet_service1.php");
require_once ("./DBGet_service2.php");
require_once ("./DBGet_show_All_or_Selected_RowsOfATable_EnableSelecting.php");


5. have in the root folder, the DBGet folder which includes
the two main files and the jquery file:

./module-DBGet/DBGet_version1.js.html
./module-DBGet/DBGet_live.js
./module-DBGet/jquery-3.6.4.min.js


6. Finally, when you load content to edit, process the content
first before returning it to user. Call the function
that makes the update:

$this_content = DBGet_processContentForLiveData($this_content);

This happens in line 92 of the file:
./kdb/structuredSet_db_editRow_showForm.php


7. Note: The module is accessed through the MyHTMLBox5 file 2 in functions:
referenceContent_WithUpdates(getPieceNumber) and
referenceContent_CopyContent(getPieceNumber)

