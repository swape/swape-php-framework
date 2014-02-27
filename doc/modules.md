##Modules##

Every module have a directory under modules.
Module name (directory name) is the same as the first URL segment.
Module name and the class name and the file name must be the same.

	/modules
		/main
        	 /mainClass.php
             
And the class name in the mainClass.php must have the name mainClass.

The second part of the URL (the one after the module name), is the function name.

So if the URL is **/main/index** , then the function sf_index in mainClass in mainClass.php from main directory under module directory is called.

**sf_index** is the default function so the URL can just be */main/*

variable **public $intPerm = 0;** is optional. If it is 0 then all can see its functions.
If 1,only for signed in useres and 2 for custom permission basesd on databse table *sf_menu*.




 