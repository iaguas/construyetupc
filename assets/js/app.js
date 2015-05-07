/**
 * Fichero: app.js
 *
 * Contiene el módulo principal de AngularJS.
 */

// Variables globales para que no las pille JSLint
/*global angular, console, Sha256*/

// Módulo principal de AngularJS
var app = angular.module('app', []);
var todos = angular.module('todos', ['ui.bootstrap']);

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


//Mostramos datos + paginacion CPUS
todos.controller('TodoController',function ($scope, $http) {

        $scope.filteredTodos = [];
        $scope.currentPage = 1;
        $scope.todos=[];
        $scope.numPerPage = 8;

        $scope.getCpus = function () {

            var request = $http({
                method  : 'POST',
                url     : '/models/getSpecificComponent.php',
                data    : {
                    component: 'cpus'
                },
                headers : {'Content-Type': 'application/json'}
            });

            request.success(function (data) {

                $scope.todos=data;
                $scope.filteredTodos = data.slice(0, $scope.numPerPage);
                //$scope.todos.push(data);

                console.log($scope.todos);
                //return $scope.todos;
            });
            //console.log(p1);
            console.log($scope.todos);

        };
        $scope.getCpus();

        $scope.numPages = function () {
            return Math.ceil($scope.todos.length / $scope.numPerPage);
        };

        $scope.$watch('currentPage + numPerPage', function () {
            var begin = (($scope.currentPage-1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;
            $scope.filteredTodos = $scope.todos.slice(begin, end);
        });

        $scope.updateDataSearch = function (component) {

            var request = $http({
                method  : 'POST',
                url     : '/models/getSearchResult.php',
                data    : {
                    component: component,
                    text: $scope.search.text
                },
                headers : {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                $scope.todos=data;
                $scope.filteredTodos = data.slice(0, $scope.numPerPage);
            });
        };

    });

