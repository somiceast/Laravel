<!doctype html>
<html lang="zh" ng-app="usay">
<head>
    <meta charset="UTF-8">
    <title>
        Usay
    </title>
    <link rel="stylesheet" href="/LaravelTest/public/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/LaravelTest/public/css/base.css">
    <script src="/LaravelTest/public/node_modules/jquery/dist/jquery.js"></script>
    <script src="/LaravelTest/public/node_modules/angular/angular.js"></script>
    <script src="/LaravelTest/public/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/LaravelTest/public/js/base.js"></script>
</head>
<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">Usay</div>
            <form id="quick_ask" ng-submit="Question.go_add_quesion()" ng-controller="QuestionAddController">
                <div class="navbar-item">
                    <input type="text" ng-model="Question.new_question.title">
                </div>
                <div class="navbar-item">
                    <button class="primary" type="submit">提问</button>
                </div>
            </form>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">首页</a>
        <?php if(is_logged_in()): ?>
                <a ui-sref="login" class="navbar-item"><?php echo e(session('username')); ?></a>
                <a href="<?php echo e(url('/api/logout')); ?>" class="navbar-item">登出</a>
            <?php else: ?>
                <a ui-sref="login" class="navbar-item">登录</a>
                <a ui-sref="signup" class="navbar-item">注册</a>
            <?php endif; ?>
        </div>
        <div class="fr"></div>
    </div>
</div>

<div class="page">
    <div ui-view></div>
</div>
<div class="version">
v1.0.0 Usay
<label><?php echo e(session('username')); ?></label>
</div>
</body>

<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        <h1>首页</h1>
        简　介:《小鸡炖蘑菇》是由糗事百科出品的一档周播综艺脱口秀节目，除了有无节操女主播的犀利吐槽，还有好看的小剧场和路人恶搞等环节，每周三我们不见不散！----------------------
    </div>
</script>
<script type="text/ng-template" id="login.tpl">
    <div ng-controller='LoginController'
         class="login container">
        <div class="card">
            <h1>登录</h1>
            <form name="login_form"
                  ng-submit="User.login()">
                <div class="input-group">
                    <label>用户名</label>
                    <input type="text"
                           name="username"
                           ng-model="User.login_data.username"
                           required
                    >
                </div>
                <div class="input-group">
                    <label>密码</label>
                    <input type="password"
                           name="password"
                           ng-model="User.login_data.password"
                           required
                    >
                </div>
                <div ng-if="User.login_failed" class="input-error-set">
                    用户名或密码有误
                </div>
                <div class="input-group">
                    <button ng-disabled="login_form.username.$error.required || login_form.password.$error.required"
                            class="primary"
                            type="submit"
                    >登录</button>
                </div>
            </form>
        </div>
    </div>
</script>
<script type="text/ng-template" id="signup.tpl">
    <div ng-controller='SignupController'
         class="signup container">
        <div class="card">
            <h1>注册<br /></h1>
            <?php /*[: User.signup_data :]*/ ?>
            <form name="signup_form"
                  ng-submit="User.signup()">
                <div class="input-group">
                    <label>用户名</label>
                    <input  name="username"
                            type="text"
                            ng-minlength="4"
                            ng-maxlength="16"
                            ng-model="User.signup_data.username"
                            ng-model-options="{debounce:1000}"

                            required
                    >
                    <div class="input-error-set" ng-if="signup_form.username.$touched">
                        <div ng-if="signup_form.username.$error.required">
                            用户名必填
                        </div>
                        <div ng-if="signup_form.username.$error.maxlength
                        || signup_form.username.$error.minlength">
                            用户名长度需在4-16字符之间
                        </div>
                        <div ng-if="User.signup_username_exists">
                            用户名已存在
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>密码</label>
                    <input
                            name="password"
                            type="password"
                            ng-minlength="6"
                            ng-maxlength="18"
                            ng-model="User.signup_data.password"
                            ng-model-options="{debounce:500}"
                            required
                    >
                    <div class="input-error-set" ng-if="signup_form.password.$touched">
                        <div ng-if="signup_form.password.$error.required ">
                            密码必填
                        </div>
                        <div ng-if="signup_form.password.$error.maxlength || signup_form.password.$error.minlength ">
                            密码长度需在6-18之间
                        </div>
                    </div>
                </div>

                <button
                        class="primary"
                        ng-disabled="signup_form.$invalid"
                        type="submit">注册
                </button>
            </form>
            简　介:《小鸡炖蘑菇》是由糗事百科出品的一档周播综艺脱口秀节目，除了有无节操女主播的犀利吐槽，还有好看的小剧场和路人恶搞等环节，每周三我们不见不散！******************
        </div>
    </div>
</script>
<script type="text/ng-template" id="question.add.tpl">
    <div ng-controller="QuestionAddController" class="question-add container">
        <div class="card">
            <form ng-submit="Question.add()" name="question_add_form">
                <div class="input-group">
                    <label >问题标题</label>
                    <input name="title"
                           type="text"
                           ng-model="Question.new_question.title"
                           ng-minlength="5"
                           ng-maxlength="255"
                           required
                    >
                </div>
                <div class="input-group">
                    <label>问题描述</label>
                    <textarea name="desc" type="text" ng-model="Question.new_question.desc">

                    </textarea>
                </div>
                <div class="input-group">
                    <button
                            type="submit"
                            ng-disabled="question_add_form.$invalid"
                            class="primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</script>
</html>