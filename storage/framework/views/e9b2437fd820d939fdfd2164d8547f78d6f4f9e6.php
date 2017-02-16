<div ng-controller="QuestionDetailController"
     class="container question-detail"
>
  <div class="card">
    question detail
    <h1>
      [: Question.current_question.title :]
    </h1>
    <div class="desc">
      [: Question.current_question.desc :]
    </div>
    <span class="cursor gray"
          ng-click="Question.show_update_form = !Question.show_update_form"
          ng-if="his.id == Question.current_question.user_id"><span ng-if="Question.show_update_form">取消</span>修改问题</span>
    <form ng-submit="Question.update()"
          class="well gray_card"
          ng-if="Question.show_update_form"
          name="question_add_form">
      <div class="input-group">
        <label >问题标题</label>
        <input name="title"
               type="text"
               ng-model="Question.current_question.title"
               ng-minlength="5"
               ng-maxlength="255"
               required
        >
      </div>
      <div class="input-group">
        <label>问题描述</label>
        <textarea name="desc"
                  type="text"
                  ng-model="Question.current_question.desc">

                    </textarea>
      </div>
      <div class="input-group clearfix">
        <button
            type="submit"
            ng-disabled="question_add_form.$invalid"
            class="primary">提交</button>
      </div>
    </form>
    <div>
      <span class="gray">回答数：[: Question.current_question.answers_with_user_info.length :]</span>
    </div>
    <div class="hr"></div>
    <div class="feed item clearfix">
      <div class=" well tac gray" ng-if="Question.current_question.answers_with_user_info.length == 0">
        还没有回答，快来抢沙发！
      </div>
      <div class="hr"></div>
      <div ng-if="!Question.current_answer_id ||
                    Question.current_answer_id == item.id"
           ng-repeat="item in Question.current_question.answers_with_user_info">
        [: QuestionService.current_answer_id :]
        <div class="vote">
          <div ng-click="Question.vote({id:item.id,vote:1})" class="up">[: item.upvote_count:]</div>
          <div ng-click="Question.vote({id:item.id,vote:2})" class="down">踩[:item.downvote_count:]</div>
        </div>


        <div class="feed-item-content">
          <div><span ui-sref="user({id:item.user.id})">[: item.user.username :]</span></div>
          <div>[: item.content :]
            <div class="action-set">
              <span ng-click="item.show_comment=!item.show_comment">
                <span ng-if="item.show_comment">取消</span>评论
              </span>

              <span class="gray">
                <span ng-if="item.user_id == his.id">
                  <a ng-click="Answer.answer_form = item" class="cursor">
                    编辑
                  </a>
                  <a ng-click="Answer.delete(item.id)" class="cursor">
                    删除
                  </a>
                </span>

                <a
                    ui-sref="question.detail({id: Question.current_question.id, answer_id:item.id})">
                  [: item.updated_at :]
                </a>
              </span>
            </div>
          </div>
        </div>
        <div comment-block ng-if="item.show_comment" answer-id="item.id">
          comment
          comment
          comment
          comment
          comment
        </div>
        <div class="hr"></div>
      </div>
    </div>
    <div>
      <form ng-submit="Answer.add_or_update(Question.current_question.id)"
            name="answer_form"
            class="answer_form">


        <div class="input-group clearfix">
                    <textarea
                        type='text'
                        name='content'
                        ng-model="Answer.answer_form.content"
                        placeholder="添加回答"
                        required></textarea>

        </div>
        <div class="input-group clearfix">
          <button ng-disabled="answer_form.$invalid"
                  type="submit" class="primary">
            提交
          </button>
        </div>
      </form>
      <div>
        <p class="input-error-set" ng-if="Answer.delete_num">
          评论已被成功删除
        </p>
        <p class="input-error-set" ng-if="Answer.answer_self">
          您不能给自己投票
        </p>
      </div>
    </div>
  </div>
</div>