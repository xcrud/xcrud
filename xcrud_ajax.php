<?php
/**
 * @package    xCrud Reload v1.0
 *
 * @copyright  (C) 2024 Open Source Matters, Inc. <https://www.xcrud.me>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
include "xcrud.php";
header("Content-Type: text/html; charset=" . Xcrud_config::$mbencoding);
echo Xcrud::get_requested_instance();
