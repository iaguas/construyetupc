/**
 * Fichero: app.js
 *
 * Contiene el módulo principal de AngularJS.
 */

// Variables globales para que no las pille JSLint
/*global angular, console, Sha256, $*/

// Módulo principal de AngularJS
var app = angular.module('app', []);
var appTable = angular.module('appTable', ['ngTable']);

// Controlador para el formulario de la Landing Page
app.controller('formController', [
    '$scope',
    '$http',
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
appTable.controller('ComponentCtrl', [
    '$scope',
    '$http',
    '$window',
    '$filter',
    'ngTableParams',
    function ($scope, $http, window, $filter, ngTableParams) {
        'use strict';

        $scope.components = [];
        $scope.providers = [];
        $scope.numPerPage = 8;
        var screenHeight = window.screen.availHeight;

        if (screenHeight <= 768) {
            $scope.numPerPage = 8;
        } else if (screenHeight > 768 && screenHeight <= 900) {
            $scope.numPerPage = 10;
        } else if (screenHeight > 1080) {
            $scope.numPerPage = 15;
        }

        // Ocultamos la tabla de productos
        $('#tableproducts').hide();

        $scope.getComponents = function (component) {
            // Obtenemos el componente especificado
            var request = $http({
                method: 'POST',
                url: '/models/getSpecificComponent.php',
                data: {
                    component: component
                },
                headers: {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                var i;

                // CPU
                if (component === 'cpus') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][6],
                            'family': data[i][2],
                            'socket': data[i][4],
                            'cores': data[i][5],
                            'freq': data[i][1],
                            'price': parseFloat((data[i][3])).toFixed(2)
                        });
                    }
                }

                // GPU
                if (component === 'gpus') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][4],
                            'frequency': data[i][1],
                            'memory': data[i][2],
                            'price': parseFloat((data[i][3])).toFixed(2)
                        });
                    }
                }

                // CPU Cooler
                if (component === 'cpucoolers') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][5],
                            'rpm': data[i][2],
                            'noise': data[i][3],
                            'size': data[i][1],
                            'price': parseFloat((data[i][4])).toFixed(2)
                        });
                    }
                }

                // Motherboard
                if (component === 'motherboards') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][2],
                            'socket': data[i][3],
                            'price': parseFloat((data[i][1])).toFixed(2)
                        });
                    }
                }

                // RAM
                if (component === 'memories') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][5],
                            'frequency': data[i][1],
                            'modules': data[i][2],
                            'size': data[i][3],
                            'price': parseFloat(data[i][4]).toFixed(2)
                        });
                    }
                }

                // Power Supply
                if (component === 'powersupplies') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][4],
                            'watts': data[i][1],
                            'eficiency': data[i][2],
                            'price': parseFloat((data[i][3])).toFixed(2)
                        });
                    }
                }

                // Case
                if (component === 'cases') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][3],
                            'format': data[i][1],
                            'price': data[i][2]
                        });
                    }
                }

                // Optical Drive
                if (component === 'opticaldrives') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id':  data[i][0],
                            'name':  data[i][2],
                            'price':  data[i][1]
                        });
                    }
                }

                // Storage
                if (component === 'storages') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][5],
                            'capacity': data[i][1],
                            'format': data[i][2],
                            'type': data[i][3],
                            'price': data[i][4]
                        });
                    }
                }

                // Monitor
                if (component === 'monitors') {
                    for (i = 0; i < data.length; i++) {
                        $scope.components.push({
                            'id': data[i][0],
                            'name': data[i][4],
                            'resolution': data[i][1],
                            'size': data[i][2],
                            'price': data[i][3]
                        });
                    }
                }

                // Ocultamos rueda de carga y mostramos productos
                $('#loadspin').hide();
                $('#tableproducts').show();

                $scope.tableParams = new ngTableParams({
                    page: 1,
                    count: 8,
                    sorting: {
                        name: 'asc'
                    }
                }, {
                    total: $scope.components.length,

                    getData: function ($defer, params) {
                        var orderedData = params.filter() ?
                                $filter('filter')($scope.components, params.filter()) :
                                $scope.components;

                        orderedData = params.sorting() ?
                                $filter('orderBy')(orderedData, params.orderBy()) :
                                orderedData;

                        // Recalculamos páginas
                        params.total(orderedData.length);
                        $('#loadspin').hide();

                        $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                    }
                });
            });
        };

        $scope.getProviders = function (compPn,compType) {
            // El componente es indiferente para los campos de los proveedores a mostrar, por lo que data en un principio es vacío
            var request = $http({
                method: 'POST',
                url: '/models/getComponentProviders.php',
                data: {
                    compPn: compPn,
                    compType: compType
                },
                headers: {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                var i;
                // data['provider'], data['price']...
                // El total se calcula en la funcion que devuelve los valores, getComponentProviders.php
                for (i = 0; i < data.length; i++) {
                    $scope.providers.push({
                        'name': data[i]['name'],
                        'price': data[i]['price'],
                        'delivery-fare': data[i]['delivery-fare'],
                        'total': data[i]['total'],
                        'pos': i
                    });
                }

                // Ocultamos rueda de carga y mostramos proveedores
                $('#loadspin').hide();
                $('#tableproducts').show();

                $scope.tableParams = new ngTableParams({
                    page: 1,
                    total: 3,
                    count: 8,
                    sorting: {
                        total: 'asc'
                    }
                }, {
                    counts: [],

                    getData: function ($defer, params) {
                        var orderedData = params.filter() ?
                            $filter('filter')($scope.providers, params.filter()) :
                            $scope.providers;

                        orderedData = params.sorting() ?
                            $filter('orderBy')(orderedData, params.orderBy()) :
                            orderedData;

                        $('#loadspin').hide();

                        $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                    }
                });
            });
        };

        //Función para añadir un componente a la cesta
        $scope.addComponent = function ($productId,$component) {


            $scope.component1 = $component.substring(0,$component.length-1);

            if ($scope.component1 === 'memorie'){
                $scope.component1 = 'memory';
            }
            if ($scope.component1 === 'opticaldrive'){
                $scope.component1 = 'optical-drive';
            }
            if ($scope.component1 === 'powersupplie'){
                $scope.component1 = 'power-supply';
            }

           var request = $http({
                method  : 'POST',
                url: '/partList/select/' + $scope.component1,
                data: {
                    'productId': $productId,
                    'productVendorName': $('#product-vendor').val(),
                    'productPrice': $('#product-price').val()
                },
               headers : {'Content-Type': 'application/json'}
            });

            request.success(function () {
                window.location.href = "/partList";
            });
        };
    }
]);

// Controlador para la inserción de los datos
appTable.controller('insertionController', [
    '$scope',
    '$http',
    '$filter',
    '$window',
    'ngTableParams',
    function ($scope, $http, $filter,$window, ngTableParams) {
        'use strict';
        $scope.nameJson=[];
        $scope.selection=[];
        $('#loadSpin').hide();
        $('#tableJson').hide();
        $('#insertionData').hide();

        $scope.getNameJson = function () {
            $('#startInsertion').hide();


            var request = $http({
                method: 'POST',
                url: '/models/getDocJson.php',
                data: {
                    url2: '../crawler/data/originals'
                },
                headers: {'Content-Type': 'application/json'}
            });

            request.success(function (data) {
                var i;
                $('#loadSpin').show();
                for (i = 0; i < data.length; i++) {
                    $scope.nameJson.push({
                        'name': data[i]['name']
                    });
                }

                $scope.tableParams = new ngTableParams({
                    page: 1,
                    count: 9,
                    sorting: {
                        name: 'asc'
                    }
                }, {
                    total: $scope.nameJson.length,

                    getData: function ($defer, params) {
                        var orderedData = params.filter() ?
                            $filter('filter')($scope.nameJson, params.filter()) :
                            $scope.nameJson;

                        orderedData = params.sorting() ?
                            $filter('orderBy')(orderedData, params.orderBy()) :
                            orderedData;

                        // Recalculamos páginas
                        params.total(orderedData.length);

                        $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                        $('#loadSpin').hide();
                        $('#tableJson').show();
                    }
                });
            });

            $('#insertionData').show();
        };


        $scope.addJson = function(docJson){
            var i,cont;
            if($scope.selection.length === 0){
                $scope.selection.push(docJson);
                $scope.return="";
            }else{
                $scope.selec=$scope.selection;
                $scope.selection=[];
                for( i = 0 ; i < $scope.selec.length; i++){
                    if($scope.selec[i] !== docJson){
                        $scope.selection.push($scope.selec[i]);
                        cont=0;
                    }else{
                        cont=1;
                    }
                }
                if(cont===0){
                    $scope.selection.push(docJson);
                }
            }
        };

        $scope.insertJson = function () {
            if($scope.selection.length === 0) {
                $scope.return="Por favor, selecciona un documento";
            }else{
                window.alert('¿Desea continuar?');
                var request = $http({
                    method: 'POST',
                    url: '/db/insertComponents.php',
                    data: {
                        docs: $scope.selection
                    },
                    headers: {'Content-Type': 'application/json'}
                });
                request.success(function (data) {
                    if(data==='ok'){
                        $scope.return="Datos insertados correctamente.";
                        $scope.selection=[];
                        $('input:checkbox').removeAttr('checked');
                    }else{
                        $scope.return="No se ha han insertado los datos correctamente.";
                    }
                });

            }
        };

    }
]);

// Controlador mostrar el coste de la configuración total
app.controller('totalCostController', [
    '$scope',
    function ($scope) {
        'use strict';
        $('#totalCostid').hide();
        $scope.totalCostFin='';
        $scope.totalCostString='';

        $scope.calculateCost = function (totalCost) {
            $scope.totalCostString=totalCost;
            $scope.totalCostFin = $scope.totalCostString.split('€');
            $scope.totalCostFin = parseFloat($scope.totalCostFin[0]).toFixed(2);

            if($scope.totalCostFin !== 0) {
                $('#totalCostid').show();
            }
        };
    }
]);
