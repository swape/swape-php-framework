Coding Styles
===

###Use tabs instead of spaces for indenting.
The code must look nice and not get too large.
So any unnecessary space must be eliminated.

###Use lowerCamelCase for methods, classes and variable names

###prefixing variables

It is nice to know what variable contains.

    $arrMyArray = [];
    $intMyNumber = 0;
    $strName = 'Alireza Balouch';
    $objApi = new awesomeApi();
    
If the code gets long and complicated you allways know what to excpect from that variable and how to check the values.

###Brackets must accure on the same line without spaces

We all use nice and awesome editors and it is easy to spot the errors and forgotten brackets.
And it is nicer to see what that end brackets belongs to in the same indent level then to meet the opening bracket.
And again the spces between ) and { are not doing any help on keeping things nicer to read. Editors are awesome to highlight things so why waste those extra spaces.

    class myClass{
        public function myFunction($arrSettings){
            if(count($arrSettings) == 5){
                // do some magic here
            }else{
                // do some other things here    
            }
        }
    }


###Single quote

If you use dobble quote, the compiler must go through the sting to see if there are any variables or linebreak or something and it is not necessary to slow down the output it you are not going to use those stuff. And it is nicer to have the variable outside the  string so that you can rename or edit the name (like sublime does) or just see where you use the variables.

    $strMyString = 'This is a long string. My name is ' . $strName . ' and I like this code.' . "\n";
    
