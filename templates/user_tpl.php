<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../database/classes/user_class.php');
require_once(__DIR__ . '/../utils/function_utils.php');

function drawUser()
{
    $db = databaseConnection();

    $query = $_SESSION['user_filters_query'];

    $users = User::getUser($db, $query);

    ?>
    <table class="table">
        <tr class="row0-u">
            <th class="row00-u">Id</th>
            <th class="row000-u">Username</th>
            <th class="row0000-u">Name</th>
            <th class="row00000-u">Email</th>
            <th class="row000000-u">Type</th>
        </tr>
        <?php
        foreach ($users as $user) {
            $profileUrl = "/../pages/admin_view/user_profile.php?user_id=" . $user->id;
            ?>
            <tr class="row1-u" id="<?php echo $user->id ?>" onclick="window.location.href='<?php echo $profileUrl; ?>'">
                <td class="row11-u">
                    <?php echo $user->id ?>
                </td>
                <td class="row111-u">
                    <?php echo $user->username ?>
                </td>
                <td class="row1111-u">
                    <?php echo $user->name ?>
                </td>
                <td class="row11111-u">
                    <?php echo $user->email ?>
                </td>
                <td class="row111111-u">
                    <?php echo getUserType($user->id) ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>