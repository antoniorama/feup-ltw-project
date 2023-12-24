<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../utils/function_utils.php');

/*
----------- 
    NOTE
-----------
The way that verifying if the checkbox must be selected is by the filter GET parameter
this is not optimal, for example:
If an agent has the same username as an hashtag, this would lead to the user being selected
by default if the hashtag is also selected.
We are aware of this not being optimal but since it doesn't affect the system very much
(in the worst case affects user experience, but doesn't compromise website functionalities)
we decided to keep it like this and apply our time in implementing more functionalities.
*/

// Draws username (from $user_id) with checkbox
function drawUserInDropdown($user_id)
{
    ?>
    <li> <label>
            <input type="checkbox" name="assigned-agents[]" value="<?php echo getUsername($user_id) ?>" <?php
               if (isset($_GET['filter'])) {
                   if (str_contains($_GET['filter'], getUsername($user_id)))
                       echo "checked";
               }
               ?>>
            <span class="li-username">
                <?php echo getUsername($user_id) ?>
            </span>
        </label></li>
    <?php
}

// Draws status name (from $status_id) with checkbox
function drawStatusInDropdown($status_id)
{
    ?>
    <li> <label>
            <input type="checkbox" name="status[]" value="<?php echo getStatusName($status_id) ?>" <?php
               if (isset($_GET['filter'])) {
                   if (str_contains($_GET['filter'], getStatusName($status_id)))
                       echo "checked";
               } ?>
               >
                <span class="li-status">
            <?php echo getStatusFromStatusId($status_id) ?>
            </span>
        </label></li>
    <?php
}

function drawPriorityInDropDown()
{
    ?>
    <li> <label>
            <input type="checkbox" name="priorities[]" value="Low" <?php
            if (isset($_GET['filter'])) {
                if (str_contains($_GET['filter'], "Low"))
                    echo "checked";
            } ?>>
            <span class="li-priority">Low</span>
        </label></li>
    <li> <label>
            <input type="checkbox" name="priorities[]" value="Medium" <?php
            if (isset($_GET['filter'])) {
                if (str_contains($_GET['filter'], "Medium"))
                    echo "checked";
            } ?>>
            <span class="li-priority">Medium</span>
        </label></li>
    <li> <label>
            <input type="checkbox" name="priorities[]" value="High" <?php
            if (isset($_GET['filter'])) {
                if (str_contains($_GET['filter'], "High"))
                    echo "checked";
            } ?>>
            <span class="li-priority">High</span>
        </label></li>
    <?php
}

function drawTagInDropdown($hashtag_id)
{
    ?>
    <li> <label>
            <input type="checkbox" name="hashtags[]" value="<?php echo getTagFromTagId($hashtag_id) ?>" <?php
               if (isset($_GET['filter'])) {
                   if (str_contains($_GET['filter'], ltrim(getTagFromTagId($hashtag_id), '#')))
                       echo "checked";
               } ?>>
            <span class="li-tag">
                <?php echo getTagFromTagId($hashtag_id) ?>
            </span>
        </label></li>
    <?php
}

function drawClearFilter()
{

}