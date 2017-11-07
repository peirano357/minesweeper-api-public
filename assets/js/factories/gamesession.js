'use strict';
app.factory('gameSessionFactory', ['$http', function ($http) {
        var gameSessionEndpoint = "/api/gamesession";

        var factory = {};

     
        factory.getGameSessionListForUser = function (id) {
            return $http.get(gameSessionEndpoint + '/list/' + id, {
                headers: {'Authorization': id}
            });
        }

        factory.getGameSessionDetail = function (id,token) {
            return $http.get(gameSessionEndpoint + '/detail/' + id, {
                headers: {'Authorization': token}
            });
        }
        
        factory.CreateGameSession = function (token, object) {
            return $http.post(gameSessionEndpoint, object, {
                headers: {'Authorization': token}
            });
        }

        factory.UpdateGameSession = function (token, object) {
            return $http.put(gameSessionEndpoint, object, {
                headers: {'Authorization': token}
            });
        }

        return factory;
    }]);


