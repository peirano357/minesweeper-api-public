<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 *
 */

namespace api\Controller;

include_once(BASEPATH . "/settings.php");
include_once '../lib/mygen_framework.php';
include_once '../lib/mygen_mysql.php';
include_once '../lib/DBConn.php';
include_once 'model/User.class.php';

class User extends ApiController {

    protected $basePath = '/user';

    public function __construct($app) {
        parent::__construct();
    }

    public function index() {

        $this->app->post($this->basePath . "/login", array(
            $this, 'login'));

        $this->app->post($this->basePath . "/signup", array(
            $this, 'signup'));
    }

    public function login() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $user = new \User();
            $result = $user->getUserByCredentials($body['userName'], $body['password']);

            if ($result === false) {
                $this->app->view()->setResponse(false, '409', 'Given credentials do not match with an existent user.', null);
            } else {
                $this->app->view()->setResponse($result, '200', "OK");
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine());
        }
        $this->app->render('json');
    }

    public function signup() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $valid = \User::validate($body);
            if ($valid['status'] === true) {
                $result = \User::create($body);
                if ($result !== false) {
                    $this->app->view()->setResponse($result, '201', "OK");
                } else {
                    $this->app->view()->setResponse(false, '409', 'An internal error has occurred while trying to create user.');
                }
            } else {
                $this->app->view()->setResponse(false, '409', $valid['message']);
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }
}