<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */

namespace api\Controller;

include_once(BASEPATH . "/settings.php");
include_once '../lib/mygen_framework.php';
include_once '../lib/mygen_mysql.php';
include_once '../lib/DBConn.php';
include_once 'model/GameSession.class.php';
include_once 'model/User.class.php';

class GameSession extends ApiController {

    protected $basePath = '/gamesession';

    public function __construct($app) {
        parent::__construct();
    }

    public function index() {

        $this->app->post($this->basePath, array(
            $this, 'createGameSession'));

        $this->app->put($this->basePath, array(
            $this, 'updateGameSession'));

        $this->app->get($this->basePath . "/detail/:id", array(
            $this, 'getGameSessionDetail'));

        $this->app->get($this->basePath . "/list/:id", array(
            $this, 'getGameSessionListForUser'));
    }

    /*
     * Use the function below for updating an existing game session
     */

    public function updateGameSession() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $valid = \GameSession::validate($body, true);
            if ($valid['status'] === true) {
                $result = \GameSession::update($body);
                if ($result !== false) {
                    $this->app->view()->setResponse($result, '200', "OK");
                } else {
                    $this->app->view()->setResponse(false, '500', 'Internal error when generating game session.');
                }
            } else {
                $this->app->view()->setResponse(false, '409', $valid['message']);
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    /*
     * Use the function below function for creating a new game session 
     * for a given USER
     */

    public function createGameSession() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $valid = \GameSession::validate($body);
            if ($valid['status'] === true) {
                $result = \GameSession::create($body);
                if ($result !== false) {
                    $this->app->view()->setResponse($result, '201', "OK");
                } else {
                    $this->app->view()->setResponse(false, '500', 'Internal error when generating game session.');
                }
            } else {
                $this->app->view()->setResponse(false, '409', $valid['message']);
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    /*
     * Use the function below function for geting an existent 
     * game session by its ID
     */

    public function getGameSessionDetail($id) {

        try {
            $result = \GameSession::getGameSessionById($id);
            if ($result !== false) {
                $this->app->view()->setResponse($result, '200', "OK");
            } else {
                $this->app->view()->setResponse(false, '404', 'Game session not found for this user.');
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    /*
     * Use the function below function for geting complete game session list 
     * for a given User Id
     */

    public function getGameSessionListForUser($id) {
       
        try {
            $result = \GameSessionCollection::getList($id);
            if ($result !== false) {
                $this->app->view()->setResponse($result, '200', "OK");
            } else {
                $this->app->view()->setResponse(false, '404', 'There are no available game sessions for this user, or user does not exists.');
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

}
