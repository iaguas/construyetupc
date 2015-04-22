/**
 * Fichero: app.js
 *
 * Contiene el módulo principal de AngularJS.
 */

// Evitamos que JSLint diga que usamos "angular" sin definirlo
/*global angular*/

// Módulo principal de AngularJS
var app = angular.module('app', []);

// Controlador para el formulario de la Landing Page
app.controller('formController', [
    function ($scope, $http) {
        'use strict';
        // creating a blank object to hold our form information.
        //$scope will allow this to pass between controller and view
        $scope.formData = {};
        // submission message doesn't show when page loads
        $scope.submission = false;
        // Updated code thanks to Yotam
        var param = function (data) {
            var returnString = '';
            for (d in data) {
                if (data.hasOwnProperty(d))
                    returnString += d + '=' + data[d] + '&';
            }
            // Remove last ampersand and return
            return returnString.slice(0, returnString.length - 1);
        };
        $scope.submitForm = function () {
            $http({
                method: 'POST',
                url: 'validationLanding.php',
                data: param($scope.formData), // pass in data as strings
                headers: {'Content-Type': 'application/x-www-form-urlencoded'} // set the headers so angular passing info as form data (not request payload)
            })
                .success(function (data) {
                    console.log(data);
                    if (!data.success) {
                        // if not successful, bind errors to error variables
                        // $scope.errorName = data.errors.name;
                        $scope.errorEmail = data.errors.email;
                        // $scope.errorTextarea = data.errors.message;
                        $scope.submissionMessage = data.messageError;
                        $scope.submission = true; //shows the error message
                    } else {
                        // if successful, bind success message to message
                        $scope.submissionMessage = data.messageSuccess;
                        $scope.formData = {}; // form fields are emptied with this line
                        $scope.submission = true; //shows the success message
                    }
                });
        };
    }]);

// Controlador para el formulario del panel de administración
app.controller('AdmLoginFormCtrl', [
    '$scope',
    '$http',
    '$window',
    function ($scope, $http, $window) {
        'use strict';

        $scope.submitForm = function () {
            if (!$scope.username || !$scope.password) {
                return;
            }

            var request = $http({
                method  : 'POST',
                url     : '/models/validateAdminLogin.php',
                data    : {
                    username: $scope.username,
                    password: $scope.password
                },
                headers : {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                if (data == 'loginOk') {
                    $scope.requestResult = 'has-success';
                    $window.location.href = 'http://' + $window.location.host + '/admin/panel';
                }
                else {
                    $scope.requestResult = 'has-error';
                }
            });

            request.error(function (data) {
                $scope.requestResult = 'has-error';
            });
        };
    }
]);

// Controlador para el borrado de un email
app.controller('AdmEmailCtrl', [
    '$scope',
    '$http',
    '$window',
    function ($scope, $http, $window) {
        'use strict';

        $scope.deleteEmail = function ($emailid) {
            if ($window.confirm('¿Seguro que quieres eliminar el email?')) {
                var request = $http({
                    method  : 'POST',
                    url     : '/models/removeEmail.php',
                    data    : {
                        emailid: $emailid
                    },
                    headers : {'Content-Type': 'application/json'}
                });
            }

            request.success(function (data) {
                if (data == 'deleteOk') {
                    // Recargamos la página
                    $window.location.reload();
                }
                else {
                    // error
                }
            });

            request.error(function (data) {
                // error
            });
        };
    }
]);
