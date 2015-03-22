'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the angularApp
 */
angular.module('angularApp')
  .controller('ResetPasswordCtrl', function ($stateParams, $scope, $rootScope, md5, $cookieStore, $location, appSession) {

    $scope.userPass;
    $scope.userPassVerify;

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

    $scope.frmResetPassword = function(){
      if($scope.userPass == $scope.userPassVerify && $scope.userPass !='')
    	   appSession.resetpassword($stateParams.email, $stateParams.token, md5.createHash($scope.userPass)).success($scope.displySuccess).error($scope.displayError);
      else{
        $scope.alerts = [];
        $scope.alerts.push({type:'warning',msg: "Enter password"});
      }
    };
    
    init();

    function init(){

    };

  });
