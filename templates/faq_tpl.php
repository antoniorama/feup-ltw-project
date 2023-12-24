<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../utils/function_utils.php');
require_once(__DIR__ . '/../database/classes/question_class.php');

function drawQuestion($questionObj)
{
    $question = $questionObj->question;
    $answer = $questionObj->answer;
    $id = $questionObj->id;
    ?>
    <div class="faq-box">
        <button class="faq-accordion">
            <?php echo $id . ". " . $question; ?>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="faq-answer-pannel">
            <p>
                <?php echo $answer; ?>
            </p>
        </div>
    </div>
    <?php
}

function drawQuestions()
{
    $db = databaseConnection();
    $questions = Question::getQuestions($db);
    ?>
    <?php
    foreach ($questions as $question) {
        drawQuestion($question);
    }
    ?>
    <script>
        let acc = document.getElementsByClassName("faq-accordion");
        let i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");

                let panel = this.nextElementSibling;
                if (panel.style.display == "block") {
                    panel.style.display = "none";
                }
                else {
                    panel.style.display = "block";
                }
            });
        }
    </script>
    <?php
}

function createFaqPopup() {
    ?>
    <div class="filter_user_popup" id="new-faq-popup">
    <div class="filter_user_popup_content">
        <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
        <p class="filter_user_popup_content_text">Create new FAQ</p>
        <form class="filter_user_form" action="/../../database/processes/process_create_faq.php" method="POST">
            <div class="form-row">
                <label class="label-ticketp0k">Question: </label>
                <input type="text" placeholder="Write your problem..." name="question">
            </div>
            <div class="form-row">
                <label class="label-ticketp0k">Answer: </label>
                <textarea name="answer" rows="5" cols="25" placeholder="Everyone should know that..."></textarea>
            </div>
            <div class="submit-row">
                <input type="submit" name="submit" value="Submit FAQ">
            </div>
        </form>
    </div>
</div>
<?php
}