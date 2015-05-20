/**
 * Fichero: app.js
 *
 * Contiene el módulo principal de AngularJS.
 */

// Variables globales para que no las pille JSLint
/*global angular, console, Sha256*/

// Módulo principal de AngularJS
var app = angular.module('app', ['ui.bootstrap', 'ngTable']);

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
                } else if (data === 'emailErr') {
                    $scope.requestResult = 'has-error-email';
                } else {
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
                    password: Sha256.hash($scope.password)
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

        $scope.logout = function () {
            $http({
                method  : 'POST',
                url     : '/models/adminLogout.php',
                data    : {
                    session: $scope.session
                },
                headers : {'Content-Type': 'application/json'}
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


// Controlador para la lógica de componentes
app.controller('ComponentCtrl', [
    '$scope',
    '$http',
    '$window',
    '$filter',
    'ngTableParams',
    function ($scope, $http, window, $filter, ngTableParams) {
        $scope.components = [];

        // TODO: ajustar elementos en función de la resolución.
        $scope.numPerPage = 8;
        var screenHeight = window.screen.availHeight;

        if(screenHeight <= 768) {
            $scope.numPerPage = 8;
        } else if(screenHeight > 768 && screenHeight <= 900) {
            $scope.numPerPage = 10;
        } else if(screenHeight > 1080) {
            $scope.numPerPage = 15;
        }

        // Obtenemos el componente especificado
        var request = $http({
            method: 'POST',
            url: '/models/getSpecificComponent.php',
            data: {
                component: 'cpus'
            },
            headers: {'Content-Type': 'application/json'}
        });

        request.success(function (data) {
            for(var i = 0; i < data.length; i++) {
                $scope.components.push({
                    'name': data[i][6],
                    'family': data[i][2],
                    'socket': data[i][4],
                    'cores': data[i][5],
                    'freq': data[i][1],
                    'price': parseFloat(data[i][3])
                });
            }

            $scope.tableParams = new ngTableParams({
                page: 1,
                count: 8,
                sorting: {
                    name: 'asc'
                }
            }, {
                total: $scope.components.length,

                getData: function($defer, params) {
                    var orderedData = params.filter() ?
                        $filter('filter')($scope.components, params.filter()) :
                        $scope.components;

                    orderedData = params.sorting() ?
                        $filter('orderBy')(orderedData, params.orderBy()) :
                        orderedData;

                    // Recalculamos páginas
                    params.total(orderedData.length);

                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                }
            });
        });
    }
]);
