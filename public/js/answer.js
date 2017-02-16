;(function () {
  'use strict';
  angular.module('answer', [])
    .service('AnswerService', [
      '$http',
      '$state',
      function ($http, $state) {
        var me = this;
        me.data = {};

        me.answer_form = {};
        me.delete_num = 0;
        me.answer_self = 0 ;
        me.comment_id = 0;
        // me.add_or_update = function () {
        //
        //   if(me.answer_form.id)
        //     $http.post('/api/answer/update', me.answer_form);
        //   else
        //     $http.post('api/answer/add', me.answer_form);
        // };
        me.add_or_update = function (question_id) {
          me.delete_num = 0;
          console.log('question_id', question_id);
          if (!question_id) {
            console.error('question_id is required');
            return;
          }

          me.answer_form.question_id = question_id;
          if (me.answer_form.id)
            $http.post('/api/answer/change', me.answer_form)
              .then(function (r) {
                if (r.data.status) {
                  me.answer_form = {};
                  $state.reload();
                  console.log('1');
                } else if (r.data.msg == "login required") {
                  $state.go('login')
                }
              });
          else
            $http.post('/api/answer/add', me.answer_form)
              .then(function (r) {
                if (r.data.status) {
                  me.answer_form = {};
                  $state.reload();
                  console.log('1');
                } else if (r.data.msg == 'login required') {
                  $state.go('login')
                }
              })
        }

        me.delete = function (answer_id) {
          me.delete_num = 0;
          if (!answer_id) {
            console.error('id is required')
            return;
          }

          $http.post('/api/answer/remove', {id: answer_id})
            .then(function (r) {
              if (r.data.status) {
                console.log('delete successfully!');
                console.log(me.delete_num);
                $state.reload();
                me.delete_num = 2;
              }
            })
        }

        /*统计票数
         * @answers array 用于统计票数的数据
         * 此数据可以是问题也可以是回答
         * 如果是问题将会跳过统计
         * */
        me.count_vote = function (answers) {
          /*迭代所有的数据*/
          for (var i = 0; i < answers.length; i++) {
            /*封装单个数据*/
            var votes, item = answers[i];
            /*如果不是回答也没有users元素说明本条不是回答或
             * 回答没有任何票数*/
            if (!item['question_id'])
              continue;
            me.data[item.id] = item;
            if (!item['users'])
              continue;
            // me.data[item.id] = item;
            // if (!item['users']) continue;
            /*每条回答的默认赞同票和反对票都为0*/

            item.upvote_count = 0;
            item.downvote_count = 0;
            /*users是所有投票用户的用户信息*/
            votes = item['users'];
            if (votes)
              for (var j = 0; j < votes.length; j++) {
                var v = votes[j]
                if (v['pivot'].vote === 1)
                  item.upvote_count++;
                if (v['pivot'].vote === 2)
                  item.downvote_count++;
              }
            console.log(answers[i]['content']);
          }
          console.log('票数数据get');
          return answers;
        }

        /*投票数据请求*/
        me.vote = function (conf) {
          if (!conf.id || !conf.vote) {
            console.log('id and vote are required');
            return;
          }
          var answer = me.data[conf.id],
            users = answer.users;
          me.answer_self = 0 ;

          if(answer.user_id == his.id) {

            console.log('you are voting yourself')
            me.answer_self = 1;
            return false;
          }

          /*判断当前用户是否已经投过相同的票*/
          for (var i = 0; i < users.length; i++) {
            if (users[i].id == his.id &&
              conf.vote == users[i].pivot.vote)
              conf.vote = 3;
          }

          return $http.post('api/answer/vote', conf)
            .then(function (r) {
              console.log('', r.data);
              if (r.data.status) {
                return true;
              }
              else if (r.data.msg == "login required") {
                console.log('你需要登陆')
                $state.go('login')
              }
              return false;
            }, function () {
              return false;
            })
        };

        //   me.vote = function (conf) {
        //     if (!conf.id || !conf.vote) {
        //       console.log('id and vote are required');
        //       return;
        //     }
        //
        //     var answer = me.data[conf.id],
        //       users = answer.users;
        //
        //     if (answer.user_id == his.id) {
        //       console.log('you are voting yourself!');
        //       return false;
        //     }
        //
        //     /*判断当前用户是否已经投过相同的票*/
        //     for (var i = 0; i < users.length; i++) {
        //       if (users[i].id == his.id &&
        //         conf.vote == users[i].pivot.vote)
        //         conf.vote = 3;
        //     }
        //
        //     return $http.post('api/answer/vote', conf)
        //       .then(function (r) {
        //         if (r.data.status)
        //           return true;
        //         else if (r.data.msg == 'login required')
        //           $state.go('login')
        //         else
        //           return false;
        //       }, function () {
        //         return false;
        //       })
        //   }


        /*更新数据*/
        me.update_data = function (id) {
          return $http.post('/api/answer/read', {id: id})
            .then(function (r) {
              me.data[id] = r.data.data;
            });

          //     if(angular.isNumberic(input))
          //         var id = input;
          //     if(angular.isArray(input))
          //         var id_set = input;
        }

        me.read = function (params) {
          return $http.post('/api/answer/read', params)
            .then(function (r) {
              if (r.data.status) {
                console.log('Answer.js/read')
                me.data = angular.merge({}, me.data, r.data.data)
                return r.data.data;
              }
              return false;
            })
        }

        me.add_comment = function () {
          return $http.post('/api/comment/add', me.new_comment)
            .then(function (r) {
                console.log('add_comment');
                if (r.data.status) {
                  return true;
                } else if(r.data.msg =="login required"){
                  $state.go('login')
                }
              }
            )
        };
        me.remove_comment = function () {
          return $http.post('/api/comment/remove', {id :me.comment_id})
            .then(function (r) {
              if(r.data.status) {
                console.log('remove successd!');
                $state.reload();
                me.delete_num=1;
              }else {
                console.log('network error')
              }
            })
        }

        //   me.add_or_update = function (question_id) {
        //     if (!question_id) {
        //       console.error('question_id is required');
        //       return;
        //     }
        //     me.answer_form.question_id = question_id;
        //     if (me.answer_form.id)
        //       $http.post('/api/answer/change', me.answer_form)
        //         .then(function (r) {
        //           if (r.data.status) {
        //             me.answer_form = {};
        //             $state.reload();
        //             console.log('1');
        //           }
        //         })
        //     else
        //       $http.post('/api/answer/add', me.answer_form)
        //         .then(function (r) {
        //           if (r.data.status) {
        //             me.answer_form = {};
        //             $state.reload();
        //             console.log('1');
        //           }
        //         })
        //   }
        //
        //   me.delete = function (id) {
        //     if (!id) {
        //       console.error('id is required');
        //       return;
        //     }
        //
        //     $http.post('/api/answer/remove', {id: id})
        //       .then(function (r) {
        //         if (r.data.status) {
        //           console.log('deleted successfully!');
        //           $state.reload();
        //         }
        //       })
        //   }
        //
        //
        //   me.update_data = function (id) {
        //     return $http.post('/api/answer/read', {id: id})
        //       .then(function (r) {
        //         console.log('r.data.data', r.data.data);
        //         me.data[id] = r.data.data;
        //       })
        //     // if(angular.isNumeric(input))
        //     //   var id = input;
        //     // if(angular.isArray(input))
        //     //   var id_set = input;
        //   }
        //
        //   me.read = function (params) {
        //     return $http.post('/api/answer/read', params)
        //       .then(function (r) {
        //         if (r.data.status) {
        //           me.data = angular.merge({}, me.data, r.data.data);
        //           return r.data.data;
        //         }
        //         return false;
        //       })
        //   }
        //
        //   me.add_comment = function () {
        //     return $http.post('/api/comment/add', me.new_comment)
        //       .then(function (r) {
        //         console.log('r', r);
        //         if (r.data.status)
        //           return true;
        //         return false;
        //       })
        //   }
      }])

    // .directive('commentBlock', [
    //   '$http',
    //   'AnswerService',
    //   function ($http, AnswerService) {
    //     var o = {};
    //     o.templateUrl = 'comment.tpl';
    //
    //     o.scope = {
    //       answer_id: '=answerId',
    //     }
    //
    //     o.link = function (sco, ele, attr) {
    //       sco.Answer = AnswerService;
    //       sco._ = {};
    //       sco.data = {};
    //       sco.helper = helper;
    //
    //       function get_comment_list() {
    //         return $http.post('/api/comment/read',
    //           {answer_id: sco.answer_id})
    //           .then(function (r) {
    //             if (r.data.status)
    //               sco.data = angular.merge({}, sco.data, r.data.data);
    //           })
    //       }
    //
    //       if (sco.answer_id)
    //         get_comment_list();
    //
    //       sco._.add_comment = function () {
    //         AnswerService.new_comment.answer_id = sco.answer_id;
    //         AnswerService.add_comment()
    //           .then(function (r) {
    //             if (r)
    //             {
    //               AnswerService.new_comment = {};
    //               get_comment_list();
    //             }
    //           })
    //       }
    //     }
    //     return o;
    //   }])
    //在angular中调用comment-block
    .directive('commentBlock', [
      '$http',
      'AnswerService',
      function ($http, AnswerService) {
        var o = {};
        o.templateUrl = 'comment.tpl';
        o.scope = {
          answer_id: '=answerId'
        }
        o.link = function (sco, ele, attr) {
          sco.Answer = AnswerService;
          sco._ = {};
          sco.data = {};
          sco.helper = helper;
          sco.comment_id=0;
          
          function get_comment_list() {
            return  $http.post('/api/comment/read',
              {answer_id: sco.answer_id})
              .then(function (r) {
                if(r.data.status) {
                  sco.data = angular.merge({}, sco.data, r.data.data);

                }
                console.log('sco.answer_id', sco.answer_id,r);
              });
          }

          // ele.on('click', function () {
            if (sco.answer_id)
              get_comment_list();

          // });
          sco.his=his;
          sco._.add_comment = function () {
            AnswerService.new_comment.answer_id  = sco.answer_id;
            AnswerService.add_comment()
              .then(function (r) {
                if(r) {
                  AnswerService.new_comment = {};
                  get_comment_list()
                }
              })
          };
          sco._.remove_comment = function (r) {
            AnswerService.comment_id = sco.comment_id =r;
            AnswerService.remove_comment()
              .then(function (r) {
                if (r) {
                }
              });
          }
        };
        return o;
      }
    ])
})();