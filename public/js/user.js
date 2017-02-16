;(function () {
    'use strict';

    angular.module('user', [
      'answer',
      'question'
    ])
      .service('UserService', [
        '$state',
        '$http',
        function ($state, $http) {
          var me = this;
          me.signup_data = {};
          me.login_data = {};
          me.data = {};
          me.signup = function () {
            $http.post('/api/signup',
              me.signup_data)
              .then(function (r) {
                if (r.data.status) {
                  me.signup_data = {};
                  //跳转到login
                  $state.go('login')
                }
              }, function (e) {
                console.log('e', e)
              })
          }
          me.username_exist = function () {
            $http.post('/api/user/exists',
              {username: me.signup_data.username})
              .then(function (r) {
                if (r.data.status && r.data.data.count) {
                  me.signup_username_exists = true;
                } else {
                  me.signup_username_exists = false;
                }
              }, function (e) {
                console.log('e', e)
              })
          }
          me.login = function () {
            $http.post('/api/login',                    // $http.post('http://192.168.191.1/LaravelTest/public/api/login',
              me.login_data)
              .then(function (r) {
                //如果返回的数据中的status为1
                if (r.data.status) {
                  //刷新界面
                  // location.href='http://www.baidu.com';
                  location.reload();
                  location.href = '/?#/home/';
                  console.log(sessionStorage)
                } else {
                  me.login_failed = true
                }
              }, function () {

              })
          }

          me.read = function (param) {
            return $http.post('/api/user/read', param)
              .then(function (r) {
                if (r.data.status) {
                  me.current_user = r.data.data;
                  me.data[param.id] = r.data.data;
                } else {
                  if (r.data.msg == 'login required')
                    $state.go('login');
                }
              })
          }
        }
      ])

      .controller('SignupController', [
        '$scope',
        'UserService',
        function ($scope, UserService) {
          $scope.User = UserService;
          $scope.$watch(function () {
            return UserService.signup_data;
          }, function (n, o) {
            if (n.username != o.username)
              UserService.username_exist();
          }, true)
        }]
      )
      .controller('LoginController', [
          '$scope',
          'UserService',
          function ($scope, UserService) {
            $scope.User = UserService;
          }
        ]
      )
      .controller('UserController', [
        '$scope',
        '$stateParams',
        'UserService',
        'AnswerService',
        'QuestionService',
        function ($scope,
                  $stateParams,
                  UserService,
                  AnswerService,
                  QuestionService) {
          $scope.User = UserService;
          // console.log('$stateParams',$stateParams)
          UserService.read($stateParams);
          AnswerService.read({user_id: $stateParams.id})
            .then(function (r) {
              if (r)
                UserService.his_answers = r;
              console.log(UserService.his_answers)
            })
          QuestionService.read({user_id: $stateParams.id})
            .then(function (r) {
              if (r)
                UserService.his_questions = r;
            })
        }
      ])
  })();