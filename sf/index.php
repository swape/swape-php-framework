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

const TEMPLATE_PATH_KEY = 'templatePath';
const CONTROLLER_PATH_KEY = 'controllerPath';
const TEMPLATE_DIR_KEY = 'templateDir';
const TEMPLATE_FILE_KEY = 'templateFile';
const HAVE_TEMPLATE_KEY = 'haveTemplate';
const HAVE_FUNCTION_KEY = 'haveFunction';
const HAVE_CONTROLLER_KEY = 'haveController';

function getPath()
{
  $system_path = explode('/', __DIR__);
  array_pop($system_path);
  return implode('/', $system_path);
}

function makeClassFromPath($path)
{
  $controller_class_name = 'SF' . $path . 'Class';
  return new $controller_class_name;
}

function getControllerName($path)
{
  return (isset($path) && $path != '') ? 'sf_' . $path : 'sf_index';
}

function getTemplateFileName($req_path)
{
  $template_dir = $req_path[0];
  // looking for child-template path from url
  return (isset($req_path[1]) && $req_path[1] != '') ? $arr_vars[TEMPLATE_FILE_KEY] = $template_dir . '/' . $req_path[1] . '.php' : $template_dir . '/index.php';
}

function getRequests($arr_vars)
{
  $input_req = file_get_contents('php://input');
  if ($input_req) {
    $json_data = json_decode($input_req, true);
    if ($json_data) {
      $arr_vars['data']['req'] = $json_data;
    } else {
      parse_str($input_req, $arr_vars['data']['req']);
    }
  }
  return $input_req;
}

function startFramework()
{
  $arr_vars = [];

  // get the system path and remove last part "sf"
  $arr_vars['path'] = getPath();

  // require the config file
  include 'config.php';

  // set the template path
  $arr_vars[TEMPLATE_PATH_KEY] = $arr_vars['path'] . '/web/app/template/';
  $arr_vars[CONTROLLER_PATH_KEY] = $arr_vars['path'] . '/web/app/controller/';

  // set the query from url
  $arr_vars['queryPath'] = explode('?', $_SERVER['REQUEST_URI']);

  // set the method
  $arr_vars['method'] = $_SERVER['REQUEST_METHOD'];

  // skip the first empty element
  $req_path = explode('/', $arr_vars['queryPath'][0]);
  array_shift($req_path);

  // set the default template url
  $arr_vars[TEMPLATE_FILE_KEY] = getTemplateFileName($req_path);

  // by default we don't have template
  $arr_vars[HAVE_TEMPLATE_KEY] = (file_exists($arr_vars[TEMPLATE_PATH_KEY] . $arr_vars[TEMPLATE_FILE_KEY]));

  // load the controller if we have it
  $arr_vars[HAVE_CONTROLLER_KEY] = false;
  $arr_vars[HAVE_FUNCTION_KEY] = false;

  $controller_name = (!isset($req_path[0]) || $req_path[0] == '') ? 'index' : $req_path[0];
  $controller_filename = $arr_vars[CONTROLLER_PATH_KEY] . $controller_name . '.php';
  $req_path[0] = $controller_name;

  if (file_exists($controller_filename)) {
    $arr_vars[HAVE_CONTROLLER_KEY] = true;
    require $controller_filename;
    $controller_class = makeClassFromPath($req_path[0]);

    // getting the Function
    $controller_function_name = getControllerName($req_path[1]);

    $arr_callable = [$controller_class, $controller_function_name];
    if (is_callable($arr_callable)) {
      $arr_vars[HAVE_FUNCTION_KEY] = true;

      // getting data from POST AND GET if we have any
      $arr_vars['data']['get'] = $_GET;
      $arr_vars['data']['post'] = $_POST;
      $arr_vars['data']['header'] = getallheaders();
      $arr_vars['data']['req'] = getRequests($arr_vars);
      $arr_data = call_user_func_array($arr_callable, ['vars' => $arr_vars]);
      if (!isset($arr_data)) {
        $arr_data = [];
      }
    }
  }

  render($arr_vars, $arr_data);
}

function render($arr_vars, &$data)
{
  if ($arr_vars[HAVE_TEMPLATE_KEY]) {
    include $arr_vars[TEMPLATE_PATH_KEY] . $arr_vars[TEMPLATE_FILE_KEY];
  } else {
    if ($arr_vars[HAVE_FUNCTION_KEY]) {
      addStandardHeaders();
      echo json_encode($data);
    } else {
      // 404 page
      http_response_code(404);
      addStandardHeaders();
      echo json_encode(['error' => 'not found']);
    }
  }
}

function addStandardHeaders()
{
  header("Access-Control-Allow-Origin: *");
  header("X-Frame-Options: DENY");
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header("Vary: Accept-Encoding");
  header('Content-Type: application/json');
}

startFramework();
