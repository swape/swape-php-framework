<?php

function startFramework()
{
    $arrVars = [];

    // get the system path and remove last part "sf"
    $arrSystemPath = explode('/', __DIR__);
    array_pop($arrSystemPath);
    $arrVars['path'] =  implode('/', $arrSystemPath);

    // require the config file
    if(file_exists($arrVars['path'] . '/sf/config.php')) {
        include 'config.php';
    }

    // set the template path
    $arrVars['templatePath'] = $arrVars['path'] . '/app/template/';
    $arrVars['controllerPath'] = $arrVars['path'] . '/app/controller/';

    // set the query from url
    $arrVars['queryPath'] = explode('?', $_SERVER['REQUEST_URI']);

    // set the method
    $arrVars['method'] = $_SERVER['REQUEST_METHOD'];

    // skip the first empty element
    $arrReqPath = explode('/', $arrVars['queryPath'][0]);
    array_shift($arrReqPath);

    // set the default template url
    $arrVars['templateDir'] = $arrReqPath[0];
    $arrVars['templateFile'] = $arrVars['templateDir'] . '/index.php';

    // look for sub template path from url
    if(isset($arrReqPath[1]) and $arrReqPath[1] != '') {
        $arrVars['templateFile'] = $arrVars['templateDir'] . '/' . $arrReqPath[1] .'.php';
    }

    // by default we don't have template
    $arrVars['haveTemplate'] = false;

    // if the file exists
    if(file_exists($arrVars['templatePath'] . $arrVars['templateFile'])) {
        $arrVars['haveTemplate'] = true;
    }

    // load the controller if we have it
    $arrVars['haveController'] = false;
    $arrVars['haveFunction'] = false;

    $strControllerFileName = $arrVars['controllerPath'] . $arrReqPath[0] .'.php';
    if(!isset($arrReqPath[0]) or $arrReqPath[0] == '') {
        $strControllerFileName = $arrVars['controllerPath'] . 'index.php';
        $arrReqPath[0] = 'index';
    }

    if(file_exists($strControllerFileName)) {
        // we just call db if we have a controller
        $arrVars['db'] = getDbObj();

        $arrVars['haveController'] = true;
        require $strControllerFileName;
        $strClassName = 'sf_' . $arrReqPath[0] .'Class';
        $objClass = new $strClassName;

        // getting the Function
        $strFunctionName = 'sf_index';
        if(isset($arrReqPath[1]) and $arrReqPath[1] != '') {
            $strFunctionName = 'sf_' . $arrReqPath[1];
        }
        $arrCallable = [$objClass , $strFunctionName];
        if(is_callable($arrCallable)) {
            $arrVars['haveFunction'] = true;
            // sending data from POST if we have any
            $arrVars['data']['get'] = $_GET;
            $arrVars['data']['post'] = $_POST;
            $arrVars['data']['header'] = getallheaders();

            $inputReq = file_get_contents('php://input');
            if($inputReq){
              $jsonData = json_decode($inputReq, true);
              if($jsonData){
                $arrVars['data']['req'] = $jsonData;
              }else{
                parse_str($inputReq, $arrVars['data']['req']);
              }
            }


            $arrData = call_user_func_array($arrCallable, ['vars'=>$arrVars]);
            if(!isset($arrData)){
              $arrData = [];
            }
        }
    }

    if($arrVars['haveTemplate']) {
        if($arrVars['haveFunction']){
        render($arrVars['templatePath'] . $arrVars['templateFile'], $arrData);
        }else{
          render($arrVars['templatePath'] . $arrVars['templateFile'], []);
        }
    } else{
        if($arrVars['haveFunction']) {
            header('Content-Type: application/json');
            echo json_encode($arrData);
        }else{
          // 404 page
          http_response_code(404);
          header('Content-Type: application/json');
          echo json_encode(['error'=>'not found']);
        }
    }

}

function render($template,&$data)
{
    include $template;
}

startFramework();
