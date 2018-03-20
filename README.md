# swape-php-framework v0.3.0

Easy and fast PHP micro-framework.

Why another php framework?

All frameworks works almost the same as each other. You have to set up a router, a controller and a template. And then telling it to match this path with that template and controller. So this is a micro-framework that takes care of all that. And since it is usual to have a modern custom json api servers and not the old boring RESTFULish api's. It is also taken care of in this framework.

But this framework have it's limitations. But those limitations are not so usual and we can have a workaround for those.

## Getting started
TODO:
- Writing getting started.
- Writing more examples and docs.
- Test with nginx


## Setting up
In apache env, you have to point to web directory. This is where the root of your app should be.

## SPF on Heroku
Procfile, composer.json and app.json is for Heroku. Please see documentation for php apps on Heroku.
If you are not planing to have this app on Heroku, you can delete these files.

### Directory set up.

```
/web/app/controler   # controllers directory
/web/app/template    # template directory
/sf                  # all the framework files
/web/static          # static files like js and img and others
```

When you first enter the page, the framework is going to look for matching template and controller.

Let's say the url is `http://localhost:8080/test`. Then it is looking for template with the name `app/template/test/index.php` and show the content of that file. So the _test_ is the matching template from the path to the file.

The same is url is also going to look for a controller `app/controller/test.php` and run the **sf_index** function inside the **sf_testClass** class.

If there is no matching controller, then it shows the template.

If there is no template but there is a controller it run that matching controller function and if there is a return data array it prints it out as json. This is real handy for making json api.

If you have both controller and template it runs the function and return the _return array_ from the function and reveal it with the template through the **$data** array variable.

If the url have another level like this: `http://localhost:8080/test/another`, then it is looking for the template in  `app/template/test/another.php` and a controller file `app/controller/test.php` with the class name **sf_testClass** and a function named **sf_another()**.

### Controller example

```php
<?php
// path: app/controller/test.php
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

Here is a typical controller that have the name **test.php**, and a class name **sf_testClass** and functions with **sf\_** prefix just like the class name.

| Url                     | controller file name    | class name      | function name |
| ----------------------- | ----------------------- | --------------- | ------------- |
| /test                   | /app/controller/test.php    | sf_testClass    | sf_index      |
| /test/test              | /app/controller/test.php    | sf_testClass    | sf_test       |
| /test/test2             | /app/controller/test.php    | sf_testClass    | sf_test2      |
| /                       | /app/controller/index.php   | sf_indexClass   | sf_index      |
| /index                  | /app/controller/index.php   | sf_indexClass   | sf_index      |
| /index/test             | /app/controller/index.php   | sf_indexClass   | sf_test       |
| /index/index            | /app/controller/index.php   | sf_indexClass   | sf_index      |
| /another/myfunc         | /app/controller/another.php | sf_anotherClass | sf_myfunc     |
| /another/myfunc/testing | /app/controller/another.php | sf_anotherClass | sf_myfunc     |

Notice the last url `/another/myfunc/testing` is going to act as it was just `/another/myfunc`. Don't worry you can get the full path in the argument array of the function.

Every function must return an array. This array is used to make a return json if there is no template or if there is a matching template it is revealed as array **$data**.

### Function argument array

If you like you can get the ready made array of data. `sf_index($arr)`

Content of this array is where you can find variables like query parameters, request method, db object and other useful information.

### Function return array and template data

When a function returns an array, it is passed to matching template name.
If there is no template, then it is converted to json object.

But if there is a template then data can be accessed from **$data** variable in template file.

### Setting up DB

To set up a mysql or other PDO database, you have to set up the credentials in **sf/config.php** file
And the PDO object is fetched into the function with all other data.

Here is an example of how you can make two api routes with "get all" function and "insert" function.

```php
<?php
// Path: /app/controller/api.php
class sf_apiClass
{

    public function sf_index($arr)
    {
      $result = $arr['db']->query('SELECT * FROM `test_table`');
      return ['myvar'=> $result];
    }

    public function sf_insert($arr)
    {
      if ($arr['method'] == 'POST') {
        $strSQL = "INSERT INTO test_table SET text = :mytext ";
        $arrParams = [
          ['name'=>':mytext' , 'value'=> $arr['data']['req']['myvar']]
        ];

        $result = $arr['db']->query($strSQL, $arrParams);
        return ['myvar'=> $result];
      } else {
        return ['method'=> $arr['method'] ];
      }
    }
}

```
As you can see **sf_index** is getting the PDO object from **$arr['db']** variable.

You can use PDO object directly with **$arr['db']->pdo** or use the **$arr['db']->query** to access the database. 

In sf_index function, we check if method is a POST and then we get the data from  **$arr['data']['req']** this is fetched from json or url encoded POST method from the browser.

**$arr['data']** also contains post and get data.

### Template

Since PHP is a templating language, we don't need to learn another templating language that is slower than PHP.
All the matching php files under _app/template_ is mapped automatically to the path.

| Url                | template file name            |
| ------------------ | ----------------------------- |
| /test              | /app/template/test/index.php  |
| /test/test         | /app/template/test/test.php   |
| /test/test2        | /app/template/test/test2.php  |
| /                  | /app/template/index.php       |
| /index             | /app/template/index/index.php |
| /index/test        | /app/template/index/test.php  |
| /index/index       | /app/template/index/index.php |
| /index/index/index | /app/template/index/index.php |

If there is no matching controller, only the template is showed. But if there is a matching controller, return data from that controller function is passed to the template.
