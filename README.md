swape-php-framework
===

Easy and fast PHP micro-framework.

Why another php framework?

All frameworks works almost the same as each other. You have to set up a router, a controller and a template. And then telling it to match this path with that template and controller. So this is a micro-framework that takes care of all that. And since it is usual to have a modern custom json api servers and not the old boring RESTFULish api's. It is also taken care of in this framework.

But this framework have it's limitations. But those limitations are not so usual and we can have a workaround for those.

## Getting started

### Directory set up.

```
/controler   # controllers directory
/template    # template directory
/sf          # all the framework files
/static      # static files like js and img and others
```

When you first enter the page, is going to look for matching template and controller.

Let's say the url is `http://localhost:8080/test`. Then it is looking for template with the name `/template/test/index.php` and show the content of that file. So the *test* is the matching template from the path to the file.

The same is url is also going to look for a controller `/controller/test.php` and run the **sf_index** function inside the **sf_testClass** class.

If there is no matching controller, then it just show the template.

If there is no template but there is a controller it just run that matching controller and if there is a return data array it prints it out as json. This is real handy for making json api.

If you have both controller and template it runs the function and return the *return array* from the function and run it with the template through the **$data** array.

If the url have another level like this: `http://localhost:8080/test/another`, then it is looking for the template in here `/template/test/another.php` and a controller file `/controller/test.php` with the class name **sf_testClass** and a function named **sf_another()**.

### Controller example

`/controller/test.php`
```php
<?php

class sf_testClass
{
    public function sf_test()
    {
        return ['a'=>1234];
    }

    public function sf_index($arr)
    {
        return ['ok'=>'index'];
    }

    public function sf_test2()
    {
        return ['some_data'=> 42];
    }
}

```

Here is a typical controller that have the name **test.php**, and a class name **sf_testClass** and functions with **sf_** prefix just like the class name.

| Url | controller file name | class name | function name |
| -- | -- | -- | -- |
| /test | /controller/test.php | sf_testClass | sf_index |
| /test/test | /controller/test.php | sf_testClass | sf_test |
| /test/test2 | /controller/test.php | sf_testClass | sf_test2 |
| / | /controller/index.php | sf_indexClass | sf_index |
| /index | /controller/index.php | sf_indexClass | sf_index |
| /index/test | /controller/index.php | sf_indexClass | sf_test |
| /index/index | /controller/index.php | sf_indexClass | sf_index |
| /another/myfunc | /controller/another.php | sf_anotherClass | sf_myfunc |
| /another/myfunc/testing | /controller/another.php | sf_anotherClass | sf_myfunc |

Notice the last url `/another/myfunc/testing` is going to act as it was just `/another/myfunc`. Don't worry you can get the full path in the argument array of the function.

Every function must return an array. This array is used to make a return json if there is no template or if there is a matching template it is revealed as array **$data**.

### Function argument array

If you like you can get the ready made array of data. `sf_index($arr)`

Content of this array is where you can get stuff like query parameters, request method, db object and other useful information from.

### Function return array and template data

When a function returns an array it is passed to matching template name.
If there is no template, then it is converted to json object.

But if there is a template then data can be accessed from **$data** variable in template file.

### Setting up DB

TODO

### DB models

TODO

### Template helpers

TODO
