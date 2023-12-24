<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/function_utils.php');
?>

<?php function drawHeader($name)
{ ?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <meta charset="utf-8">
        <link rel="icon" href="/images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#000000">
        <title>Tickets</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400%2C500">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400%2C500">
        <link rel="stylesheet" href="/../css/general.css">
        <link rel="stylesheet" href="/../css/company.css">
        <link rel="stylesheet" href="/../css/user_dropdown.css">
        <link rel="stylesheet" href="/../css/admin_view_departments.css">
        <link rel="stylesheet" href="/../css/admin_view_user_profile.css">
        <link rel="stylesheet" href="/../css/admin_view_users.css">
        <link rel="stylesheet" href="/../css/change_password.css">
        <link rel="stylesheet" href="/../css/client_settings.css">
        <link rel="stylesheet" href="/../css/login_page.css">
        <link rel="stylesheet" href="/../css/main_page.css">
        <link rel="stylesheet" href="/../css/register_page.css">
        <link rel="stylesheet" href="/../css/signup_successful.css">
        <link rel="stylesheet" href="/../css/user_view.css">
        <link rel="stylesheet" href="/../css/ticketpage.css">
        <link rel="stylesheet" href="/../css/agent_view_tickets.css">
        <link rel="stylesheet" href="/../css/ticket_style.css">
        <link rel="stylesheet" href="/../css/faq_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous" referrerpolicy="no-referrer">
        <script src="/../javascript/<?php echo $name ?>.js" defer></script>
    </head>
<?php }

function drawCompany()
{ ?>
    <div class="company">
        <img src="/../../../images/favicon.png" alt="~">
        <div class="text_name">TicketCare</div>
    </div>
<?php }

function drawUserTab()
{
    ?>
    <div class="user-tab-all">
        <div class="user" onclick="openDropdown()" style="cursor: pointer;">
            <img class="arrow" src="/../images/arrow_drop.png" alt="↓">
            <div class="text_username">
                <?php echo getUsername($_SESSION['user_id']) ?>
            </div>
        </div>
        <div id="userDropdown" class="dropdown-content">
            <a href="/../pages/client_settings.php">
                <div class="profile" style="cursor: pointer;">
                    <img class="profile_icon" src="/../images/user.png" alt="user">
                    <div class="profile_text">Profile</div>
                </div>
            </a>
            <div id="logout-btn" class="logout" style="cursor: pointer;">
                <img class="logout_icon" src="/../images/logout.png" alt="X">
                <div class="logout_text">Log out</div>
            </div>
        </div>
    </div>
<?php }

function drawClientSideTab($selected): void
{
    ?>
    <div class="side_tab"> <!--  alterar icons -->
        <?php drawCompany() ?>
        <div class="line"></div>
        <div class="button_list">
            <a href="/../../pages/client_view/client_faq.php">
                <div class="button_departments">
                    <p class="text_tikets">FAQ</p>
                    <img class="image_tickets" src="/../../images/department.png" alt="O">
                    <?php if ($selected === 1) { ?>
                        <div class="rectangle"></div>
                    <?php } ?>
                </div>
            </a>
            <a href="/../../pages/client_view/tickets.php?selected=open">
                <div class="button_users">
                    <p class="text_users">Tickets</p>
                    <img class="image_users" src="/../../images/users.png" alt="O">
                    <?php if ($selected === 2) { ?>
                        <div class="rectangle"></div>
                    <?php } ?>
                </div>
            </a>
        </div>
    </div>
<?php }