<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../database/classes/ticket_class.php');
require_once(__DIR__ . '/../database/classes/question_class.php');
require_once(__DIR__ . '/../utils/function_utils.php');

function drawTicket($ticket)
{
    ?>
    <div class="container_ticket">
        <div class="text_title">
            <p class="text_title_title">
                <?php echo $ticket->title; ?>
            </p>
            <?php drawTicketDepartment($ticket); ?>
            <?php drawTicketTime($ticket); ?>
        </div>
        <div class="text_description">
            <?php echo $ticket->description ?>
        </div>
        <div class="ticket-info-container">
            <?php
            drawTicketStatus($ticket);
            drawTicketPriority($ticket);
            drawTicketTags($ticket->id);
            drawTicketNumberMessages($ticket);
            ?>
        </div>
    </div>
    <?php
}

function drawTicketTime(Ticket $ticket): void
{
    $last_message_time = getTicketLastMessageTime($ticket->id);
    ?>
    <p class="ticket-info-time">
        <?php echo $last_message_time ?>
    </p>
<?php }

function drawTicketDepartment(Ticket $ticket): void
{
    ?>
    <p class="ticket-info-department">
        <?php echo getDepartmentFromDepartmentId($ticket->department_id) ?>
    </p>
<?php }

function drawTicketStatus(Ticket $ticket): void
{
    ?>
    <p class="ticket-info-status">
        <?php echo getStatusFromStatusId($ticket->status_id); ?>
    </p>
<?php }

function drawTicketPriority(Ticket $ticket): void
{
    if ($ticket->priority == 1) { ?>
        <p class="ticket-info-priority-low">Low</p>
    <?php } ?>
    <?php if ($ticket->priority == 2) { ?>
        <p class="ticket-info-priority-medium">Medium</p>
    <?php } ?>
    <?php if ($ticket->priority == 3) { ?>
        <p class="ticket-info-priority-high">High</p>
    <?php }
}


function drawTicketTags($ticket_id): void
{
    $tags = getTagsFromTicket($ticket_id);

    foreach ($tags as $tag) {
        if (getUserType($_SESSION['user_id']) !== "Client") {
            ?>
            <form action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="selected" value="<?php echo $_GET['selected'] ?>">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id ?>">
                <input type="hidden" name="tag_id" value="<?php echo $tag['id'] ?>">
                <button class="ticket-info-tag" id="tag-agent">
                    <?php echo $tag['name'] ?>
                </button>
            </form>
        <?php } else {
            ?>
            <button class="ticket-info-tag">
                <?php echo $tag['name'] ?>
            </button>
            <?php
        }
    }
}

function drawTicketNumberMessages(Ticket $ticket): void
{
    $total_messages = getTicketNumberMessages($ticket);

    ?>
    <img class="message-number-img" src="/images/chat_bubble.png" alt="chat">
    <p class="message-number-text">
        <?php echo $total_messages ?>
    </p>
<?php }

// Draws tickets from clients
function drawTicketsClient($query)
{

    $client_id = $_SESSION['client_id'];

    $db = databaseConnection();

    $tickets = Ticket::getTickets($db, $client_id, $query);

    foreach ($tickets as $ticket) {

        $url = "../ticketpage.php?id=" . $ticket->id; ?>
        <a href=<?php echo $url; ?>>
            <?php drawTicket($ticket); ?>
        </a>
    <?php }
}

// Draw ticket from agent 
function drawTicketsAgent($query): void
{

    $agent_id = $_SESSION['agent_id'];

    $db = databaseConnection();

    if (str_contains($query, '?')) {
        $tickets = Ticket::getTickets($db, $agent_id, $query);
    } else {
        $tickets = Ticket::getTickets($db, $agent_id, $query, true);
    }


    if (count($tickets) > 0) {
        foreach ($tickets as $ticket) {
            $url = "../ticketpage.php?id=" . $ticket->id;
            ?>
            <a href="<?php echo $url; ?>">
                <?php drawTicket($ticket); ?>
            </a>
            <?php
        }
    }
}

function drawTicketInfo($ticket_id)
{
    $ticket = getTicket($ticket_id);

    $requester = getUsername($ticket->client_id); // only works because client_id is always same as user_id
    $agent = getUsername(getUserIdFromAgentId($ticket->agent_id));
    $department = getDepartmentName($ticket->department_id);
    if ($ticket->department_id === 0)
        $department = "No department";
    $status = getStatusName($ticket->status_id);
    $priority = $ticket->priority;
    $last_activity_date = getLastActivity($ticket->id);
    $created_date = getCreatedDate($ticket->id);

    $user_type = getUserType($_SESSION['user_id']);

    ?>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Requester</div>
        <div class="ticket-info-row-text">
            <?php echo $requester; ?>
        </div>
    </div>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Agent</div>
        <?php
        if ($ticket->agent_id === 0 && getUserType($_SESSION['user_id']) !== "Client") { ?>
            <form action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                <input type="hidden" name="agent_id" value="<?php echo $_SESSION['agent_id']; ?>">
                <button type="submit" class="assign-myself">Assign myself</button>
            </form>
        <?php } else { ?>
            <div class="ticket-info-row-text" <?php if ($user_type !== "Client") {
                echo "id='info1'";
            } ?>> <?php echo $agent; ?>
            </div>
        <?php } ?>
    </div>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Department</div>
        <div class="ticket-info-row-text" <?php if ($user_type !== "Client") {
            echo "id='info2'";
        } ?>> <?php echo $department; ?> </div>
    </div>
    <div class="ticket-info-row">
    <div class="ticket-info-row-head"> Status</div>
        <div class="ticket-info-row-text" <?php if ($user_type !== "Client") {
            echo "id='info3'";
        } ?>> <?php echo $status; ?>
        </div>
    </div>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Priority</div>
        <div class="ticket-info-row-text">
            <?php echo $priority; ?>
        </div>
    </div>
    <?php if (getUserType($_SESSION['user_id']) !== "Client") { ?>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Tags</div>
        <div class="ticket-info-row-text">
            <form action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                <input type="text" name="tag_name" placeholder="Add tag">
                <datalist id="tag-suggestions">
                    <!-- This allows autocomplete -->
                    <?php
                    $all_tags = getAllTags();
                    $tag_names = array_column($all_tags, 'name');
                    foreach ($tag_names as $name) {
                        ?>
                        <option value="<?php echo $name ?>">
                            <?php
                    }
                    ?>
                </datalist>
            </form>
        </div>
    </div>
    <?php } ?>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Last Activity</div>
        <div class="ticket-info-row-text">
            <?php echo $last_activity_date; ?>
        </div>
    </div>
    <div class="ticket-info-row">
        <div class="ticket-info-row-head"> Created</div>
        <div class="ticket-info-row-text">
            <?php echo $created_date; ?>
        </div>
    </div>

    <?php if ($user_type === "Client") { ?>
        <div class="separator"> </div>

        <div class="ticket-info-new-ticket-button">
            <p class="ticket-info-new-ticket-button-text">New Ticket</p>
            <img class="image_users" src="/../../images/users.png" alt="O">
        </div>

        <?php
    }
}

function drawNewTicketPopup()
{
    ?>
    <div class="filter_user_popup" id="new-ticket-popup">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Create new ticket</p>
            <form class="filter_user_form" action="/../../database/processes/process_create_ticket.php" method="POST">
                <div class="form-row">
                    <label class="label-ticketp0k">Title: </label>
                    <input type="text" placeholder="Ticket tile" name="title">
                </div>
                <div class="form-row">
                    <label class="label-ticketp0k">Description: </label>
                    <textarea name="description" rows="5" cols="25" placeholder="Describe your problem"></textarea>
                </div>
                <div class="form-row">
                    <label class="label-ticketp0k">Priority: </label>
                    <select name="priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="form-row">
                    <label class="label-ticketp0k">Department: </label>
                    <select name="department">
                        <option value="0">No department</option>
                        <?php $departments = getAllDepartments();
                        foreach ($departments as $department) {
                            ?>
                            <option value="<?php echo $department['id'] ?>"><?php echo $department['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Create ticket">
                </div>
            </form>
        </div>
    </div>
    <?php
}

function drawTicketTitle($ticket_id)
{
    $ticket = getTicket($ticket_id);

    $title = $ticket->title;

    ?>
    <div class=main-tab-text><?php echo $title; ?>
    </div>
    <?php
}

function drawTicketText($ticket_id, $user_id)
{


    $messages = getChat($ticket_id);
    ?>
    <div class="chat-box">
        <?php
        foreach ($messages as $message) {

            $sender_name = getUsername($message->sender_id);
            ?>
            <div class="message-box">
                <div class="message-header">
                    <div class="message-sender">
                        <?php echo $sender_name . ' (' . getUserType($message->sender_id) . ')' ?>
                    </div>
                    <div class="message-datestamp">
                        <?php echo $message->timestamp ?>
                    </div>
                </div>
                <div class="separator"></div>

                <div class="message-text">
                    <?php echo filter_var($message->message, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                </div>
            </div>
            <?php
        }
        ?>
        <?php if (canWriteTicket($ticket_id, $user_id)) { ?>
            <form action="/../database/processes/process_send_message.php" method="post" class="message-form">
                <textarea id="message-write" name="message-input" rows="5" cols="60"
                    placeholder="Describe your problem"></textarea>
                <input type="hidden" name="ticket_id" value="<?php echo $_GET['id'] ?>">
                <button id="submit-button">Submit</button>
            <?php } ?>
        </form>
        <?php if (isTicketAgent($ticket_id, $user_id)) { ?>
            <button id="message-choose-faq-button">use a FAQ</button>
        <?php } ?>

    </div>
    <?php
}

function drawChooseFAQPopup($ticket_id)
{
    ?>
    <div class="filter_user_popup" id="choose-faq-popup">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Choose a FAQ</p>
            <form class="filter_user_form" action="/../../database/processes/process_send_message.php" method="POST">
                <div class="form-row">
                    <label class="label-ticketp0k">FAQ</label>
                    <select name="message-input">
                        <option value="0">No FAQ</option>
                        <?php $questions = getAllQuestions();
                        foreach ($questions as $question) {
                            ?>
                            <option value="<?php echo $question->answer ?>"><?php echo $question->id . ". " . $question->question ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Submit Answer">
                </div>
            </form>
        </div>
    </div>
    <?php
}