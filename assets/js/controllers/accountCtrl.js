'use strict';
app.controller('loginCtrl', ["$scope", "accountFactory", "SweetAlert", '$state', 'toaster', function ($scope, accountFactory, SweetAlert, $state, toaster) {

        $scope.loginUser = function () {
            accountFactory.Login($scope.user).error(function (response) {
                SweetAlert.swal('Something went wrong...', response.message, "error");
            }).success(function (response) {

                //CREATE LOGIN COOCKIE
                createLoginCookie(response.data.token);
                var accessToken = getCurrentToken();
                createLoggedInUser(response.data);
                // REDIRECT
                $state.go('app.ui.game');
            });
        }


        $scope.registerUser = function () {

            // check passwords match
            if ($scope.newUser.password !== $scope.newUser.password2) {
                SweetAlert.swal('Something went wrong...', 'Password and Password confirmation do not match!', "error");
                return false;
            }

            accountFactory.Signup($scope.newUser).error(function (response) {
                SweetAlert.swal('Something went wrong...', response.message, "error");
            }).success(function (response) {

                //CREATE LOGIN COOCKIE
                createLoginCookie(response.data.token);
                var accessToken = getCurrentToken();
                createLoggedInUser(response.data);
                // REDIRECT 
                toaster.pop('success', 'New User', 'You have successfully created your account.');
                $state.go('login.signin');
            });
        }
    }]);