<script>
    var examWizard=$.fn.examWizard({finishOption:{enableModal:!0},quickAccessOption:{quickAccessPagerItem:5}});$(".question-response-rows").click(function(){var e=$(this).data("question"),s=".question-"+e;$(".question").addClass("hidden"),$(s).removeClass("hidden"),$("input[name=currentQuestionNumber]").val(e),$("#current-question-number-label").text(e),$("#back-to-prev-question").removeClass("disabled"),$("#go-to-next-question").removeClass("disabled")});
</script>