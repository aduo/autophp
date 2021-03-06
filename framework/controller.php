<?php

/**
 * @author ricolau<ricolau@qq.com>
 * @version 2016-08-18
 * @desc controller base
 *
 */
abstract class controller extends base {

    protected $_render = null;

    

    protected function _init() {
        $this->_render = new render_default();
    }

    public function setRenderEngine($obj) {
        $this->_render = $obj;
    }

    public function render($path = '') {
        header('_runId: '.auto::getRunId());

        $_debugMicrotime = microtime(true);
        if ($path) {
            $this->_render->render($path);
            
            ($timeCost = microtime(true) - $_debugMicrotime) && performance::add(__METHOD__, $timeCost, array('path'=>$path));
            
            return;
        }
        $dir = dispatcher::instance()->getModuleName();
        $controller = dispatcher::instance()->getControllerName();
        $action = dispatcher::instance()->getActionName();

        if (dispatcher::instance()->getPathDeep() == dispatcher::path_deep3) {
            $path = $dir . DS . $controller . DS . $action;
        } else {
            $path = $controller . DS . $action;
        }
        $this->_render->render($path);

        ($timeCost = microtime(true) - $_debugMicrotime) && performance::add(__METHOD__, $timeCost, array('path'=>$path));
    }

    public function slot($slot, $isDisplay = false) {
        return $this->_render->slot($slot, $isDisplay);
    }

    public function fetch($path = '') {
        $_debugMicrotime = microtime(true);
        if ($path) {
            $ret = $this->_render->fetch($path);

            ($timeCost = microtime(true) - $_debugMicrotime) && performance::add(__METHOD__, $timeCost, array('path'=>$path));
            
            return $ret;
        }
        $dir = dispatcher::instance()->getModuleName();
        $controller = dispatcher::instance()->getControllerName();
        $action = dispatcher::instance()->getActionName();

        if (dispatcher::instance()->getPathDeep() == dispatcher::path_deep3) {
            $path = $dir . DS . $controller . DS . $action;
        } else {
            $path = $controller . DS . $action;
        }

        $ret = $this->_render->fetch($path);

        ($timeCost = microtime(true) - $_debugMicrotime) && performance::add(__METHOD__, $timeCost, array('path'=>$path));
        
        return $ret;
    }

    public function forward($path) {
        dispatcher::instance()->setPath($path)->dispatch()->run();
    }

    public function assign($key, $val) {
        $this->_render->assign($key, $val);
    }

    public function massign($key) {
        $this->_render->massign($key);
    }

    public function renderJson($data) {
        if(!headers_sent()){
            header('_runId: '.auto::getRunId());
            header('Content-type: application/json');
        }
        response::outputJson($data);
    }
    
    
    public function requestGet($key, $type='str', $default=null){
        return request::get($key, $type, $default);
    }
    public function requestPost($key, $type='str', $default=null){
        return request::post($key, $type, $default);
    }
    
    public function requestGetAll(){
        return request::getAll();
    }
    public function requestPostAll(){
        return request::postAll();
    }

}
