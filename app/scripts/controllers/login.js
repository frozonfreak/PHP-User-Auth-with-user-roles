'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the angularApp
 */
angular.module('angularApp')
  .controller('LoginCtrl', function ($scope, $rootScope, md5, $cookieStore, $location, appSession) {

    $scope.userEmail ='admin@testapp.com';
    $scope.userPass = 'reset';

    $scope.alerts = [];

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };

    $scope.displayError = function(data, status){
      $scope.alerts = [];
      $scope.alerts.push({type:'warning',msg: data});
    };

    $scope.checkUserAuth = function(data, status){
      $scope.alerts = [];
      $scope.alerts.push({type:'success',msg: data});

      $cookieStore.put('TokenID',data["message"]);
      $cookieStore.put('UserEmail',$scope.userEmail);

      $location.path("/home");
    };


    $scope.frmLogin = function(){
    	if($scope.userEmail != undefined && $scope.userPass != undefined)
          appSession.verifyLogin($scope.userEmail, md5.createHash($scope.userPass)).success($scope.checkUserAuth).error($scope.displayError);
      else{
        $scope.alerts = [];
        $scope.alerts.push({type:'warning',msg: 'Update All fields'});
      }

    };
    
  });
