;(function () {
  'use strict';
  angular.module('question', [])

    .service('QuestionService', [
      '$state',
      '$http',
      'AnswerService',
      function ($state, $http, AnswerService) {
        var me = this;

        me.new_question = {};
        me.data = {};

        me.go_add_question = function () {
          console.log('添加问题');
          $state.go('question.add')
        };
        me.add = function () {
          if (!me.new_question.title)
            return;
          $http.post('/api/question/add', me.new_question)                    // $http.post('http://192.168.191.1/LaravelTest/pu·lic/api/question/add',me.new_question)
            .then(function (r) {
              if (r.data.status) {
                me.new_question = {}
                $state.go('home')
              } else {
                $state.go('login')
              }
            }, function (e) {
              console.log('e', e)
            })
        };

        me.read = function (params) {
          return $http.post('/api/question/read', params)
            .then(function (r) {
                if (r.data.status) {
                  if (params.id) {
                    console.log('question_read_params', params);
                    me.data[params.id] = me.current_question = r.data.data;
                    me.its_answers = me.current_question.answers_with_user_info;
                    me.its_answers = AnswerService.count_vote(me.its_answers);
                    console.log('its_answers', me.its_answers);
                  }
                  else {
                    me.data = angular.merge({}, me.data, r.data.data)
                  }
                  return r.data.data
                }
                return false;
              }
            )
        }

        me.vote = function (conf) {
          var $r = AnswerService.vote(conf);
          if ($r)
            $r.then(function (r) {
              if (r) {
                me.update_answer(conf.id);
                console.log('update_answer_completed');
              }
            })
        }
        me.update_answer = function (answer_id) {
          $http.post('/api/answer/read', {id: answer_id})
            .then(function (r) {
              if (r.data.status) {
                for (var i = 0; i < me.its_answers.length; i++) {
                  var answer = me.its_answers[i];
                  if (answer.id == answer_id) {
                    console.log('r.data.data', r.data.data);
                    me.its_answers[i] = r.data.data;
                    AnswerService.data[answer_id] = r.data.data;
                  }
                }
              }
            })
        }
        me.logout = function () {
          $http.post('/api/logout')
            .then(function (r) {
                if (r.data.status) {
                  console.log('logout')
                  $state.go('login')
                  location.reload();
                  location.href = '/?#/home/';
                  console.log('logout')
                }
              }
            )
        }

        me.update = function () {
          if (!me.current_question.title) {
            return false;
          }

          return $http.post('/api/question/change', me.current_question)
            .then(function (r) {
              if (r.data.status)
                me.show_update_form = false;
            })
        };

        me.remove = function (question_id) {
          if(!question_id) {
            console.log('id is required');
            return;
          }
          console.log('me.current_question.id',me.current_question.id)
          return $http.post('/api/question/remove',{id:me.current_question.id})
            .then(function (r) {
              if (r.data.status) {
                console.log('question deleted')
              }else{
                console.log('network error')
              }
            })
        }
      }
    ])


    .controller('QuestionController', [
      '$scope',
      'QuestionService',
      function ($scope, QuestionService) {
        $scope.Question = QuestionService;

      }]
    )

    .controller('QuestionAddController', [
      '$state',
      '$scope',
      'QuestionService',
      function ($state, $scope, QuestionService) {
        $scope.Question = QuestionService;
        //跳转login
        if (!his.id)
          $state.go('login');
      }]
    )

    .controller('QuestionDetailController', [
      '$scope',
      'QuestionService',
      '$stateParams',
      'AnswerService',
      function ($scope, QuestionService, $stateParams, AnswerService) {
        QuestionService.read($stateParams);
        $scope.Answer = AnswerService;
        //判断是否有answer_id
        if ($stateParams.answer_id) {
          QuestionService.current_answer_id = $stateParams.answer_id;
          console.log('stateParams', QuestionService.current_answer_id)
        }
        else
          QuestionService.current_answer_id = null;
      }
    ])

})();