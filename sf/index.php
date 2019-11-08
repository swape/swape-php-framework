<?php
// this is in case we run other than apache
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

function startFramework()
{
    $arr_vars = [];

// get the system path and remove last part "sf"
    $system_path = explode('/', __DIR__);
    array_pop($system_path);
    $arr_vars['path'] = implode('/', $system_path);

// require the config file
    if (file_exists($arr_vars['path'] . '/sf/config.php')) {
        include 'config.php';
    }

// set the template path
    $arr_vars['templatePath'] = $arr_vars['path'] . '/web/app/template/';
    $arr_vars['controllerPath'] = $arr_vars['path'] . '/web/app/controller/';

// set the query from url
    $arr_vars['queryPath'] = explode('?', $_SERVER['REQUEST_URI']);

// set the method
    $arr_vars['method'] = $_SERVER['REQUEST_METHOD'];

// skip the first empty element
    $req_path = explode('/', $arr_vars['queryPath'][0]);
    array_shift($req_path);

// set the default template url
    $arr_vars['templateDir'] = $req_path[0];
    $arr_vars['templateFile'] = $arr_vars['templateDir'] . '/index.php';

// look for sub template path from url
    if (isset($req_path[1]) and $req_path[1] != '') {
        $arr_vars['templateFile'] = $arr_vars['templateDir'] . '/' . $req_path[1] . '.php';
    }

// by default we don't have template
    $arr_vars['haveTemplate'] = false;

// if the file exists
    if (file_exists($arr_vars['templatePath'] . $arr_vars['templateFile'])) {
        $arr_vars['haveTemplate'] = true;
    }

// load the controller if we have it
    $arr_vars['haveController'] = false;
    $arr_vars['haveFunction'] = false;

    $controller_filename = $arr_vars['controllerPath'] . $req_path[0] . '.php';
    if (!isset($req_path[0]) or $req_path[0] == '') {
        $controller_filename = $arr_vars['controllerPath'] . 'index.php';
        $req_path[0] = 'index';
    }

    if (file_exists($controller_filename)) {
        $arr_vars['haveController'] = true;
        require $controller_filename;
        $controller_class_name = 'sf_' . $req_path[0] . 'Class';
        $controller_class = new $controller_class_name;

// getting the Function
        $controller_function_name = 'sf_index';
        if (isset($req_path[1]) and $req_path[1] != '') {
            $controller_function_name = 'sf_' . $req_path[1];
        }
        $arr_callable = [$controller_class, $controller_function_name];
        if (is_callable($arr_callable)) {
            $arr_vars['haveFunction'] = true;

// sending data from POST if we have any
            $arr_vars['data']['get'] = $_GET;
            $arr_vars['data']['post'] = $_POST;
            $arr_vars['data']['header'] = getallheaders();

            $input_req = file_get_contents('php://input');
            if ($input_req) {
                $json_data = json_decode($input_req, true);
                if ($json_data) {
                    $arr_vars['data']['req'] = $json_data;
                } else {
                    parse_str($input_req, $arr_vars['data']['req']);
                }
            }

            $arr_data = call_user_func_array($arr_callable, ['vars' => $arr_vars]);
            if (!isset($arr_data)) {
                $arr_data = [];
            }
        }
    }

    if ($arr_vars['haveTemplate']) {
        if ($arr_vars['haveFunction']) {
            render($arr_vars['templatePath'] . $arr_vars['templateFile'], $arr_data);
        } else {
            render($arr_vars['templatePath'] . $arr_vars['templateFile'], $arr_data);
        }
    } else {
        if ($arr_vars['haveFunction']) {
            addHeader();
            echo json_encode($arr_data);
        } else {
// 404 page
            http_response_code(404);
            addHeader();
            echo json_encode(['error' => 'not found']);
        }
    }
}

function render($template, &$data)
{
    include $template;
}
function addHeader()
{
    header("Access-Control-Allow-Origin: *");
    header("X-Frame-Options: DENY");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Vary: Accept-Encoding");
    header('Content-Type: application/json');
}

startFramework();
