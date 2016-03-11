/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var app = angular.module("app", ['ngRoute']);
function ProfesorResource($http, $q, baseUrl) {
    this.get = function (dni) {
        var defered = $q.defer();
        var promise = defered.promise;

        $http({
            method: 'GET',
            url: 'http://localhost/cursoangularjs/Server/api/profesor.php/profesores/' + dni
        }).success(function (data, status, headers, config) {
            defered.resolve(data);
        }).error(function (data, status, headers, config) {
            defered.reject(status);
        });
        return promise;
    }

    this.list = function () {
        var defered = $q.defer();
        var promise = defered.promise;

        $http({
            method: 'GET',
            url: 'http://localhost/cursoangularjs/Server/api/profesor.php/profesores'
        }).success(function (data, status, headers, config) {
            defered.resolve(data);
        }).error(function (data, status, headers, config) {
            defered.reject(status);
        });
        return promise;
    }
}

function ProfesorResourceProvider() {
    var _baseUrl;
    this.setBaseUrl = function (baseUrl) {
        _baseUrl = baseUrl;
    };
    this.$get = ['$http', '$q', function ($http, $q) {
            return new ProfesorResource($http, $q, _baseUrl);
        }];
}

app.provider("profesorResource", ProfesorResourceProvider);
app.constant("baseUrl", ".");
app.config(['baseUrl', 'profesorResourceProvider', function (baseUrl, profesorResourceProvider) {
        profesorResourceProvider.setBaseUrl(baseUrl);
    }
]);

app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/', {
            templateUrl: "main.html",
            controller: "MainProfesorController"
        });

        $routeProvider.when('/gestion/listado', {
            templateUrl: "listado.html",
            controller: "ListadoProfesoresController",
            resolve: {
                profesores: ['profesorResource', function (profesorResource) {
                        return profesorResource.list();
                    }]
            }
        });

        $routeProvider.when('/gestion/detalle/:dni', {
            templateUrl: "detalle.html",
            controller: "DetalleProfesorController",
            resolve: {
                profesor: ['profesorResource', '$route', function (profesorResource, $route) {
                        return profesorResource.get($route.current.params.dni);
                    }]
            }
        });

        $routeProvider.otherwise({
            redirectTo: '/'
        });

    }]);

app.value("urlLogo", "logo_banco.jpg");
app.run(["$rootScope", "urlLogo", function ($rootScope, urlLogo) {
        $rootScope.urlLogo = urlLogo;
    }]);

app.controller("DetalleProfesorController", ["$scope", "profesor", "$q", function ($scope, profesor, $q) {

        $scope.profesor = profesor;

        $scope.guardarDatos = function () {
            alert("Se han guardado los datos");
        };
    }
]);

app.controller("ListadoProfesoresController", ['$scope', 'profesores', '$q', function ($scope, profesores, $q) {
        $scope.profesores = [];
        $scope.filtroNombreProfesor = "";
        $scope.profesores = profesores;

        /** Transforma el texto quitando todos los acentos diéresis, etc. **/
        function normalize(texto) {
            texto = texto.toLowerCase();
            texto = texto.replace(/[áàäâ]/g, "a");
            texto = texto.replace(/[éèëê]/g, "e");
            texto = texto.replace(/[íìïî]/g, "i");
            texto = texto.replace(/[óòôö]/g, "o");
            texto = texto.replace(/[úùüü]/g, "u");
            texto = texto.toUpperCase();
            return texto;
        }

        $scope.consultaProfesor = function (value) {
            //búsqueda insensible a mayúsculas o minúsculas.
            if ((normalize(value.nombre).includes(normalize($scope.filtroNombreProfesor))) || (normalize(value.apellido1).includes(normalize($scope.filtroNombreProfesor)))
                    || normalize(value.apellido2).includes(normalize($scope.filtroNombreProfesor))) {
                return true;
            } else {
                return false;
            }
        }
    }
]);

app.controller("MainProfesorController", ['$scope', function ($scope) {
    }
]);