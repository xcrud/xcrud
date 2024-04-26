<?php
/**
 * @package    xCrud Reload v1.0
 *
 * @copyright  (C) 2024 Open Source Matters, Inc. <https://www.xcrud.me>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
function publish_action($xcrud)
{
    if ($xcrud->get("primary")) {
        $db = Xcrud_db::get_instance();
        $query =
            'UPDATE base_fields SET `bool` = b\'1\' WHERE id = ' .
            (int) $xcrud->get("primary");
        $db->query($query);
    }
}
function unpublish_action($xcrud)
{
    if ($xcrud->get("primary")) {
        $db = Xcrud_db::get_instance();
        $query =
            'UPDATE base_fields SET `bool` = b\'0\' WHERE id = ' .
            (int) $xcrud->get("primary");
        $db->query($query);
    }
}

function exception_example($postdata, $primary, $xcrud)
{
    // get random field from $postdata
    $postdata_prepared = array_keys($postdata->to_array());
    shuffle($postdata_prepared);
    $random_field = array_shift($postdata_prepared);
    // set error message
    $xcrud->set_exception($random_field, "This is a test error", "error");
}

function test_column_callback($value, $fieldname, $primary, $row, $xcrud)
{
    return $value . " - nice!";
}

function after_upload_example($field, $file_name, $file_path, $params, $xcrud)
{
    $ext = trim(strtolower(strrchr($file_name, ".")), ".");
    if ($ext != "pdf" && $field == "uploads.simple_upload") {
        unlink($file_path);
        $xcrud->set_exception("simple_upload", "This is not PDF", "error");
    }
}

function movetop($xcrud)
{
    if ($xcrud->get("primary") !== false) {
        $primary = (int) $xcrud->get("primary");
        $db = Xcrud_db::get_instance();
        $query =
            "SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`";
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = [];
        foreach ($result as $key => $item) {
            if ($item["officeCode"] == $primary && $key != 0) {
                array_splice($result, $key - 1, 0, [$item]);
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item) {
            $query =
                "UPDATE `offices` SET `ordering` = " .
                $key .
                " WHERE officeCode = " .
                $item["officeCode"];
            $db->query($query);
        }
    }
}
function movebottom($xcrud)
{
    if ($xcrud->get("primary") !== false) {
        $primary = (int) $xcrud->get("primary");
        $db = Xcrud_db::get_instance();
        $query =
            "SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`";
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = [];
        foreach ($result as $key => $item) {
            if ($item["officeCode"] == $primary && $key != $count - 1) {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, [$item]);
                break;
            }
        }

        foreach ($result as $key => $item) {
            $query =
                "UPDATE `offices` SET `ordering` = " .
                $key .
                " WHERE officeCode = " .
                $item["officeCode"];
            $db->query($query);
        }
    }
}

function show_description($value, $fieldname, $primary_key, $row, $xcrud)
{
    $result = "";
    if ($value == "1") {
        $result = '<i class="fa fa-check" />' . "OK";
    } elseif ($value == "2") {
        $result = '<i class="fa fa-circle-o" />' . "Pending";
    }
    return $result;
}

function custom_field($value, $fieldname, $primary_key, $row, $xcrud)
{
    return '<input type="text" readonly class="xcrud-input" name="' .
        $xcrud->fieldname_encode($fieldname) .
        '" value="' .
        $value .
        '" />';
}
function unset_val($postdata)
{
    $postdata->del("Paid");
}

function format_phone($new_phone)
{
    $new_phone = preg_replace("/[^0-9]/", "", $new_phone);

    if (strlen($new_phone) == 7) {
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $new_phone);
    } elseif (strlen($new_phone) == 10) {
        return preg_replace(
            "/([0-9]{3})([0-9]{3})([0-9]{4})/",
            "($1) $2-$3",
            $new_phone
        );
    } else {
        return $new_phone;
    }
}
