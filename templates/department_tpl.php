<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../database/classes/department_class.php');
require_once(__DIR__ . '/../utils/function_utils.php');

function drawDepartment()
{

    $db = databaseConnection();
    $query = 'SELECT * FROM Department';

    $departments = Department::getDepartment($db, $query);

    ?>

    <table class="table">
        <tr class="row0-d">
            <th class="row00-d">Id</th>
            <th class="row000-d">Name</th>
        </tr>
        <?php
        foreach ($departments as $department) { ?>
            <tr class="row1-d" id="<?php echo $department->id ?>">
                <td class="row11-d">
                    <?php echo $department->id ?>
                </td>
                <td class="row111-d">
                    <?php echo $department->name ?>
                </td>
            </tr>
        <?php }
        ?>
    </table>
<?php }

function drawDepartmentPopup()
{
    $db = databaseConnection();
    $query = 'SELECT * FROM Department';

    $departments = Department::getDepartment($db, $query);

    foreach ($departments as $department) {
        ?>
        <div class="edit_department_popup" id="form<?php echo $department->id ?>">
            <div class="popup-content">
                <img class="popup-content-close" src="/../../images/close.png" alt="close">
                <p class="popup-content-text">Edit department
                    <?php echo $department->id ?>
                </p>
                <form name="editDepartmentForm" class="popup-content-form"
                    action="/../../database/processes/process_department.php" method="POST">
                    <input type="hidden" name="edit_id" value="<?php echo $department->id ?>">
                    <input type="text" name="edit_name" value="<?php echo $department->name ?>">
                    <button type="submit" class="popup-content-submit">Edit</button>
                </form>
                <form name="deleteDepartmentForm" class="popup-content-form"
                    action="/../../database/processes/process_department.php" method="POST">
                    <input type="hidden" name="delete_id" value="<?php echo $department->id ?>">
                    <button type="submit" name="delete_department" class="popup-content-delete">Delete department</button>
                </form>
            </div>
        </div>
    <?php }
}

function drawDepartmentWithCheckbox($user_id)
{
    $db = databaseConnection();
    $query = 'SELECT * FROM Department';

    $departments = Department::getDepartment($db, $query);

    $agent_departments = getDepartmentsFromAgent($user_id);

    foreach ($departments as $department) {
        ?>
        <li>
            <div class="option">
                <input class="option-checkmark" type="checkbox" name="department[]" value="<?php echo $department->id ?>"
                    id="cb<?php echo $department->id ?>" <?php if (in_array($department->id, $agent_departments))
                           echo "checked" ?>>
                    <span class="option-text">
                    <?php echo $department->name ?>
                </span>
            </div>
        </li>
        <?php
    }
}
?>