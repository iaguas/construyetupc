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
    '$scope',
    '$http',
    '$window',
    function ($scope, $http) {
        'use strict';

        $scope.submitForm = function () {
            if (!$scope.email) {
                return;
            }

            var request = $http({
                method  : 'POST',
                url     : '/models/validateLanding.php',
                data    : {
                    email: $scope.email
                },
                headers : {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                if (data === 'regOk') {
                    $scope.requestResult = 'has-success';
                    $scope.email = '';
                }else if( data === 'emailErr') {
                    $scope.requestResult = 'has-error-email';
                }
                else {
                    $scope.requestResult = 'has-error';
                }
            });
        };
    }
]);

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
                if (data === 'loginOk') {
                    $scope.requestResult = 'has-success';
                    $window.location.href = 'http://' + $window.location.host + '/admin/panel';
                } else {
                    $scope.requestResult = 'has-error';
                }
            });

            request.error(function () {
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

                request.success(function (data) {
                    if (data === 'deleteOk') {
                        // Recargamos la página
                        $window.location.reload();
                    }
                });
            }
        };
    }
]);
