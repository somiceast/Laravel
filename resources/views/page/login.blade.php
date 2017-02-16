

   {{--Created by PhpStorm.--}}
   {{--User: Little Guy--}}
   {{--Date: 2017/1/11--}}
   {{--Time: 21:09--}}
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
                <div class="input-group clearfix">
                    <button ng-disabled="login_form.username.$error.required || login_form.password.$error.required"
                            class="primary clearfix"
                            type="submit"
                                    >登录</button>
                </div>
            </form>
        </div>
    </div>