'use strict';
app.factory('accountFactory', ['$http', function ($http) {
        var userEndpoint = "/api/user";


        var factory = {};

        factory.Login = function (params) {
            return $http.post(userEndpoint + '/login', {userName: params.userName, password: params.password});
        }

        factory.Signup = function (object) {
            return $http.post(userEndpoint+ '/signup', object);
        }


        return factory;
    }]);


