<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */

namespace api\Controller;

class ApiController {

    protected $app;
    protected $basePath = '';
    protected $data = array();

    public function __construct($app = null) {
        $this->app = ($app instanceof Slim) ? $app : \Slim\Slim::getInstance();
        $this->app->get('/unauthorized(/:format)', array($this, 'unauthorized'));
        $this->index();
    }

    /**
     * Unauthorized response example
     * @param $format
     */
    public function unauthorized($format = 'json') {
        $this->app->view()->setResponse($this->data, 401, 'Unauthorized');
        $this->app->render($format);
    }

}
