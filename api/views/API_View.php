<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */

namespace Api\View;

class Api_View extends \Slim\View {

    private $app;
    private $output = array('code' => '200', 'message' => 'Successful request.', 'data' => false);

    public function __construct($app) {
        parent::__construct();
        $this->app = $app;
    }

    public function setResponse($data, $status = 200, $message = 'Successful request.') {
        $statusValue = '200';
        $this->output['code'] = $status;
        $this->output['message'] = $message;
        if ($data !== false) {
            $this->output['data'] = $data;
        }
        // optionally, we could overwritte the http status code
        $this->app->response()->status($status);
    }

    public function render($template, $data = null) {
        $template = ($template == 'xml') ? 'xml' : 'json';
        $response = $this->app->response();
        $response['Access-Control-Allow-Origin'] = "*";
        $response['Access-Control-Allow-Headers'] = "Authorization, Cache-Control, X-Requested-With";
        $response['Access-Control-Allow-Methods'] = "GET, POST, PUT, DELETE";
        $response['Content-Type'] = 'application/json';
        $this->app->response($response);
        switch ($template) {
            case 'json':
                $response['Content-Type'] = 'application/json';

                if (($callback = $this->app->request()->get('callback')) !== null) {
                    return $this->jsonpRenderer($callback);
                }

                return $this->jsonRenderer();
            case 'xml':
                $response['Content-Type'] = 'text/xml';
                return $this->xmlRenderer();
        }
    }

    private function xmlRenderer() {

    }

    private function jsonRenderer() {
        return json_encode($this->output);
    }

    private function jsonpRenderer($callback) {
        return sprintf("%s(%s);", $callback, $this->jsonRenderer());
    }
}