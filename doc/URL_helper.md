URL helper
---

$objURL is a helper to find the path and routing.
By default class and function name is what you need.

Lets say the URL is:

    http://somewhere.com/classname/myfunction
    
Then this is going to call the function called sf_myfunction in class classnameClass in clasnameClass.php file under a directory called classname under modules.

But lets say want to add some more to the path.

    http://somewhere.com/classname/myfunction/myvar/something/blabla/2
    
This is where $objURL have the answers ;)
Just like the database class you can call the urlClass from the global vars.

    function sf_myfunction(){
        global $objURL;
        
        $strVar1 = @$objURL->arrVar[3]; // myvar
        $strVar2 = @$objURL->arrVar[4]; // something
        $strVar3 = @$objURL->arrVar[5]; // blabla
        $strVar4 = @$objURL->arrVar[6]; // 2
    
    }
    
$objURL->arrVar[1] contains class name and $objURL->arrVar[2] contains the function (without the prefix ``sf_``) how it appears on the URL path.

$objURL->arrQuery have all the query value pair string.

So everything after `?` is added to this array.

