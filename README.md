# DBGet
A module to retrieve and show live or static data from selected records or whole tables in PHP applications

###########

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

