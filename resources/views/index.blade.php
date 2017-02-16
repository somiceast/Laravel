<!doctype html>
<html ng-controller="BaseController" lang="zh" ng-app="usay" user_id="{{session('user_id')}}">
<head>
  <meta charset="UTF-8">
  <title>
    Usay
  </title>
  <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
  <link rel="stylesheet" href="/css/base.css">
  <script src="/node_modules/jquery/dist/jquery.js"></script>
  <script src="/node_modules/angular/angular.js"></script>
  <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
  <script src="/js/base.js"></script>
  <script src="/js/user.js"></script>
  <script src="/js/common.js"></script>
  <script src="/js/question.js"></script>
  <script src="/js/answer.js"></script>
</head>
<body>
<div class="navbar clearfix">
  <div class="container">
    <div class="fl">
      <div ui-sref="home" class="navbar-item brand kkk">Usay</div>
      <form id="quick_ask"
            class="kkk"
            ng-controller="QuestionController"
            ng-submit="Question.go_add_question()"
      >
        <div>
          <div class="navbar-item clearfix">
            <input type="text"
                   ng-model="Question.new_question.title">
          </div>
          <button
              type="submit"
              class="primary"
          >提问
          </button>

        </div>
      </form>
    </div>
    <div class="fr">
      <a ui-sref="home" class="navbar-item">首页</a>
      @if(is_logged_in())
        <a ui-sref="login" class="navbar-item">{{session('username')}}</a>
        <a ng-controller="QuestionController" ng-click="Question.logout()"
           {{--href="{{url('/api/logout')}}" --}}
           class="navbar-item">登出</a>
      @else
        <a ui-sref="login" class="navbar-item">登录</a>
        <a ui-sref="signup" class="navbar-item">注册</a>
      @endif
    </div>
    <div class="fr"></div>
  </div>
</div>

<div class="page">
  <div ui-view></div>
</div>
<div class="version kkk clearfix">
  v1.0.0 Usay
  <label>{{session('username')}}</label>
</div>
<script type="text/ng-template" id="comment.tpl">
  <div class="comment-block">
    {{--点击才能显示--}}
    <div class="hr"></div>
    <div class="comment-item-set">
      <div class="rect"></div>
      <div ng-if="helper.obj_length(data)"
           ng-repeat="item in data"
           class="comment-item clearfix">
        <div class="user">[: item.user.username :] :</div>
        <div class="comment-content">
          [: item.content :]
          <div class="gray">
          <span ng-if="item.user.id == his.id">
            <a ng-click="_.remove_comment(item.id)" class="cursor">
              删除
            </a>
          </span>
            <a>
              [: item.updated_at :]
            </a>
          </div>
        </div>
      </div>
      <div class="gray tac well" ng-if="!helper.obj_length(data)">
        该回答没有评论
      </div>
      <div class="input-group">
        <form class="comment_form" ng-submit="_.add_comment(item.id)">
          <input type="text"
                 ng-model="Answer.new_comment.content"
                 placeholder="说些什么...">
          <button class="primary" type="submit">评论</button>

        </form>
      </div>
    </div>
  </div>

</script>
</body>

</html>