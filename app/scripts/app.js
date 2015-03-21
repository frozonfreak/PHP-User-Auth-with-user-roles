'use strict';

var angularApp = angular.module('angularApp',['ui.bootstrap','ui.router','ngAnimate', 'ngCookies', 'ngResource','ngSanitize','ngTouch','angular-md5']).run(function($rootScope, appSession, $cookieStore, $location){
      $rootScope.userAgent = navigator.userAgent;
      
      $rootScope.showData = true;
      // Needed for the loading screen
      $rootScope.$on('$locationChangeStart', function(event, next, current){
      	
      	//Remove header and footer for login page
      	if(next.indexOf("login") > -1 || next.indexOf("register") > -1 || next.indexOf("passwordreset") > -1)
      		$rootScope.showData = false;
      	else{
      		//If not login page or registration page or password reset, then check for valid session before route change
      		appSession.verifySessionAndAuth($cookieStore.get('TokenID'), $cookieStore.get('UserEmail'), next).success(function(data, status){
      			if(data["status"] != 0){
      				$location.path("/login");
      			}
      		}).error(function(){
      			$location.path("/login");
      		});
      		$rootScope.showData = true;
      	}

        $rootScope.loading = true;
      });

      $rootScope.$on('$locationChangeSuccess', function(){
        $rootScope.loading = false;
      });

      // Fake text i used here and there.
      $rootScope.lorem = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel explicabo, aliquid eaque soluta nihil eligendi adipisci error, illum corrupti nam fuga omnis quod quaerat mollitia expedita impedit dolores ipsam. Obcaecati.';

});

angularApp.config(function($stateProvider, $urlRouterProvider) {

  // Now set up the states
  $stateProvider
    .state('home', {
      url: "/home",
      templateUrl: "views/main.html",
      controller: 'MainCtrl',
    })
    .state('about', {
      url: "/about",
      templateUrl: "views/about.html",
      controller: 'AboutCtrl',
    })
    .state('login', {
      url: "/login",
      templateUrl: "views/login.html",
      controller: 'LoginCtrl',
    })
    // For any unmatched url, redirect to /state1
     $urlRouterProvider.otherwise("/home");

});

angularApp.factory('appSession', function($http){
    return {
        verifyLogin: function(email, password) {
          	return $http.post('/php_user_auth/server/updateTask.php',{
	            type      : 'verifyLogin',
	            email     : email,
	            password  : password
          	});
        },
        verifySessionAndAuth: function(token,email, url){
        	return $http.post('/php_user_auth/server/updateTask.php',{
	            type      : 'verifySessionAndAuth',
	            token     : token,
	            email  	  : email,
	            url 	  : url
          });
        }
    }
});

//Main Controller for body, handles loading and unloading along with preloader gif
angularApp.controller('MainController', function($rootScope, $scope){

  // User agent displayed in home page
  $scope.userAgent = navigator.userAgent;
  
  // Needed for the loading screen
  $rootScope.$on('$locationChangeStart', function(){
    $rootScope.loading = true;
  });

  $rootScope.$on('$locationChangeSuccess', function(){
    $rootScope.loading = false;
  });

  // Fake text i used here and there.
  $rootScope.lorem = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel explicabo, aliquid eaque soluta nihil eligendi adipisci error, illum corrupti nam fuga omnis quod quaerat mollitia expedita impedit dolores ipsam. Obcaecati.';

});

