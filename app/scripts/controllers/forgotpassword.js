'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the angularApp
 */
angular.module('angularApp')
  .controller('ForgotPasswordCtrl', function ($scope, $rootScope, md5, $cookieStore, $location, appSession) {
    
    $scope.userEmail = 'admin@testapp.com';

    $scope.alerts = [];

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };

    $scope.displayError = function(data, status){
      $scope.alerts = [];
      $scope.alerts.push({type:'warning',msg: data});
    };

    $scope.displySuccess = function(data, status){
      $scope.alerts = [];
      $scope.alerts.push({type:'success',msg: data});
    };

    $scope.frmForgotPassword = function(){
    	appSession.forgotpassword($scope.userEmail).success($scope.displySuccess).error($scope.displayError);
    };
    

  });
