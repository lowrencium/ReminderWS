<?php
class PostsController extends AppController {
    var $components = array('RequestHandler');

    function service() {
        $this->layout = false;
        $this->autoRender = false;
        Configure::write('debug', 0);
        ini_set("soap.wsdl_cache_enabled", "0");
        $server = new SoapServer('http://86.74.103.173/reminder/reminderWS/posts/wsdl');
        $server->setClass("Post");
        $server->handle();
    }

    function wsdl() {
        $this->layout = false;
        Configure::write('debug', 0);
        $this->RequestHandler->respondAs('xml');
    }
}
?>