
  {{--Created by PhpStorm.--}}
  {{--User: Little Guy--}}
  {{--Date: 2017/1/11--}}
  {{--Time: 21:06--}}


  <div ng-controller='SignupController'
       class="signup container">
      <div class="card">
          <h1>注册<br /></h1>
          {{--[: User.signup_data :]--}}
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