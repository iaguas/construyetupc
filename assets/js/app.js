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