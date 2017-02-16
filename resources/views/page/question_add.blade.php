 {{--Created by PhpStorm. User: Little Guy Date: 2017/1/11--}}
 {{--Time: 21:19--}}
 <div ng-controller="QuestionAddController"
      class="question-add container">
     <div class="card">
         <form ng-submit="Question.add()"
               name="question_add_form">
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
                 <textarea name="desc"
                           type="text"
                           ng-model="Question.new_question.desc">

                    </textarea>
             </div>
             <div class="input-group clearfix">
                 <button
                         type="submit"
                         ng-disabled="question_add_form.$invalid"
                         class="primary">提交</button>
             </div>
         </form>
     </div>
 </div>
