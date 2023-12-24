<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/ticket_tpl.php');
require_once(__DIR__ . '/../../templates/filter_tpl.php');

drawHeader("agent_view_tickets");

session_start();

// Check if user is allowed to access this page
handleUser(false, false, true, true);


if (!isset($_SESSION['ticket_filters_query'])) {
    $_SESSION['ticket_filters_query'] = "";
}

$to_add = $_SESSION['ticket_filters_query'];

if (!isset($_SESSION['sort_by'])) {
    $_SESSION['sort_by'] = "DESC";
}

$sort_by = $_SESSION['sort_by'];

// Note: Agents should be able to see all tickets from their departments, 
// and none of the other departments
$unassigned_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
JOIN Department d ON t.department_id = d.id OR t.department_id = 0
LEFT JOIN (
    SELECT ticket_id, MAX(timestamp) AS last_message_time
    FROM ChatMessage
    GROUP BY ticket_id
) cm ON t.id = cm.ticket_id
WHERE t.agent_id IS NULL
    AND t.status_id NOT IN (SELECT id FROM Status WHERE name LIKE '%Closed%')
    AND EXISTS (
        SELECT 1
        FROM AgentDepartment ad
        WHERE ad.agent_id = ?
        AND (ad.department_id = t.department_id OR t.department_id = 0)
    ) $to_add
GROUP BY t.id
ORDER BY cm.last_message_time $sort_by;";

$assigned_to_me_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
JOIN Department d ON t.department_id = d.id OR t.department_id = 0
LEFT JOIN (
    SELECT ticket_id, MAX(timestamp) AS last_message_time
    FROM ChatMessage
    GROUP BY ticket_id
) cm ON t.id = cm.ticket_id
WHERE t.agent_id = ? 
    AND t.status_id NOT IN (SELECT id FROM Status WHERE name LIKE '%Closed%')
    AND EXISTS (
        SELECT 1
        FROM AgentDepartment ad
        WHERE ad.agent_id = t.agent_id
        AND (ad.department_id = t.department_id OR t.department_id = 0)
    ) $to_add
GROUP BY t.id
ORDER BY cm.last_message_time $sort_by;
";

$all_tickets_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
LEFT JOIN AgentDepartment ad ON t.department_id = ad.department_id OR t.department_id = 0
LEFT JOIN (
    SELECT ticket_id, MAX(timestamp) AS last_message_time
    FROM ChatMessage
    GROUP BY ticket_id
) cm ON t.id = cm.ticket_id
WHERE (ad.agent_id = ? OR t.agent_id IS NULL)
    AND status_id NOT IN (SELECT id FROM Status WHERE name LIKE '%Closed%')
    AND EXISTS (
        SELECT 1
        FROM AgentDepartment ad
        WHERE ad.agent_id = t.agent_id
        AND (ad.department_id = t.department_id OR t.department_id = 0)
    ) $to_add
GROUP BY t.id
ORDER BY cm.last_message_time $sort_by;";


$archive_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
INNER JOIN AgentDepartment ad ON t.department_id = ad.department_id OR t.department_id = 0
LEFT JOIN (
    SELECT ticket_id, MAX(timestamp) AS last_message_time
    FROM ChatMessage
    GROUP BY ticket_id
) cm ON t.id = cm.ticket_id
WHERE ad.agent_id = ?
    AND status_id IN (SELECT id FROM Status WHERE name LIKE '%Closed%')
    AND EXISTS (
        SELECT 1
        FROM AgentDepartment ad
        WHERE ad.agent_id = t.agent_id
        AND (t.department_id = ad.department_id OR t.department_id = 0)
    ) $to_add
GROUP BY t.id
ORDER BY cm.last_message_time $sort_by;";

?>

<body>
    <?php drawUserTab() ?>
    <div class="side_tab">
        <?php drawCompany() ?>
        <div class="line"></div>
        <div class="button_list">
            <a href="overview.php">
                <div class="button_departments">
                    <p class="main-tab-text">Overview</p>
                    <img class="side-bar-imagFe-1" src="/../images/overview.png" alt="O">
                </div>
            </a>
            <div class="button_users">
                <p class="text_users">Tickets</p>
                <img class="side-bar-image-2" src="/../../images/tickets.png" alt="O">
                <div class="rectangle"></div>
            </div>
        </div>
    </div>
    <div class="main-tab">
        <div class="upper_tab">
            <p class="main-tab-text">Tickets</p>
            <div class="upper-tab-button">
                <img class="img_pencil" src="/../../images/pencil.png" alt="edit">
                <p class="text_department">Filter</p>
            </div>
        </div>
        <div class="navigation">
            <a href="?selected=unassigned">
                <div class="navigation-group">
                    <div class="navigation-click">Unassigned</div>
                    <?php if ($_GET['selected'] == 'unassigned') { ?>
                        <div class="navigation-downbar"></div>
                    <?php } ?>
                </div>
            </a>
            <a href="?selected=assigned-to-me">
                <div class="navigation-group">
                    <div class="navigation-click">Assigned to me</div>
                    <?php if ($_GET['selected'] == 'assigned-to-me') { ?>
                        <div class="navigation-downbar"></div>
                    <?php } ?>
                </div>
            </a>
            <a href="?selected=all-tickets">
                <div class="navigation-group">
                    <div class="navigation-click">All tickets</div>
                    <?php if ($_GET['selected'] == 'all-tickets') { ?>
                        <div class="navigation-downbar"></div>
                    <?php } ?>
                </div>
            </a>
            <a href="?selected=archive">
                <div class="navigation-group">
                    <div class="navigation-click">Archive</div>
                    <?php if ($_GET['selected'] == 'archive') { ?>
                        <div class="navigation-downbar"></div>
                    <?php } ?>
                </div>
            </a>
        </div>
        <?php if ($_GET['selected'] == 'unassigned') {
            drawTicketsAgent($unassigned_query);
        } ?>
        <?php if ($_GET['selected'] == 'assigned-to-me') {
            drawTicketsAgent($assigned_to_me_query);
        } ?>
        <?php if ($_GET['selected'] == 'all-tickets') {
            drawTicketsAgent($all_tickets_query);
            drawTicketsAgent($unassigned_query);
        } ?>
        <?php if ($_GET['selected'] == 'archive') {
            drawTicketsAgent($archive_query);
        } ?>
    </div>
    <div class="right-side-tab">
        <div class="right-side-tab-container">
            <p class="right-side-tab-text">Recent activity</p>
            <button class="right-side-tab-button">Show more</button>
        </div>
    </div>
    <!-- This has "user" classes because I am copying the CSS from admin panel
    I know that I should have done a general name since the beginning but i didn't
    and now it's a lot of work to rename everything -->
    <div class="filter_user_popup">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/../../images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Choose your filters</p>
            <form class="filter_user_form" action="/../../database/processes/process_ticket_filter.php">
                <input type="hidden" name="selected" value=<?php echo $_GET['selected'] ?>>
                <div class="form-row">
                    <label for="order5">Order by:</label>
                    <select id="order5" name="order1">
                        <option value="Recent message date" <?php echo $sort_by === "DESC" ? 'selected' : ''; ?>>Recent
                            message date</option>
                        <option value="Old message date" <?php echo $sort_by === "ASC" ? 'selected' : ''; ?>>Old message
                            date</option>
                    </select>
                </div>
                <div class="filter-popup-button">Assigned agents</div>
                <div class="collapse-dropdown">
                    <ul class="filter-popup-nav">
                        <?php
                        $colleages = getColleages($_SESSION['user_id']);
                        foreach ($colleages as $user) {
                            drawUserInDropdown($user);
                        }
                        ?>
                    </ul>
                </div>
                <br>
                <div class="filter-popup-button" id="fpb2">Status</div>
                <div class="collapse-dropdown" id="cd2">
                    <ul class="filter-popup-nav">
                        <?php
                        $status = getAllStatus();
                        foreach ($status as $s) {
                            drawStatusInDropdown($s['id']);
                        }
                        ?>
                    </ul>
                </div>
                <br>
                <div class="filter-popup-button" id="fpb3">Priority</div>
                <div class="collapse-dropdown" id="cd3">
                    <ul class="filter-popup-nav">
                        <?php drawPriorityInDropDown(); ?>
                    </ul>
                </div>
                <br>
                <div class="filter-popup-button" id="fpb4">Hashtags</div>
                <div class="collapse-dropdown" id="cd4">
                    <ul class="filter-popup-nav">
                        <?php
                        $hashtags = getAllTags();
                        foreach ($hashtags as $tag) {
                            drawTagInDropdown($tag['id']);
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear-filters-row">
                    <input class="clear-filters" type="submit" name="clear-filters" id="90d" value="Clear filters">
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Filter">
                </div>
            </form>
        </div>
    </div>
</body>