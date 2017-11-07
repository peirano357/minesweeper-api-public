<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
if (!function_exists('apache_request_headers')) {

    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val)
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }

}

class HttpBasicAuth extends \Slim\Middleware {

    protected $realm;
    protected $whiteList;
    protected $adminProtected;

    public function __construct() {

        $this->whiteList = array("\/user\/login",
            "\/user\/signup",
              );

        $this->adminRoutes = array("^\/admin\/adminelementlist$", "^\/blah\/([0-9]+)$");
    }

    /**
     * Deny Access
     *
     */
    public function deny_access() {
        $res = $this->app->response();
        $res->header('Access-Control-Allow-Origin', "*");
        $res->header('Access-Control-Allow-Headers', "Authorization");
        $res->header('Access-Control-Allow-Methods', "GET, POST, PUT, DELETE");
        $res->status(401);
    }

    public function authenticate($token) {

        return User::validateToken($token);
    }

    protected function printOptions() {
        $this->app->view()->setResponse(array(), 200, 'Ok');
        $this->app->view->render();
    }

    protected function isAdminPath() {
        $patterns_flattened = implode('|', $this->adminRoutes);
        $matches = '';
        preg_match('/' . $patterns_flattened . '/', $this->app->request->getPathInfo(), $matches);
        return (count($matches) != 0);
    }

    protected function validateAdminPath() {
        if ($this->isAdminPath()) {
            return true;
        }
        return true;
    }

    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call() {
        if ($this->app->request->isOptions()) {
            $this->printOptions();
            return;
        }
        $patterns_flattened = implode('|', $this->whiteList);
        $matches = '';
        preg_match('/' . $patterns_flattened . '/', $this->app->request->getPathInfo(), $matches);

        //if (!in_array($this->app->request->getPathInfo(), $this->whiteList)) {
        if (count($matches) == 0) {
            $req = apache_request_headers();
            if (isset($req['Authorization'])) {
                $tokenAuth = $req['Authorization'];
                if ($this->authenticate($tokenAuth)) {
                    //if you want to update TOKEN life or set user in session, 
                    //this is where you should do it
                    $this->app->auth_user = $usrObj;
                    if ($this->validateAdminPath())
                        $this->next->call();
                    else
                        $this->deny_access();
                } else {
                    $this->deny_access();
                }
            } else {
                $this->deny_access();
            }
        } else {
            $this->next->call();
        }
    }
}