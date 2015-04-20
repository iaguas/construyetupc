// You should only create your module once. According to the docs, if you create a module with a name that already exists, it will overwrite the previous one. (So last-in-wins.)

// angular.module('app', []);

// You can retrieve your module as many times as you like and in separate files if you wish. You will typically retrieve your module multiple times to declare services, controllers, directives, etc.

// angular.module('app').service('myService', ...);
// angular.module('app').controller('myController', ...);
// angular.module('app').directive('myDirective', ...);


// Se construye la directiva ng-notify para angular
var app = angular.module('app', []);
// create angular controller and pass in $scope and $http
  app.controller('formController',
    function($scope, $http) {
      // creating a blank object to hold our form information.
      //$scope will allow this to pass between controller and view
      $scope.formData = {};
      // submission message doesn't show when page loads
      $scope.submission = false;
      // Updated code thanks to Yotam
      var param = function(data) {
            var returnString = '';
            for (d in data){
                if (data.hasOwnProperty(d))
                   returnString += d + '=' + data[d] + '&';
            }
            // Remove last ampersand and return
            return returnString.slice( 0, returnString.length - 1 );
      };
      $scope.submitForm = function() {
        $http({
        method : 'POST',
        url : 'validationLanding.php',
        data : param($scope.formData), // pass in data as strings
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' } // set the headers so angular passing info as form data (not request payload)
      })
        .success(function(data) {
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
});
/*
app.controller('ngNotifyController', ['$scope', 'ngNotify', 
    function($scope, ngNotify) {
        'use strict';

        // Custom additons...

        /*

        ngNotify.addTheme('newtheme', 'my-new-class');

        ngNotify.config({
            theme: 'newtheme'
        });

        */

        // ---

        /*

        ngNotify.addType('notice', 'my-notice-type');

        ngNotify.set('This is my notice type!', 'notice');

       
        // Demo notifications...

        $scope.displayNotify = function(notify) {
            switch(notify) {
                case 'success':
                    ngNotify.set('You have successfully logged in!', {
                        type: 'success'
                    });
                    break;
                case 'info':
                    ngNotify.set('You have a new message in your inbox.', 'info');
                    break;
                case 'warn': 
                    ngNotify.set('Please login before accessing that part of the site.', 'warn');
                    break;
                case 'error':
                    ngNotify.set('The action you are trying to take does not exist.', 'error');
                    break;
                case 'grimace':
                    ngNotify.set('An additional notification type to use.', 'grimace');
                    break;
                default:
                    ngNotify.set('This is the current default message type.');
                    break;
            }
        };

        // Configuration options...

        $scope.theme = 'pure';
        $scope.themeOptions = ['pure', 'pastel', 'prime', 'pitchy'];

        $scope.duration = 4000;
        $scope.durationOptions = [
            { id: 500, value: '500 ms' }, 
            { id: 1000, value: '1000 ms' }, 
            { id: 2000, value: '2000 ms' }, 
            { id: 4000, value: '4000 ms' }, 
            { id: 8000 , value: '8000 ms'}
        ];

        $scope.position = 'bottom';
        $scope.positionOptions = ['bottom', 'top'];

        $scope.defaultType = 'info';
        $scope.defaultOptions = ['info', 'success', 'warn', 'error', 'grimace'];

        $scope.sticky = false;
        $scope.stickyOptions = [true, false];

        // Configuration actions...

        $scope.setDefaultType = function() {
            ngNotify.config({
                type: $scope.defaultType
            });
        };

        $scope.setDefaultPosition = function() {
            ngNotify.config({
                position: $scope.position
            });
        };

        $scope.setDefaultDuration = function() {
            ngNotify.config({
                duration: $scope.duration
            });
        };

        $scope.setDefaultTheme = function() {
            ngNotify.config({
                theme: $scope.theme
            });
        };

        $scope.setDefaultSticky = function() {
            ngNotify.config({
                sticky: $scope.sticky
            });
        };

        $scope.dismissNotify = function() {
            ngNotify.dismiss();
        };
    }
]);*/

