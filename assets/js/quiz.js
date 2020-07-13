var MQZ_Quiz;
(function ($) {
    MQZ_Quiz = {
        load: function() {
            var template = wp.template( 'mqz-quiz' );
            var row = $(template(window.json_str.quiz));            
            jQuery(".quiz-container").html(row);
            var navi = wp.template( 'mqz-navigation');
            row = $(navi(window.json_str.quiz));
            jQuery(".quiz-navigation").html(row);

            jQuery(".quiz-navigation").off("click").on("click", ".btn", function(e) {
                if (jQuery(this).attr("data-action") == "start") {
                    MQZ_Quiz.GoToPage(1, 1);
                } else if (jQuery(this).attr("data-action") == "next") {
                    MQZ_Quiz.GoToPage(1, 1);
                } else if (jQuery(this).attr("data-action") == "prev") {
                    MQZ_Quiz.GoToPage(-1, 1);
                }
            });

            jQuery("#quiz-container").off("click").on("click", ".question-answer", function(e) {
                e.preventDefault();
                var q_id = parseInt(MQZ_Quiz.GetQuestion());
                var a_id = parseInt(jQuery(this).attr("data-id"));

                if (jQuery(this).hasClass("active")) {
                    jQuery(this).removeClass("active");
                    window.json_str.questions[q_id - 1].answers[a_id].selected = false;
                } else {
                    jQuery(this).addClass("active");
                    window.json_str.questions[q_id - 1].answers[a_id].selected = true;
                }

            });

            MQZ_Quiz.AddQuestion(0);
        },
        GoToPage: function(questionid, pagenum) {
            
            var q_id = parseInt(MQZ_Quiz.GetQuestion()) + questionid;
            
            if (q_id > window.json_str.questions.length) {
                MQZ_Quiz.ShowResult();
                return;
            }
            var template = wp.template( 'mqz-question' );
            var question  = window.json_str.questions[q_id - 1];
            console.log(question);
            var row = $(template(question));   
            jQuery(".quiz-container").html(row);

            var tpl_answer = wp.template ('mqz-question-answer');
            for (var i in question.answers) {
                var answer = question.answers[i];
                answer.id = i;
                if (answer.title == "") answer.title = "Answer " + i;

                row = $(tpl_answer(answer));
                console.log(answer);
                jQuery(".quiz-container .question-answers").append(row);
            }
            
            
            MQZ_Quiz.AddQuestion(q_id);
            MQZ_Quiz.ShowNavigation(q_id);
        },
        ShowNavigation: function(q_id) {
            if (q_id > 0) {
                jQuery(".quiz-navigation .btn-q").show();
                jQuery(".quiz-navigation .btn-quiz").hide();
            } else if (q_id == -1) {
                jQuery(".quiz-navigation .btn-q").hide();
                jQuery(".quiz-navigation .btn-quiz").hide();
            }
            else {
                jQuery(".quiz-navigation .btn-q").hide();
                jQuery(".quiz-navigation .btn-quiz").show();
            }
        },
        AddQuestion: function(id) {
            sessionStorage.setItem("question", id);
        },
        GetQuestion: function() {
            return sessionStorage.getItem("question");
        },
        ShowResult: function() {
            var template = wp.template( 'mqz-result' );            
            var row = $(template({}));
            jQuery(".quiz-container").html(row);
            MQZ_Quiz.ShowNavigation(-1);
        }
    };
    jQuery(document).ready(function(e) {
        if (typeof window.json_str != "undefined")
            MQZ_Quiz.load();
    });
}(jQuery));