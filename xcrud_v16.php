<?php
/**
 * @package    xCrud f0ska v1.6-*
 *
 * @copyright  (C) 2024 Open Source Matters, Inc. <https://www.xcrud.me>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */


// debug enabled, great for debug and development, you can remove this two lines for production app
error_reporting(E_ALL); // error reporting (debug)
ini_set('display_errors', 'On'); // error showing (debug)

// direct access to DB driver and config

define('XCRUD_PATH', str_replace('\\', '/', dirname(__file__))); // str_replace - windows trick




require (XCRUD_PATH . '/xcrud_config.php'); // configuration
require (XCRUD_PATH . '/xcrud_db.php'); // database class
mb_internal_encoding(Xcrud_config::$mbencoding); // sets multibyte encoding globaly
date_default_timezone_set(@date_default_timezone_get()); // xcrud code not depends on timezone, but this can fix some warnings


class Xcrud
{
    private $demo_mode = false;
    protected static $instance = array();
    protected static $css_loaded = false;
    protected static $js_loaded = false;
    protected static $classes = array();
    protected $ajax_request = false;
    public $instance_name;
    protected $instance_count;
    protected $table;
    protected $table_name;
    protected $primary_key; // name of primary
    protected $primary_val; // value of a primary
    protected $where = array();
    protected $order_by = array();
    protected $relation = array();
    protected $fields = array();
    protected $fields_create = array();
    protected $fields_edit = array();
    protected $fields_view = array();
    protected $fields_list = array();
    protected $fields_names = array();
    protected $labels = array();
    protected $columns = array();
    protected $columns_names = array();

    protected $is_create = true;
    protected $is_edit = true;
    protected $is_view = true;
    protected $is_remove = true;
    protected $is_csv = true;
    protected $is_search = true;
    protected $is_print = true;
    protected $is_title = true;
    protected $is_numbers = true;
    protected $is_duplicate = false;
    protected $is_inner = false;
    protected $is_pagination = true;
    protected $is_limitlist = true;
    protected $is_sortable = true;
    protected $is_list = true;

    protected $buttons_position = 'right';
    protected $buttons = array();
    protected $readonly = array();
    protected $disabled = array();
    protected $validation_required = array();
    protected $validation_pattern = array();
    protected $before_insert = array();
    protected $before_update = array();
    protected $before_remove = array();
    protected $after_insert = array();
    protected $after_update = array();
    protected $after_remove = array();
    protected $field_type = array();
    protected $field_attr = array();
    protected $defaults = array();
    protected $limit = 20;
    protected $limit_list = array(
        '20',
        '50',
        '100',
        'all');
    protected $column_cut = 50;
    protected $column_cut_list = array();
    protected $no_editor = array();
    protected $show_primary_ai_field = false;
    protected $show_primary_ai_column = false;
    protected $url;
    protected $key;

    protected $benchmark = false;
    protected $search_pattern = array('%', '%');
    protected $connection = false;
    protected $start_minimized = false;
    protected $remove_confirm = false;
    protected $upload_folder = array();
    protected $upload_config = array();
    protected $upload_folder_def = '../uploads';
    protected $upload_to_save = array();
    protected $upload_to_remove = array();
    protected $binary = array();
    protected $pass_var = array();
    protected $reverse_fields = array();
    protected $no_quotes = array();
    protected $join = array();
    protected $inner_where = array();
    protected $inner_table_instance = array();
    protected $condition = array();
    protected $theme = 'default';
    protected $unique = array();
    protected $fk_relation = array();

    protected $links_label = array();
    protected $emails_label = array();
    public $sum = array();
    protected $alert_create;
    protected $alert_edit;
    protected $subselect = array();
    protected $subselect_before = array();

    protected $highlight = array();
    protected $highlight_row = array();
    protected $modal = array();
    protected $column_class = array();
    protected $no_select = array(); // only subselect flag for correct sorting
    protected $primary_ai = false;

    protected $language = 'en';

    protected static $lang_arr = array();

    protected $subselect_query = array();
    protected $where_pri = array();
    protected $field_params = array();
    protected $mass_alert_create = array();
    protected $mass_alert_edit = array();
    protected $column_callback = array();
    protected $field_callback = array();
    protected $replace_insert = array();
    protected $replace_update = array();
    protected $replace_remove = array();
    protected $send_external_create = array();
    protected $send_external_edit = array();
    protected $locked_fields = array(); // disallow save data in form fields
    protected $column_pattern = array();
    protected $field_tabs = array();
    protected $field_marker = array();
    protected $field_tooltip = array();
    protected $table_tooltip = array();
    protected $column_tooltip = array();
    protected $search_columns = array();
    protected $search_default = null;
    protected $column_width = array();

    protected $order_column = null;
    protected $order_direct = 'asc';
    protected $result_list = array(); // DB grid result becomes glodal
    protected $result_row = array(); // DB details result becomes glodal
    protected $result_total = 0;

    protected $is_get = false;
    protected $after = null;

    protected $table_info = null; // fields information from database

    protected $before_upload = array();
    protected $after_upload = array();
    protected $after_resize = array();
    protected $custom_vars = array();
    protected $tabdesc = array();
    public $column_name = array();
    public $search = 0;

    protected $hidden_columns = array(); // allows to select non in grid data
    protected $hidden_fields = array(); // allows save data in non in form fields
    protected $range = '';
    protected $task = '';
    protected $column = false;
    protected $phrase = '';
    protected $inner_value = false;
    protected $fields_output = array();
    protected $hidden_fields_output = array();
    protected $start = 0;
    protected $before = '';
    protected $bit_field = array();
    protected $point_field = array();
    protected $float_field = array();
    protected $text_field = array();
    protected $int_field = array();
    protected $grid_condition = array(); // ***** remove *****
    protected $hide_button = array();
    protected $set_lang = array();
    public $table_ro = false;
    protected $load_view = array(
        'list' => 'xcrud_list_view.php',
        'create' => 'xcrud_detail_view.php',
        'edit' => 'xcrud_detail_view.php',
        'view' => 'xcrud_detail_view.php');
    protected $grid_restrictions = array();
    protected $direct_select_tags = array(); // get unselectable {tags}
    protected $action = array();

    protected $exception = false;
    protected $exception_fields = array();
    protected $exception_text = '';
    protected $message = array();

    protected $nested_rendered = array();
    protected $default_tab = false;
    protected $prefix = '';
    protected $query = '';
    protected $total_query = '';

    protected $condition_backup = array();
    protected static $sess_id = null;
    protected $is_rtl = true;

    protected $strip_tags = true;
    protected $safe_output = true;

    protected $before_list = array();
    protected $before_create = array();
    protected $before_edit = array();
    protected $before_view = array();

    protected $lists_null_opt = true;
    protected $custom_fields = array();

    protected $date_format = array();

    protected $cancel_file_saving = false;


    /** constructor, sets basic xcrud vars (they can be changed by public pethods) */
    protected function __construct()
    {
        Xcrud_config::$scripts_url = self::check_url(Xcrud_config::$scripts_url, true);
        Xcrud_config::$editor_url = self::check_url(Xcrud_config::$editor_url);
        Xcrud_config::$editor_init_url = self::check_url(Xcrud_config::$editor_init_url);

        $this->limit = Xcrud_config::$limit;
        $this->limit_list = Xcrud_config::$limit_list;
        $this->column_cut = Xcrud_config::$column_cut;
        $this->show_primary_ai_field = Xcrud_config::$show_primary_ai_field;
        $this->show_primary_ai_column = Xcrud_config::$show_primary_ai_column;

        $this->benchmark = Xcrud_config::$benchmark;
        $this->start_minimized = Xcrud_config::$start_minimized;
        $this->remove_confirm = Xcrud_config::$remove_confirm;
        $this->upload_folder_def = Xcrud_config::$upload_folder_def;

        $this->theme = Xcrud_config::$theme;
        $this->is_print = Xcrud_config::$enable_printout;
        $this->is_title = Xcrud_config::$enable_table_title;
        $this->is_csv = Xcrud_config::$enable_csv_export;
        $this->is_numbers = Xcrud_config::$enable_numbers;
        $this->is_pagination = Xcrud_config::$enable_pagination;
        $this->is_search = Xcrud_config::$enable_search;
        $this->is_limitlist = Xcrud_config::$enable_limitlist;
        $this->is_sortable = Xcrud_config::$enable_sorting;


        $this->language = Xcrud_config::$language;

        $this->search_pattern = Xcrud_config::$search_pattern;

        $this->demo_mode = Xcrud_config::$demo_mode;

        $this->default_tab = Xcrud_config::$default_tab;
        $this->prefix = Xcrud_config::$dbprefix;
        $this->is_rtl = Xcrud_config::$is_rtl;

        $this->strip_tags = Xcrud_config::$strip_tags;
        $this->safe_output = Xcrud_config::$safe_output;

        $this->lists_null_opt = Xcrud_config::$lists_null_opt;

        $this->date_format = array('php_d' => Xcrud_config::$php_date_format, 'php_t' => Xcrud_config::$php_time_format);
    }
    protected function __clone()
    {
    }
    public function __toString()
    {
        return $this->render();
    }
    public static function get_instance($name = false)
    {
        self::init_prepare();
        if (!$name)
            $name = sha1(rand() . microtime());
        if (!isset(self::$instance[$name]) || null === self::$instance[$name])
        {
            self::$instance[$name] = new self();
            self::$instance[$name]->instance_name = $name;
        }
        self::$instance[$name]->instance_count = count(self::$instance);
        return self::$instance[$name];
    }
    public static function get_requested_instance()
    {
        if (isset($_POST['xcrud']['instance']) && isset($_POST['xcrud']['key']) && isset($_POST['xcrud']['task']))
        {
            self::init_prepare('post');
            $key = $_POST['xcrud']['key'] ? $_POST['xcrud']['key'] : self::error('Security key cannot be empty');
            $inst_name = $_POST['xcrud']['instance'] ? $_POST['xcrud']['instance'] : self::error('Instance name cannot be empty');
            $is_get = false;
        }
        elseif (isset($_GET['xcrud']['instance']) && isset($_GET['xcrud']['key']) && isset($_GET['xcrud']['task']) && $_GET['xcrud']['task'] ==
            'file')
        {
            self::init_prepare('get');
            $key = $_GET['xcrud']['key'] ? $_GET['xcrud']['key'] : self::error('Security key cannot be empty');
            $inst_name = $_GET['xcrud']['instance'] ? $_GET['xcrud']['instance'] : self::error('Instance name cannot be empty');
            $is_get = true;
        }
        else
        {
            self::error('Wrong request!');
        }

        if (isset($_SESSION['lists']['xcrud_session'][$inst_name]['key']) && $_SESSION['lists']['xcrud_session'][$inst_name]['key'] ==
            $key)
        {
            self::$instance[$inst_name] = new self();
            self::$instance[$inst_name]->is_get = $is_get;
            self::$instance[$inst_name]->ajax_request = true;
            self::$instance[$inst_name]->instance_name = $inst_name;
            self::$instance[$inst_name]->import_vars($key);
            self::$instance[$inst_name]->inner_where();
            return self::$instance[$inst_name]->render();
        }
        else
            self::error('<strong>The verification key is out of date</strong><br />
                This means that your browser cached a previous version of this page with an old key (for security reasons the verification key is generated every request)<br />
                Why? Maybe you pressed the back button in your browser or opened a bookmark from last session. <br /><strong>Just reload the page, nothing happened :)</strong>');
    }
    protected static function init_prepare($method = false)
    {
        switch ($method)
        {
            case 'post':
                $sess_name = (Xcrud_config::$dynamic_session && isset($_POST['xcrud']['sess_name']) && $_POST['xcrud']['sess_name']) ? $_POST['xcrud']['sess_name'] :
                    Xcrud_config::$sess_name;
                break;
            case 'get':
                $sess_name = (Xcrud_config::$dynamic_session && isset($_GET['xcrud']['sess_name']) && $_GET['xcrud']['sess_name']) ? $_GET['xcrud']['sess_name'] :
                    Xcrud_config::$sess_name;
                break;
            default:
                $sess_name = Xcrud_config::$sess_name;
                break;
        }
        self::session_start($sess_name);
        if (is_callable(Xcrud_config::$before_construct))
        {
            call_user_func(Xcrud_config::$before_construct);
        }
    }

    public static function session_start($sess_name = false)
    {
        if (!$sess_name)
        {
            $sess_name = Xcrud_config::$sess_name;
        }
        if (!session_id() && !Xcrud_config::$external_session && !Xcrud_config::$alt_session)
        {
            if (!headers_sent())
            {
                session_name($sess_name);
                session_cache_expire(Xcrud_config::$sess_expire);
                session_set_cookie_params(0, '/');
                session_start();
            }
            else
                self::error('xCRUD can not create session, because the output is already sent into browser.
                Try to define xCRUD instance before the output start or use session_start() at the beginning of your script');
        }
        if (Xcrud_config::$alt_session)
        {
            if (!headers_sent())
            {
                if (!isset($_COOKIE[$sess_name]))
                {
                    self::$sess_id = base_convert(str_replace(' ', '', microtime()) . rand(), 10, 36);
                }
                else
                {
                    self::$sess_id = $_COOKIE[$sess_name];
                }
                setcookie($sess_name, self::$sess_id, time() + Xcrud_config::$alt_lifetime * 60, '/');
            }
            else
                self::error('xCRUD can not start session, because the output is already sent into browser.
                Try to define xCRUD instance before the output start or use <strong>Xcrud::session_start();</strong> at the beginning of your script');
        }
    }

    public function connection($user = '', $pass = '', $table = '', $host = 'localhost', $encode = 'utf8')
    {
        if ($user && $table)
        {
            $this->connection = array(
                $user,
                $pass,
                $table,
                $host,
                $encode);
        }
        return $this;
    }
    public function start_minimized($bool = true)
    {
        $this->start_minimized = (bool)$bool;
        return $this;
    }
    public function remove_confirm($bool = true)
    {
        $this->remove_confirm = (bool)$bool;
        return $this;
    }

    public function theme($theme = 'default')
    {
        $this->theme = $theme;
    }
    public function limit($limit = 20)
    {
        $this->limit = $limit;
        return $this;
    }
    public function limit_list($limit_list = '')
    {
        if ($limit_list)
        {
            if (is_array($limit_list))
                $this->limit_list = array_unique($limit_list);
            else
            {
                $this->limit_list = array_unique($this->parse_comma_separated($limit_list));
            }
        }
        return $this;
    }

    public function show_primary_ai_field($bool = true)
    {
        $this->show_primary_ai_field = (bool)$bool;
        return $this;
    }
    public function show_primary_ai_column($bool = true)
    {
        $this->show_primary_ai_column = (bool)$bool;
        return $this;
    }

    public function table($table = '', $prefix = false)
    {
        if ($prefix !== false)
        {
            $this->prefix = $prefix;
        }
        $this->table = $this->prefix . $table;
        return $this;
    }
    public function table_name($name = '', $tooltip = false, $icon = false)
    {
        if ($name)
            $this->table_name = $name;
        if ($tooltip)
        {
            $this->table_tooltip = array('tooltip' => $tooltip, 'icon' => $icon);
        }
        return $this;
    }
    public function where($fields = false, $where_val = false, $glue = 'AND', $index = false)
    {

        if ($fields && $where_val !== false)
        {
            $fdata = $this->_parse_field_names($fields, 'where');
            foreach ($fdata as $fitem)
            {
                if ($index)
                {
                    $this->where[$index] = array(
                        'table' => $fitem['table'],
                        'field' => $fitem['field'],
                        'value' => isset($fitem['value']) ? $fitem['value'] : $where_val,
                        'glue' => $glue);
                }
                else
                {
                    $this->where[] = array(
                        'table' => $fitem['table'],
                        'field' => $fitem['field'],
                        'value' => isset($fitem['value']) ? $fitem['value'] : $where_val,
                        'glue' => $glue);
                }
            }
            unset($fields, $fdata);
        }
        elseif ($fields && !is_array($fields) && $where_val === false)
        {
            if ($index)
            {
                $this->where[$index] = array('custom' => $fields, 'glue' => $glue);
            }
            else
            {
                $this->where[] = array('custom' => $fields, 'glue' => $glue);
            }
            unset($fields);
        }
        elseif (!$fields && $where_val)
        {
            if ($index)
            {
                $this->where[$index] = array('custom' => $where_val, 'glue' => $glue);
            }
            else
            {
                $this->where[] = array('custom' => $where_val, 'glue' => $glue);
            }
            unset($where_val);
        }
        elseif (!$fields && !$where_val && $index && isset($this->where[$index]))
        {
            unset($this->where[$index]);
        }
        return $this;
    }
    public function or_where($fields = '', $where_val = false)
    {
        return $this->where($fields = '', $where_val = '', 'OR');
    }
    public function order_by($fields = '', $direction = 'asc')
    {
        if ($fields)
        {
            if ($direction === false && is_string($fields))
            {
                $this->order_by[$fields] = false;
            }
            else
            {
                $fdata = $this->_parse_field_names($fields, 'order_by');
                foreach ($fdata as $key => $fitem)
                {
                    $this->order_by[$key] = isset($fitem['value']) ? $fitem['value'] : $direction;
                }
            }
        }
        unset($fields);
        return $this;
    }
    public function relation($fields = '', $rel_tbl = '', $rel_field = '', $rel_name = '', $rel_where = array(), $order_by = false,
        $multi = false, $rel_concat_separator = ' ', $tree = false, $depend_field = '', $depend_on = '')
    {
        if ($fields && $rel_tbl && $rel_field && $rel_name)
        {
            if ($depend_on)
            {
                $fdata = $this->_parse_field_names($depend_on, 'relation');
                $depend_on = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            }
            $fdata = $this->_parse_field_names($fields, 'relation');
            foreach ($fdata as $fitem)
            {
                $this->relation[$fitem['table'] . '.' . $fitem['field']] = array(
                    'rel_tbl' => $rel_tbl,
                    'rel_alias' => 'alias' . rand(),
                    'rel_field' => $rel_field,
                    'rel_name' => $rel_name,
                    'rel_where' => $rel_where,
                    'rel_separator' => $rel_concat_separator,
                    'order_by' => $order_by,
                    'multi' => $multi,
                    'table' => $fitem['table'],
                    'field' => $fitem['field'],
                    'tree' => $tree,
                    'depend_field' => $depend_field,
                    'depend_on' => $depend_on);
            }
        }
        return $this;
    }

    public function fk_relation($label = '', $fields = '', $fk_table = '', $in_fk_field = '', $out_fk_field = '', $rel_tbl =
        '', $rel_field = '', $rel_name = '', $rel_where = array(), $rel_orderby = '', $rel_concat_separator = ' ', $before = '',
        array $add_data = array())
    {
        if ($fields && $rel_tbl && $rel_field && $rel_name && $label)
        {
            $fdata = $this->_parse_field_names($fields, 'fk_relation');
            $fitem = reset($fdata);
            $table = $this->_get_table('subselect');
            //foreach ($fdata as $key => $fitem)
            //{
            //$alias = 'tfkalias' . base_convert(rand(), 10, 36);
            $alias = $table . '.' . $label;
            $this->fk_relation[$alias] = array(
                'table' => $fitem['table'],
                'field' => $fitem['field'],
                'label' => $label,
                'before' => $before ? key(reset($this->_parse_field_names($before, 'fk_relation'))) : '',
                'alias' => $alias,
                'rel_alias' => 'ralias' . rand(),
                'fk_alias' => 'fkalias' . rand(),
                'fk_table' => $fk_table,
                'in_fk_field' => $in_fk_field,
                'out_fk_field' => $out_fk_field,
                'rel_tbl' => $rel_tbl,
                'rel_field' => $rel_field,
                'rel_name' => $rel_name,
                'rel_where' => $rel_where,
                'rel_orderby' => $rel_orderby,
                'rel_separator' => $rel_concat_separator,
                'add_data' => $add_data);
            $this->field_type[$alias] = 'fk_relation';
            $this->defaults[$alias] = '';
            if (!isset($this->field_attr[$alias]))
            {
                $this->field_attr[$alias] = array();
            }

            //}
        }
        return $this;
    }
    public function join($fields = '', $join_tbl = '', $join_field = '', $alias = false, $not_insert = false)
    {
        $fdata = $this->_parse_field_names($fields, 'join');
        $alias = $alias ? $alias : $join_tbl;
        $key = key($fdata);
        $this->join[$alias] = array(
            'table' => $fdata[$key]['table'],
            'field' => $fdata[$key]['field'],
            'join_table' => $this->prefix . $join_tbl,
            'join_field' => $join_field,
            'not_insert' => $not_insert);
        //$this->field_type[$this->join[$alias]['join_table'] . '.' . $this->join[$alias]['join_field']] = 'hidden';
        $this->pass_var($alias . '.' . $join_field, '{' . $key . '}', 'edit');
        return $this;
    }
    /** nested table constructor */
    public function nested_table($instance_name = '', $field = '', $inner_tbl = '', $tbl_field = '')
    {
        if ($instance_name && $field && $inner_tbl && $tbl_field)
        {
            $fdata = $this->_parse_field_names($field, 'nested_table');
            foreach ($fdata as $fitem)
            {
                $this->inner_table_instance[$instance_name] = $fitem['table'] . '.' . $fitem['field']; // name of nested object will be stored in parent instance
                $instance = Xcrud::get_instance($instance_name); // just another xcrud object
                $instance->table($this->prefix . $inner_tbl);
                $instance->is_inner = true; // nested flag

                $fdata2 = $this->_parse_field_names($tbl_field, 'nested_table', $inner_tbl);

                $instance->inner_where[$fitem['table'] . '.' . $fitem['field']] = key($fdata2); // this connects nested table with parent
                return $instance; // only one cycle
            }
        }
    }

    public function fields($fields = '', $reverse = false, $tabname = false, $mode = false)
    {
        $fdata = $this->_parse_field_names($fields, 'fields');
        switch ($mode)
        {
            case 'create':
                if (!$reverse && $tabname)
                {
                    $this->field_tabs['create'][$tabname] = $tabname;
                }
                break;
            case 'edit':
                if (!$reverse && $tabname)
                {
                    $this->field_tabs['edit'][$tabname] = $tabname;
                }
                break;
            case 'view':
                if (!$reverse && $tabname)
                {
                    $this->field_tabs['view'][$tabname] = $tabname;
                }
                break;
            default:
                if (!$reverse && $tabname)
                {
                    $this->field_tabs['create'][$tabname] = $tabname;
                    $this->field_tabs['edit'][$tabname] = $tabname;
                    $this->field_tabs['view'][$tabname] = $tabname;
                }
                break;
        }
        foreach ($fdata as $fitem)
        {
            $fitem['tab'] = $tabname;
            switch ($mode)
            {
                case 'create':
                    $this->fields_create[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->reverse_fields['create'] = $reverse;
                    break;
                case 'edit':
                    $this->fields_edit[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->reverse_fields['edit'] = $reverse;
                    break;
                case 'view':
                    $this->fields_view[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->reverse_fields['view'] = $reverse;
                    break;
                default:
                    $this->fields_create[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->fields_edit[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->fields_view[$fitem['table'] . '.' . $fitem['field']] = $fitem;
                    $this->reverse_fields['create'] = $reverse;
                    $this->reverse_fields['edit'] = $reverse;
                    $this->reverse_fields['view'] = $reverse;
                    break;
            }
        }
        unset($fields, $fdata);
        return $this;
    }
    public function unique($fields = '')
    {
        $fdata = $this->_parse_field_names($fields, 'unique');
        foreach ($fdata as $fitem)
        {
            $this->unique[$fitem['table'] . '.' . $fitem['field']] = $fitem;
        }
        unset($fields);
        return $this;
    }
    public function label($fields = '', $label = '')
    {
        $fdata = $this->_parse_field_names($fields, 'label');
        foreach ($fdata as $fitem)
        {
            $this->labels[$fitem['table'] . '.' . $fitem['field']] = isset($fitem['value']) ? $fitem['value'] : $label;
        }
        return $this;
    }
    public function columns($columns = '', $reverse = false)
    {
        $fdata = $this->_parse_field_names($columns, 'columns');
        foreach ($fdata as $fitem)
        {
            $this->fields_list[$fitem['table'] . '.' . $fitem['field']] = $fitem;
        }
        $this->reverse_fields['list'] = $reverse;
        unset($columns);
        return $this;
    }
    public function unset_add($bool = true)
    {
        $this->is_create = !(bool)$bool;
        return $this;
    }
    public function unset_edit($bool = true, $field = false, $operand = false, $value = false)
    {
        $this->is_edit = !(bool)$bool;
        if ($field && $operand && $value !== false)
        {
            $this->grid_restrictions['edit'] = array(
                'field' => $field,
                'operator' => $operand,
                'value' => $value);
        }
        return $this;
    }
    public function unset_view($bool = true, $field = false, $operand = false, $value = false)
    {
        $this->is_view = !(bool)$bool;
        if ($field && $operand && $value !== false)
        {
            $this->grid_restrictions['view'] = array(
                'field' => $field,
                'operator' => $operand,
                'value' => $value);
        }
        return $this;
    }
    public function unset_remove($bool = true, $field = false, $operand = false, $value = false)
    {
        $this->is_remove = !(bool)$bool;
        if ($field && $operand && $value !== false)
        {
            $this->grid_restrictions['remove'] = array(
                'field' => $field,
                'operator' => $operand,
                'value' => $value);
        }
        return $this;
    }
    public function duplicate_button($bool = true, $field = false, $operand = false, $value = false)
    {
        $this->is_duplicate = (bool)$bool;
        if ($field && $operand && $value !== false)
        {
            $this->grid_restrictions['duplicate'] = array(
                'field' => $field,
                'operator' => $operand,
                'value' => $value);
        }
        return $this;
    }

    public function unset_csv($bool = true)
    {
        $this->is_csv = !(bool)$bool;
        return $this;
    }
    public function unset_print($bool = true)
    {
        $this->is_print = !(bool)$bool;
        return $this;
    }
    public function unset_title($bool = true)
    {
        $this->is_title = !(bool)$bool;
        return $this;
    }
    public function unset_numbers($bool = true)
    {
        $this->is_numbers = !(bool)$bool;
        return $this;
    }

    public function unset_search($bool = true)
    {
        $this->is_search = !(bool)$bool;
        return $this;
    }
    public function unset_limitlist($bool = true)
    {
        $this->is_limitlist = !(bool)$bool;
        return $this;
    }
    public function unset_pagination($bool = true)
    {
        $this->is_pagination = !(bool)$bool;
        return $this;
    }
    public function unset_sortable($bool = true)
    {
        $this->is_sortable = !(bool)$bool;
        return $this;
    }
    public function unset_list($bool = true)
    {
        $this->is_list = !(bool)$bool;
        return $this;
    }

    public function button($link = '', $name = '', $icon = '', $class = '', $parameters = array(), $condition = array())
    {
        if ($link)
        {
            $this->buttons[] = array(
                'link' => $link,
                'name' => $name,
                'icon' => $icon,
                'class' => $class,
                'params' => (array )$parameters);
        }
        if ($condition && is_array($condition) && count($condition) == 3 && $name)
        {
            list($field, $operator, $value) = $condition;
            $this->grid_restrictions[$name] = array(
                'field' => $field,
                'operator' => $operator,
                'value' => $value);
        }
        return $this;
    }
    public function change_type($fields = '', $type = '', $default = false, $attr = array())
    {
        if ($type)
        {
            $fdata = $this->_parse_field_names($fields, 'change_type');
            foreach ($fdata as $fitem)
            {

                switch ($type)
                {
                    case 'file':
                    case 'image':
                        $this->upload_config[$fitem['table'] . '.' . $fitem['field']] = $attr;
                        break;
                    case 'price':
                        $def_attr = array(
                            'max' => 10,
                            'decimals' => 2,
                            'separator' => ',',
                            'prefix' => '',
                            'suffix' => '',
                            'point' => '.');
                        $this->field_attr[$fitem['table'] . '.' . $fitem['field']] = array_merge($def_attr, (array )$attr);
                        break;
                    case 'select':
                    case 'multiselect':
                    case 'radio':
                    case 'checkboxes':
                        if (!is_array($attr) or !isset($attr['values']))
                        {
                            $this->field_attr[$fitem['table'] . '.' . $fitem['field']]['values'] = $attr;
                        }
                        else
                        {
                            $this->field_attr[$fitem['table'] . '.' . $fitem['field']] = $attr;
                        }
                        break;
                    case 'point':
                        //$this->field_attr[$fitem['table'] . '.' . $fitem['field']] = $map_attr;
                        $def_attr = array( // defaults
                            'text' => Xcrud_config::$default_text,
                            'search_text' => Xcrud_config::$default_search_text,
                            'zoom' => Xcrud_config::$default_zoom,
                            'width' => Xcrud_config::$default_width,
                            'height' => Xcrud_config::$default_height,
                            'search' => Xcrud_config::$default_coord,
                            'coords' => Xcrud_config::$default_search);
                        $this->field_attr[$fitem['table'] . '.' . $fitem['field']] = array_merge($def_attr, (array )$attr);
                        break;
                    case 'remote_image':
                        if (is_array($attr) && !isset($attr['link']))
                        {
                            $attr['link'] = '';
                        }
                        elseif (is_string($attr))
                        {
                            $attr = array('link' => $attr);
                        }
                        $this->field_attr[$fitem['table'] . '.' . $fitem['field']] = $attr;
                    default:
                        if ($attr && !is_array($attr))
                        {
                            $attr = array('maxlength' => (int)$attr);
                        }
                        $this->field_attr[$fitem['table'] . '.' . $fitem['field']] = $attr;
                        break;
                }
                $this->field_type[$fitem['table'] . '.' . $fitem['field']] = $type;
                $this->defaults[$fitem['table'] . '.' . $fitem['field']] = $default;
            }
        }
        return $this;
    }
    public function create_field($fields = '', $type = '', $default = false, $attr = array())
    {
        $fdata = $this->_parse_field_names($fields, 'create_field');
        foreach ($fdata as $fkey => $fitem)
        {
            $this->custom_fields[$fkey] = $fitem;
        }
        return $this->change_type($fields, $type, $default, $attr);
    }
    public function pass_default($fields = '', $value = '')
    {
        $fdata = $this->_parse_field_names($fields, 'pass_default');
        foreach ($fdata as $fitem)
        {
            $this->defaults[$fitem['table'] . '.' . $fitem['field']] = isset($fitem['value']) ? $fitem['value'] : $value;
        }
        return $this;
    }
    public function pass_var($fields = '', $value = '', $type = 'all', $eval = false)
    {
        $fdata = $this->_parse_field_names($fields, 'pass_var');
        $type = str_replace(' ', '', $type);
        $types = $this->parse_comma_separated($type);
        foreach ($fdata as $fitem)
        {
            $findex = $fitem['table'] . '.' . $fitem['field'];
            $pass_var = array(
                'table' => $fitem['table'],
                'field' => $fitem['field'],
                'value' => isset($fitem['value']) ? $fitem['value'] : $value,
                'eval' => $eval);
            foreach ($types as $tp)
            {
                if ($tp == 'all')
                {
                    $this->pass_var['create'][$findex] = $pass_var;
                    $this->pass_var['edit'][$findex] = $pass_var;
                    $this->pass_var['view'][$findex] = $pass_var;
                    break;
                }
                elseif ($tp == 'create' || $tp == 'edit' || $tp == 'view')
                {
                    $this->pass_var[$tp][$findex] = $pass_var;
                }
            }
        }
        return $this;
    }
    public function no_quotes($fields = '')
    {
        $fdata = $this->_parse_field_names($fields, 'no_quotes');
        foreach ($fdata as $fkey => $fitem)
        {
            $this->no_quotes[$fkey] = true;
        }
        return $this;
    }
    public function sum($fields = '', $class = '', $custom_text = '')
    {
        $fdata = $this->_parse_field_names($fields, 'sum');
        foreach ($fdata as $fkey => $fitem)
        {
            $this->sum[$fkey] = array(
                'table' => $fitem['table'],
                'column' => $fitem['field'],
                'class' => isset($fitem['value']) ? $fitem['value'] : $class,
                'custom' => $custom_text);
        }
        return $this;
    }
    public function readonly_on_create($field = '')
    {
        return $this->readonly($field, 'create');
    }
    public function disabled_on_create($field = '')
    {
        return $this->disabled($field, 'create');
    }
    public function readonly_on_edit($field = '')
    {
        return $this->readonly($field, 'edit');
    }
    public function disabled_on_edit($field = '')
    {
        return $this->disabled($field, 'edit');
    }
    public function readonly($fields = '', $mode = false) // needs to be re-written
    {
        $fdata = $this->_parse_field_names($fields, 'readonly');
        foreach ($fdata as $key => $fitem)
        {
            $this->readonly[$key] = $this->parse_mode($mode);
        }
        return $this;
    }
    public function disabled($fields = '', $mode = false)
    {
        $fdata = $this->_parse_field_names($fields, 'disabled');
        foreach ($fdata as $key => $fitem)
        {
            $this->disabled[$key] = $this->parse_mode($mode);
        }
        return $this;
    }
    public function condition($fields = '', $operator = '', $value = '', $method = '', $params = array(), $mode = false)
    {
        if ($fields && $method && $operator)
        {
            $fdata = $this->_parse_field_names($fields, 'condition');
            foreach ($fdata as $key => $fitem)
            {
                $this->condition[] = array(
                    'field' => $key,
                    'value' => $value,
                    'operator' => $operator,
                    'method' => $method,
                    'params' => (array )$params,
                    'mode' => $this->parse_mode($mode));
            }
        }
        return $this;
    }
    public function instance_name()
    {
        return $this->instance_name;
    }
    public function benchmark($bool = true)
    {
        $this->benchmark = (bool)$bool;
        return $this;
    }
    public function column_cut($int = 50, $fields = false, $safe_output = false)
    {
        if ($fields === false)
        {
            $this->column_cut = (int)$int ? (int)$int : false;
            $this->safe_output = $safe_output;
        }
        else
        {
            $fdata = $this->_parse_field_names($fields, 'column_cut');
            foreach ($fdata as $fitem)
            {
                $this->column_cut_list[$fitem['table'] . '.' . $fitem['field']] = array('count' => $int, 'safe' => $safe_output);
            }
        }
        return $this;
    }
    public function links_label($text = '')
    {
        if ($text)
        {
            $this->links_label['text'] = trim($text);
        }
        return $this;
    }
    public function emails_label($text = '')
    {
        if ($text)
        {
            $this->emails_label['text'] = trim($text);
        }
        return $this;
    }

    public function no_editor($fields = '')
    {
        $fdata = $this->_parse_field_names($fields, 'no_editor');
        foreach ($fdata as $fitem)
        {
            $this->no_editor[$fitem['table'] . '.' . $fitem['field']] = true;
        }
        return $this;
    }
    public function validation_required($fields = '', $chars = 1)
    {
        $fdata = $this->_parse_field_names($fields, 'validation_required');
        foreach ($fdata as $fitem)
        {
            $this->validation_required[$fitem['table'] . '.' . $fitem['field']] = isset($fitem['value']) ? $fitem['value'] : $chars;
        }
        return $this;
    }
    public function validation_pattern($fields = '', $pattern = '')
    {
        $fdata = $this->_parse_field_names($fields, 'validation_pattern');
        foreach ($fdata as $fitem)
        {
            $this->validation_pattern[$fitem['table'] . '.' . $fitem['field']] = isset($fitem['value']) ? $fitem['value'] : $pattern;
        }
        return $this;
    }
    public function alert($column = '', $cc = '', $subject = '', $message = '', $link = false, $field = false, $value = false,
        $mode = 'all')
    {

        if ($cc)
        {
            if (!is_array($cc))
                $cc = $this->parse_comma_separated($cc);
        }
        if ($mode == 'all' or $mode == 'create')
            $this->alert_create[] = array(
                'column' => $column,
                'cc' => $cc,
                'subject' => $subject,
                'message' => $message,
                'link' => $link,
                'field' => $field,
                'value' => $value);
        if ($mode == 'all' or $mode == 'edit')
            $this->alert_edit[] = array(
                'column' => $column,
                'cc' => $cc,
                'subject' => $subject,
                'message' => $message,
                'link' => $link,
                'field' => $field,
                'value' => $value);
        return $this;
    }
    public function alert_create($column = '', $cc = '', $subject = '', $message = '', $link = false, $field = false, $value = false)
    {
        return $this->alert($column, $cc, $subject, $message, $link, $field, $value, 'create');
    }
    public function alert_edit($column = '', $cc = '', $subject = '', $message = '', $link = false, $field = false, $value = false)
    {
        return $this->alert($column, $cc, $subject, $message, $link, $field, $value, 'edit');
    }

    // NEEDS TO BE REWRITTEN
    public function mass_alert($email_table = '', $email_column = '', $emeil_where = '', $subject = '', $message = '', $link = false,
        $field = false, $value = false, $mode = 'all')
    {
        $table = $this->_get_table('mass_alert');
        $field = $this->table . '.' . $field;
        if ($mode == 'all' or $mode == 'create')
            $this->mass_alert_create[] = array(
                'email_table' => $email_table,
                'email_column' => $email_column,
                'where' => $emeil_where,
                'subject' => $subject,
                'message' => $message,
                'link' => $link,
                'field' => $field,
                'value' => $value,
                'table' => $table);
        if ($mode == 'all' or $mode == 'edit')
            $this->mass_alert_edit[] = array(
                'email_table' => $email_table,
                'email_column' => $email_column,
                'where' => $emeil_where,
                'subject' => $subject,
                'message' => $message,
                'link' => $link,
                'field' => $field,
                'value' => $value,
                'table' => $table);

        return $this;
    }
    public function mass_alert_create($email_table = '', $email_column = '', $emeil_where = '', $subject = '', $message = '',
        $link = false, $field = false, $value = false)
    {
        return $this->mass_alert($email_table, $email_column, $emeil_where, $subject, $message, $link, $field, $value, 'create');
    }
    public function mass_alert_edit($email_table = '', $email_column = '', $emeil_where = '', $subject = '', $message = '',
        $link = false, $field = false, $value = false)
    {
        return $this->mass_alert($email_table, $email_column, $emeil_where, $subject, $message, $link, $field, $value, 'edit');
    }
    public function send_external($path, $data = array(), $method = 'include', $mode = 'all', $where_field = '', $where_val =
        '')
    {
        if ($where_field)
        {
            $fdata = $this->_parse_field_names($where_field, 'send_external');
            $where_field = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
        }
        if ($mode == 'all' or $mode == 'create')
            $this->send_external_create = array(
                'data' => $data,
                'path' => $path,
                'method' => $method,
                'where_field' => $where_field,
                'where_val' => $where_val);
        if ($mode == 'all' or $mode == 'edit')
            $this->send_external_edit = array(
                'data' => $data,
                'path' => $path,
                'method' => $method,
                'where_field' => $where_field,
                'where_val' => $where_val);
        return $this;
    }
    public function page_call($url = '', $data = array(), $where_param = '', $where_value = '', $method = 'get')
    {
        return $this->send_external($url, $data, $method, 'all', $where_param, $where_value);
    }
    public function page_call_create($url = '', $data = array(), $where_param = '', $where_value = '', $method = 'get')
    {
        return $this->send_external($url, $data, $method, 'create', $where_param, $where_value);
    }
    public function page_call_edit($url = '', $data = array(), $where_param = '', $where_value = '', $method = 'get')
    {
        return $this->send_external($url, $data, $method, 'edit', $where_param, $where_value);
    }
    public function subselect($column_name = '', $sql = '', $before = false)
    {
        if ($column_name && $sql)
        {
            $table = $this->_get_table('subselect');
            $column_alias = $table . '.' . $column_name;
            if ($before)
            {
                $fdata = $this->_parse_field_names($before, 'subselect');
                $before = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            }
            $this->subselect[$column_alias] = $sql;
            $this->subselect_before[$column_alias] = $before;
            //$this->no_select[$column_alias] = true;
            $this->labels[$column_alias] = $column_name;
            $this->field_type[$column_alias] = 'none';
            $this->defaults[$column_alias] = '';
        }
        return $this;
    }
    public function highlight($columns = '', $operator = '', $value = '', $color = '', $class = '')
    {
        if ($columns && $operator)
        {
            $fdata = $this->_parse_field_names($columns, 'highlight');
            foreach ($fdata as $fitem)
            {
                $this->highlight[$fitem['table'] . '.' . $fitem['field']][] = array(
                    'value' => $value,
                    'operator' => $operator,
                    'color' => $color,
                    'class' => $class);
            }
        }
        return $this;
    }
    public function highlight_row($columns = '', $operator = '', $value = '', $color = '', $class = '')
    {
        if ($columns && $operator)
        {
            $fdata = $this->_parse_field_names($columns, 'highlight_row');
            foreach ($fdata as $fitem)
            {
                $this->highlight_row[] = array(
                    'field' => $fitem['table'] . '.' . $fitem['field'],
                    'value' => $value,
                    'operator' => $operator,
                    'color' => $color,
                    'class' => $class);
            }
        }
        return $this;
    }
    public function modal($columns = '', $icon = false)
    {
        $fdata = $this->_parse_field_names($columns, 'modal');
        foreach ($fdata as $fitem)
        {
            $this->modal[$fitem['table'] . '.' . $fitem['field']] = isset($fitem['value']) ? $fitem['value'] : $icon;
        }
        return $this;
    }
    public function column_class($columns = '', $class = '')
    {
        $fdata = $this->_parse_field_names($columns, 'column_class');
        foreach ($fdata as $fitem)
        {
            $this->column_class[$fitem['table'] . '.' . $fitem['field']][] = isset($fitem['value']) ? $fitem['value'] : $class;
        }
        return $this;
    }
    public function language($lang = 'en')
    {
        $this->language = $lang;
        return $this;
    }
    public function field_tooltip($fields = '', $tooltip = '', $icon = false)
    {
        if ($fields && $tooltip)
        {
            $fdata = $this->_parse_field_names($fields, 'column_class');
            foreach ($fdata as $fitem)
            {
                $this->field_tooltip[$fitem['table'] . '.' . $fitem['field']] = array('tooltip' => isset($fitem['value']) ? $fitem['value'] :
                        $tooltip, 'icon' => $icon);
            }
        }
        return $this;
    }

    public function search_columns($fields = false, $default = false)
    {
        if ($fields)
        {
            $fdata = $this->_parse_field_names($fields, 'search_columns');
            foreach ($fdata as $fkey => $fitem)
            {
                $this->search_columns[$fkey] = $fitem;
            }
        }
        if ($default !== false)
        {
            if ($default == '')
            {
                $this->search_default = false;
            }
            else
            {
                $fdata = $this->_parse_field_names($default, 'search_columns');
                $this->search_default = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            }
        }
        return $this;
    }
    public function column_width($fields = '', $width = '')
    {
        if ($fields && $width)
        {
            $fdata = $this->_parse_field_names($fields, 'column_width');
            foreach ($fdata as $fitem)
            {
                $this->column_width[$fitem['table'] . '.' . $fitem['field']] = $width;
            }
        }
        return $this;
    }
    public function before_insert($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_insert['callable'] = $callable;
            $this->before_insert['path'] = $path;
        }
        return $this;
    }
    public function before_update($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_update['callable'] = $callable;
            $this->before_update['path'] = $path;
        }
        return $this;
    }
    public function before_remove($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_remove['callable'] = $callable;
            $this->before_remove['path'] = $path;
        }
        return $this;
    }
    public function after_insert($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->after_insert['callable'] = $callable;
            $this->after_insert['path'] = $path;
        }
        return $this;
    }
    public function after_update($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->after_update['callable'] = $callable;
            $this->after_update['path'] = $path;
        }
        return $this;
    }
    public function after_remove($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->after_remove['callable'] = $callable;
            $this->after_remove['path'] = $path;
        }
        return $this;
    }
    public function after_upload($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->after_upload['callable'] = $callable;
            $this->after_upload['path'] = $path;
        }
        return $this;
    }
    public function before_upload($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_upload['callable'] = $callable;
            $this->before_upload['path'] = $path;
        }
        return $this;
    }
    public function column_callback($fields = '', $callback = '', $path = 'functions.php')
    {
        if ($fields && $callback && $path)
        {
            $fdata = $this->_parse_field_names($fields, 'column_callback');
            foreach ($fdata as $fitem)
            {
                $this->column_callback[$fitem['table'] . '.' . $fitem['field']] = array(
                    'name' => $fitem['table'] . '.' . $fitem['field'],
                    'path' => rtrim($path, '/'),
                    'callback' => $callback);
            }
        }
        return $this;
    }
    public function field_callback($fields = '', $callback = '', $path = 'functions.php')
    {
        if ($fields && $callback && $path)
        {
            $fdata = $this->_parse_field_names($fields, 'field_callback');
            foreach ($fdata as $fitem)
            {
                $this->field_callback[$fitem['table'] . '.' . $fitem['field']] = array(
                    'name' => $fitem['table'] . '.' . $fitem['field'],
                    'path' => rtrim($path, '/'),
                    'callback' => $callback);
            }
        }
        return $this;
    }

    public function replace_insert($callable = '', $path = 'functions.php')
    {
        if ($callable)
        {
            $this->replace_insert = array('callable' => $callable, 'path' => $path);
        }
        return $this;
    }
    public function replace_update($callable = '', $path = 'functions.php')
    {
        if ($callable)
        {
            $this->replace_update = array('callable' => $callable, 'path' => $path);
        }
        return $this;
    }
    public function replace_remove($callable = '', $path = 'functions.php')
    {
        if ($callable)
        {
            $this->replace_remove = array('callable' => $callable, 'path' => $path);
        }
        return $this;
    }
    public function before_list($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_list['callable'] = $callable;
            $this->before_list['path'] = $path;
        }
        return $this;
    }
    public function before_create($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_create['callable'] = $callable;
            $this->before_create['path'] = $path;
        }
        return $this;
    }
    public function before_edit($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_edit['callable'] = $callable;
            $this->before_edit['path'] = $path;
        }
        return $this;
    }
    public function before_view($callable = '', $path = 'functions.php')
    {
        if ($callable && $path)
        {
            $this->before_view['callable'] = $callable;
            $this->before_view['path'] = $path;
        }
        return $this;
    }
    public function call_update($postdata, $primary)
    {
        if (!$this->task)
        {
            self::error('Sorry, but you must use <strong>call_update()</strong> only in callbacks');
        }
        return $this->_update($postdata->to_array(), $primary);
    }
    public function set_var($name = null, $value = null)
    {
        if ($name)
        {
            $this->custom_vars[$name] = $value;
        }
        return $this;
    }
    public function get_var($name = null)
    {
        if ($name)
        {
            return isset($this->custom_vars[$name]) ? $this->custom_vars[$name] : false;
        }
        else
        {
            return false;
        }
    }
    public function unset_var($name)
    {
        if (isset($this->custom_vars[$name]))
        {
            unset($this->custom_vars[$name]);
        }
        return $this;
    }

    public function column_name($fields = '', $text = '')
    {
        $fdata = $this->_parse_field_names($fields, 'column_name');
        foreach ($fdata as $fitem)
        {
            $this->column_name[$fitem['table'] . '.' . $fitem['field']] = $text;
        }
        unset($fields);
        return $this;
    }
    public function column_pattern($fields, $patern)
    {
        if ($fields && $patern)
        {
            $fdata = $this->_parse_field_names($fields, 'column_pattern');
            foreach ($fdata as $fkey => $fitem)
            {
                $this->column_pattern[$fkey] = $patern;
            }
        }
        return $this;
    }
    public function column_tooltip($fields = '', $tooltip = '', $icon = false)
    {
        if ($fields && $tooltip)
        {
            $fdata = $this->_parse_field_names($fields, 'column_tooltip');
            foreach ($fdata as $fkey => $fitem)
            {
                $this->column_tooltip[$fkey] = array('tooltip' => isset($fitem['value']) ? $fitem['value'] : $tooltip, 'icon' => $icon);
            }

        }
        return $this;
    }
    public function buttons_position($position = 'left')
    {
        switch ($position)
        {
            case 'left':
            case 'right':
            case 'none':
                $this->buttons_position = $position;
                break;
        }
        return $this;
    }
    public function hide_button($names = '')
    {
        foreach ($this->parse_comma_separated($names) as $name)
        {
            $this->hide_button[$name] = 1;
        }
        return $this;
    }
    public function set_lang($var = '', $translate = '')
    {
        if ($var)
        {
            $this->set_lang[$var] = $translate;
        }
        return $this;
    }

    public function search_pattern($left = '%', $right = '%')
    {
        $this->search_pattern = array($left, $right);
        return $this;
    }
    public function load_view($mode = '', $file = '')
    {
        if ($mode && $file)
        {
            switch ($mode)
            {
                case 'list':
                case 'create':
                case 'edit':
                case 'view':
                    $this->load_view[$mode] = $file;
                    break;
                default:
                    self::error('Incorrect mode.');
            }
        }
        return $this;
    }
    public function create_action($name = '', $callable = '', $path = 'functions.php')
    {
        if ($callable && $name)
        {
            $this->action[$name] = array('callable' => $callable, 'path' => $path);
        }
        return $this;
    }
    public function get($name = '')
    {
        if (!$this->task)
        {
            self::error('Sorry, but you must use <strong>get()</strong> only in callbacks');
        }
        if ($this->_get('key'))
        {
            return $this->_get($name);
        }
        else
        {
            return $this->_post($name);
        }
    }
    public function default_tab($name = false)
    {
        $this->default_tab = $name;
        return $this;
    }
    public function query($query = '')
    {
        $this->query = $query;
        return $this;
    }
    public function check()
    {
        $array = array();
        $phpvers = phpversion();
        $array['PHP version'] = array('value' => $phpvers, 'state' => ((int)$phpvers >= 5 ? 'passed' : 'error'));

    }
    public function set_attr($fields = '', array $attr = array())
    {
        if ($fields && $attr)
        {
            $fdata = $this->_parse_field_names($fields, 'set_attr');
            foreach ($fdata as $fkey => $fitem)
            {
                if (isset($this->field_attr[$fkey]))
                {
                    $this->field_attr[$fkey] = array_merge((array )$this->field_attr[$fkey], $attr);
                }
                else
                {
                    $this->field_attr[$fkey] = $attr;
                }
            }

        }
        return $this;
    }
    public function lists_null_opt($bool = true)
    {
        $this->lists_null_opt = $bool;
        return $this;
    }


    /** public renderer, final instance method */
    public function render($task = false, $primary = false)
    {
        $this->benchmark_start();
        $this->_receive_post($task, $primary);
        $this->_regenerate_key();
        $this->_remove_and_save_uploads();
        $this->_get_language();
        $this->_get_theme_config();
        if ($this->query)
        {
            return $this->render_custom_query_task();
        }
        $this->_get_table_info();
        return $this->_run_task();
    }

    /** main task trigger */
    protected function _run_task()
    {
        if ($this->after && $this->after == $this->task)
        {
            return self::error('Task recursion!');
        }
        if (!$this->task)
        {
            $this->task = 'list';
        }
        switch ($this->task)
        {
            case 'create':
                $this->_set_field_types('create');
                //$this->_sort_defaults();
                return $this->_create();
                break;
            case 'edit':
                $this->_set_field_types('edit');
                return $this->_entry('edit');
                break;
            case 'save':
                if (!$this->before)
                {
                    return self::error('Restricted task!');
                }
                $this->_set_field_types($this->before);
                return $this->_save();

                /*$this->task = $this->after;
                $this->after = null;
                return $this->_run_task();*/
                break;
            case 'remove':
                $this->_set_field_types('list');
                $this->_remove();
                return $this->_list();
                break;
            case 'upload':
                return $this->_upload();
                break;
            case 'remove_upload':
                return $this->_remove_upload();
                break;
            case 'crop_image':
                return $this->manual_crop();
                break;
            case 'unique':
                $this->_set_field_types('edit');
                return $this->_check_unique_value();
                break;
            case 'clone':
                $this->_set_field_types('list');
                $this->_clone_row();
                return $this->_list();
                break;
            case 'print':
                if (!$this->is_print)
                {
                    return self::error('Restricted');
                }
                $this->_set_field_types('list', Xcrud_config::$print_all_fields);
                $this->theme = 'printout';
                return $this->_list();
                break;
            case 'depend':
                return $this->create_relation($this->_post('name', false, 'base64'), $this->_post('value'), $this->get_field_attr($this->
                    _post('name', false, 'base64'), 'edit'), $this->_post('dependval'));
                break;
            case 'view':
                $this->_set_field_types('view');
                return $this->_entry('view');
                break;
            case 'query':

                break;
            case 'external':

                break;
            case 'action':
                return $this->_call_action();
                break;
            case 'file':
                $this->_set_field_types('list');
                return $this->_render_file();
                break;
            case 'csv':
                $this->_set_field_types('list', Xcrud_config::$csv_all_fields);
                return $this->_csv();
                break;
            case 'list':
            default:
                $this->_set_field_types('list');
                return $this->_list();
                break;
        }
    }
    protected function render_custom_query_task()
    {
        $this->is_edit = false;
        $this->is_remove = false;
        $this->is_create = false;
        $this->is_view = false;
        $this->is_search = false;
        switch ($this->task)
        {
            case 'print':
                if (!$this->is_print)
                {
                    return self::error('Restricted');
                }
                $this->theme = 'printout';
                $this->start = 0;
                $this->limit = 0;
                return $this->render_custom_datagrid();
                break;
            case 'action':
                return $this->_call_action();
                break;
            case 'csv':
                return $this->render_custom_csv();
                break;
            default:
                return $this->render_custom_datagrid();
                break;
        }
    }
    protected function render_custom_datagrid()
    {
        $query = $this->parse_query_params();
        $db = Xcrud_db::get_instance($this->connection);
        $db->query('SELECT COUNT(*) as `count` FROM (SELECT NULL' . $this->total_query . ') counts');
        $this->sum_row = $db->row();
        $this->result_total = $this->sum_row['count'];
        $order_by = $this->_build_order_by();
        $limit = $this->_build_limit($this->result_total);
        $db->query($query . ' ' . $order_by . ' ' . $limit);
        $this->result_list = $db->result();
        $this->columns = reset($this->result_list);
        unset($this->columns['primary_key']);
        foreach ($this->columns as $key => $tmp)
        {
            $this->columns[$key] = array('table' => '', 'field' => $key);
            if (!isset($this->field_type[$key]))
            {
                $this->field_type[$key] = 'text';
            }
        }
        $this->fields_list = $this->columns;
        $this->_set_column_names();
        if (!$this->table_name)
        {
            $this->table_name = '&nbsp;';
        }
        return $this->_render_list();
    }
    protected function render_custom_csv()
    {
        if (!$this->is_csv)
        {
            return self::error('Restricted');
        }
        $this->columns = $this->fields_list;
        $query = $this->parse_query_params();
        $db = Xcrud_db::get_instance($this->connection);
        $order_by = $this->_build_order_by();
        $this->_set_column_names();
        ini_set('auto_detect_line_endings', true);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-type: application/octet-stream");
        $table_name = $this->_clean_file_name(trim(html_entity_decode($this->table_name, ENT_QUOTES, 'utf-8')));
        header("Content-Disposition: attachment; filename=\"" . ($table_name ? $table_name : 'table') . ".csv\"");
        header("Content-Transfer-Encoding: binary");
        $output = fopen('php://output', 'w');
        fwrite($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // bom
        fputcsv($output, $this->columns_names, Xcrud_config::$csv_delimiter, Xcrud_config::$csv_enclosure);
        $db->query($query . ' ' . $order_by);
        while ($row = $db->result->fetch_assoc()) // low level result process, saves memory
        {
            $out = array();
            foreach ($this->columns as $field => $fitem)
            {
                $out[] = htmlspecialchars_decode(strip_tags($this->_render_export_item($field, $row[$field], $row['primary_key'], $row)),
                    ENT_QUOTES);
            }
            fputcsv($output, $out, Xcrud_config::$csv_delimiter, Xcrud_config::$csv_enclosure);
        }
    }
    protected function parse_query_params()
    {
        $query = preg_replace('/\s+/u', ' ', $this->query);
        $query = preg_replace('/[\)`\s]from[\(`\s]/ui', ' FROM ', $query);

        if (preg_match('/(limit([0-9\s\,]+)){1}$/ui', $query, $matches))
        {
            $query = str_ireplace($matches[0], '', $query);
            if (!$this->ajax_request)
            {
                $tmp = explode(',', $matches[2]);
                if (isset($tmp[1]))
                {
                    $this->start = (int)trim($tmp[0]);
                    $this->limit = (int)trim($tmp[1]);
                }
                else
                {
                    $this->start = 0;
                    $this->limit = (int)trim($tmp[0]);
                }
            }
        }
        if (preg_match('/(order\sby([^\(\)]+)){1}$/ui', $query, $matches))
        {
            $query = str_ireplace($matches[0], '', $query);
            if (!$this->ajax_request)
            {
                $tmp = explode(',', $matches[2]);
                foreach ($tmp as $item)
                {
                    $item = trim($item);
                    $direct = (mb_strripos($item, ' desc') == (mb_strlen($item) - 5) || mb_strripos($item, '`desc') == (mb_strlen($item) - 5)) ?
                        'desc' : 'asc';
                    $item = str_ireplace(array(
                        ' asc',
                        ' desc',
                        '`asc',
                        '`desc',
                        '`'), '', $item);
                    $this->order_by[$item] = $direct;
                }
            }
        }
        $tmp = preg_replace_callback('/\( (?> [^)(]+ | (?R) )+ \)/xui', array($this, 'query_params_callback'), $query);
        $from_pos = mb_strpos($tmp, ' FROM ');
        $this->total_query = mb_substr($query, $from_pos);
        $query = mb_substr($query, 0, $from_pos) . ',(0) AS `primary_key`' . mb_substr($query, $from_pos);
        return $query;
    }
    protected function query_params_callback($matches)
    {
        return preg_replace('/./Uui', '*', $matches[0]);
    }

    /** main output */
    protected function render_output()
    {
        if ($this->ajax_request)
        {
            $contents = $this->render_control_fields() . $this->data;
            $this->after_render();
            return $contents;
        }
        else
        {
            $contents = '';
            if (!self::$css_loaded && !Xcrud_config::$manual_load)
            {
                $contents .= self::load_css();
            }
            ob_start();
            include (XCRUD_PATH . '/' . Xcrud_config::$themes_path . '/' . $this->theme . '/xcrud_container.php');
            $contents .= ob_get_contents();
            ob_end_clean();
            unset($this->data);
            if (!self::$js_loaded && !Xcrud_config::$manual_load)
            {
                $contents .= self::load_js();
            }
            $this->after_render();
            return $contents;
        }
    }
    protected function after_render()
    {
        switch ($this->task)
        {
            case 'file':
            case 'depend':
            case 'print':
            case 'csv':
                break;
            default:
                if (self::$instance)
                {
                    foreach (self::$instance as $i)
                    {
                        $i->_export_vars();
                    }
                }
                break;
        }
        if (is_callable(Xcrud_config::$after_render))
        {
            call_user_func(Xcrud_config::$after_render);
        }
    }
    /** returns current view into main container */
    protected function render_view()
    {
        return $this->render_control_fields() . $this->data;
    }


    /** files and images rendering */
    protected function _render_file()
    {
        $field = str_replace('`', '', $this->_get('field'));
        if (!$field)
            exit();
        $thumb = $this->_get('thumb', false);
        $crop = (bool)$this->_get('crop', false);
        $settings = $this->upload_config[$field];
        $blob = false;

        $image = array_search($field, array_reverse($this->upload_to_save));
        if (!$image)
        {
            list($tmp1, $tmp2) = explode('.', $field);
            $db = Xcrud_db::get_instance($this->connection);

            $this->where_pri($this->primary_key, $this->primary_val);
            $where = $this->_build_where();
            $table_join = $this->_build_table_join();

            $db = Xcrud_db::get_instance($this->connection);
            $db->query("SELECT `$tmp1`.`$tmp2`\r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}\r\n LIMIT 1");
            $row = $db->row();
            $image = $row[$tmp2];
            if (isset($this->upload_config[$field]['blob']) && $this->upload_config[$field]['blob'] === true)
            {
                $blob = true;
            }
            else
            {
                if ($thumb !== false)
                {
                    if (isset($settings['thumbs'][$thumb]))
                    {
                        $thumb_set = $settings['thumbs'][$thumb];
                        $path = $this->get_thumb_path($image, $field, $thumb_set);
                    }
                    else
                    {
                        $folder = $this->get_image_folder($field);
                        $path = $folder . '/' . $image;
                    }

                }
                else
                {
                    $folder = $this->get_image_folder($field);
                    $path = $folder . '/' . $image;
                }
                //$image = ($thumb ? substr_replace($image, $marker, strrpos($image, '.'), 0) : $image);
                //$path = $this->check_folder($folder, 'render_image') . '/' . $image;
                if (!is_file($path))
                {
                    header("HTTP/1.0 404 Not Found");
                    exit('Not Found');
                }
                //$output = file_get_contents($path);
            }
        }
        else
        {
            //$folder = $this->upload_folder[$field];
            if ($crop)
            {
                $folder = $this->get_image_folder($field);
                $tmp_filename = substr($image, 0, strrpos($image, '.')) . '.tmp';
                $path = $folder . '/' . $tmp_filename;
            }
            elseif ($thumb !== false)
            {
                $thumb_set = $settings['thumbs'][$thumb];
                $path = $this->get_thumb_path($image, $field, $thumb_set);
            }
            else
            {
                $folder = $this->get_image_folder($field);
                $path = $folder . '/' . $image;
            }
            //$image = ($thumb ? substr_replace($image, $marker, strrpos($image, '.'), 0) : $image);
            //$path = $this->check_folder($folder, 'render_image') . '/' . $image;
            if (!is_file($path))
            {
                header("HTTP/1.0 404 Not Found");
                exit('Not Found');
            }
            //$output = file_get_contents($path);
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        if ($this->field_type[$field] == 'image' && !$blob)
        {
            $size = getimagesize($path);
            switch ($size[2])
            {
                case 1:
                    header("Content-type: image/gif");
                    break;
                case 2:
                    header("Content-type: image/jpeg");
                    break;
                case 3:
                    header("Content-type: image/png");
                    break;
            }
        }
        elseif ($blob && $this->field_type[$field] == 'image')
        {
            header("Content-type: image/jpeg");
        }
        elseif ($blob)
        {
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"" . (isset($settings['filename']) ? $settings['filename'] :
                'binary_data') . "\"");
            header("Content-Transfer-Encoding: binary");
        }
        else
        {
            if (trim(strtolower(strrchr($path, '.')), '.') == 'pdf')
            {
                header("Content-type: application/pdf");
                header("Content-Disposition: inline; filename=\"" . (isset($settings['filename']) ? $settings['filename'] : $image) . "\"");
            }
            else
            {
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"" . (isset($settings['filename']) ? $settings['filename'] : $image) .
                    "\"");
            }

            header("Content-Transfer-Encoding: binary");
        }
        if ($blob)
            header("Content-Length: " . strlen($image));
        else
            header("Content-Length: " . filesize($path));
        @ob_clean();
        flush();
        if ($blob)
        {
            return $image;
        }
        else
        {
            readfile($path);
        }
        exit();
    }
    public function _csv()
    {
        if (!$this->is_csv)
        {
            return self::error('Restricted');
        }
        $db = Xcrud_db::get_instance($this->connection);
        $select = $this->_build_select_list(true);
        $table_join = $this->_build_table_join();
        $where = $this->_build_where();
        $order_by = $this->_build_order_by();
        $this->_set_column_names();
        $headers = array();
        foreach ($this->columns as $field => $fitem)
        {
            if (isset($this->field_type[$field]) && ($this->field_type[$field] == 'password' or $this->field_type[$field] ==
                'hidden'))
                continue;
            $headers[] = $this->columns_names[$field];
        }
        ini_set('auto_detect_line_endings', true);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $this->_clean_file_name($this->table_name ? $this->table_name :
            $this->table) . ".csv\"");
        header("Content-Transfer-Encoding: binary");
        $output = fopen('php://output', 'w');
        fwrite($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // bom
        fputcsv($output, $headers, Xcrud_config::$csv_delimiter, Xcrud_config::$csv_enclosure);
        $db->query("SELECT {$select} FROM `{$this->table}` {$table_join} {$where} {$order_by}");
        while ($row = $db->result->fetch_assoc()) // low level result process, saves memory
        {
            $out = array();
            foreach ($this->columns as $field => $fitem)
            {
                if (isset($this->field_type[$field]) && ($this->field_type[$field] == 'password' or $this->field_type[$field] ==
                    'hidden'))
                    continue;
                $out[] = htmlspecialchars_decode(strip_tags($this->_render_export_item($field, $row[$field], $row['primary_key'], $row)),
                    ENT_QUOTES);
            }
            fputcsv($output, $out, Xcrud_config::$csv_delimiter, Xcrud_config::$csv_enclosure);
        }
    }
    /** returns request variable */
    protected function _post($field = '', $default = false, $filter = false)
    {
        if (isset($_POST['xcrud'][$field]))
        {

            if (Xcrud_config::$auto_xss_filtering)
            {
                $xss = $this->load_core_class('xss');
            }
            else
            {
                $xss = false;
            }
            if (($field == 'postdata' or $field == 'unique') && $_POST['xcrud'][$field])
            {
                $data_keys = array_keys($_POST['xcrud'][$field]);
                foreach ($data_keys as $k => $key)
                {
                    $data_keys[$k] = $xss ? $xss->xss_clean($this->fieldname_decode($key)) : $this->fieldname_decode($key);
                    if ($xss)
                    {
                        $_POST['xcrud'][$field][$key] = $xss->xss_clean($_POST['xcrud'][$field][$key]);
                    }
                }
                return array_combine($data_keys, $_POST['xcrud'][$field]);
            }
            elseif ($filter)
            {
                switch ($filter)
                {
                    case 'key':
                        return str_replace('`', '', $xss ? $xss->xss_clean($_POST['xcrud'][$field]) : $_POST['xcrud'][$field]);
                        break;
                    case 'int':
                        return (int)$_POST['xcrud'][$field];
                        break;
                    case 'trim':
                        return trim($xss ? $xss->xss_clean($_POST['xcrud'][$field]) : $_POST['xcrud'][$field]);
                        break;
                    case 'base64':
                        return $xss ? $xss->xss_clean($this->fieldname_decode($_POST['xcrud'][$field])) : $this->fieldname_decode($_POST['xcrud'][$field]);
                        break;
                    default:
                        return $xss ? $xss->xss_clean($_POST['xcrud'][$field]) : $_POST['xcrud'][$field];
                        break;
                }
            }
            else
            {
                return $xss ? $xss->xss_clean($_POST['xcrud'][$field]) : $_POST['xcrud'][$field];
            }
        }
        else
            return $default;
    }
    protected function _get($field = '', $default = false, $filter = false)
    {
        if (isset($_GET['xcrud'][$field]))
        {

            if (Xcrud_config::$auto_xss_filtering)
            {
                $xss = $this->load_core_class('xss');
            }
            else
            {
                $xss = false;
            }
            if ($filter)
            {
                switch ($filter)
                {
                    case 'key':
                        return str_replace('`', '', $xss ? $xss->xss_clean($_GET['xcrud'][$field]) : $_GET['xcrud'][$field]);
                        break;
                    case 'int':
                        return (int)$_GET['xcrud'][$field];
                        break;
                    case 'trim':
                        return trim($xss ? $xss->xss_clean($_GET['xcrud'][$field]) : $_GET['xcrud'][$field]);
                        break;
                    default:
                        return $xss ? $xss->xss_clean($_GET['xcrud'][$field]) : $_GET['xcrud'][$field];
                        break;
                }
            }
            else
            {
                return $xss ? $xss->xss_clean($_GET['xcrud'][$field]) : $_GET['xcrud'][$field];
            }
        }
        else
            return $default;
    }

    protected function stripslashes_callback(&$item, $key)
    {
        $item = stripslashes($item);
    }


    /** creates fieldlist for adding record */
    protected function _create($postdata = array())
    {
        if (!$this->is_create || $this->table_ro)
            return self::error('Forbidden');

        $this->primary_val = null;
        $this->result_row = array_merge($this->defaults, $postdata);

        if ($this->before_create)
        {
            $path = $this->check_file($this->before_create['path'], 'before_create');
            include_once ($path);
            if (is_callable($this->before_create['callable']))
            {
                $postdata = new Xcrud_postdata($this->result_row, $this);
                call_user_func_array($this->before_create['callable'], array($postdata, $this));
                $this->result_row = $postdata->to_array();
            }
        }

        $this->_set_field_names();

        /** conditions process */
        if ($this->condition)
        {
            foreach ($this->condition as $params)
            {
                if (!isset($params['mode']['create']))
                    continue;

                $params['value'] = $this->replace_text_variables($params['value'], $this->result_row);
                if (array_key_exists($params['field'], $this->result_row) && $this->_compare($this->result_row[$params['field']], $params['operator'],
                    $params['value']))
                {
                    if (is_array($params['method']) && is_callable($params['method']))
                    {
                        call_user_func_array($params['method'], $params['params']);
                    }
                    elseif (is_callable(array($this, $params['method'])))
                    {
                        $this->condition_backup($params['method']);
                        call_user_func_array(array($this, $params['method']), $params['params']);
                    }
                    elseif (is_callable($params['method']))
                    {
                        call_user_func_array($params['method'], $params['params']);
                    }
                }
            }
        }
        return $this->_render_details('create');
    }
    /** creates fieldlist for editing or viewing record */
    protected function _entry($mode = 'edit', $postdata = array())
    {
        $this->where_pri($this->primary_key, $this->primary_val);
        $select = $this->_build_select_details($mode);
        $where = $this->_build_where();
        $table_join = $this->_build_table_join();

        $db = Xcrud_db::get_instance($this->connection);
        $db->query("SELECT {$select}\r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}\r\n LIMIT 1");
        $this->result_row = array_merge((array )$db->row(), $postdata);

        // moved here to support conditions for buttons
        if (((!$this->is_edit($this->result_row) || $this->table_ro) && $mode == 'edit') or (!$this->is_view($this->result_row) &&
            $mode == 'view'))
            return self::error('Forbidden');

        $callback_method = 'before_' . $mode;
        if ($this->{$callback_method})
        {
            $path = $this->check_file($this->{$callback_method}['path'], $callback_method);
            include_once ($path);
            if (is_callable($this->{$callback_method}['callable']))
            {
                $postdata = new Xcrud_postdata($this->result_row, $this);
                call_user_func_array($this->{$callback_method}['callable'], array(
                    $postdata,
                    $this->primary_val,
                    $this));
                $this->result_row = $postdata->to_array();
            }
        }

        $this->_set_field_names();

        /** conditions process */
        if ($this->condition)
        {
            foreach ($this->condition as $params)
            {
                if (!isset($params['mode'][$mode]))
                    continue;

                $params['value'] = $this->replace_text_variables($params['value'], $this->result_row);
                if (isset($this->result_row[$params['field']]) && $this->_compare($this->result_row[$params['field']], $params['operator'],
                    $params['value']))
                {
                    if (is_array($params['method']) && is_callable($params['method']))
                    {
                        call_user_func_array($params['method'], $params['params']);
                    }
                    elseif (is_callable(array($this, $params['method'])))
                    {
                        $this->condition_backup($params['method']);
                        call_user_func_array(array($this, $params['method']), $params['params']);
                    }
                    elseif (is_callable($params['method']))
                    {
                        call_user_func_array($params['method'], $params['params']);
                    }
                }
            }
        }
        /** hidden fields pass_var_process **/
        if ($mode == 'edit' && isset($this->pass_var['edit']))
        {
            $data = array();
            foreach ($this->result_row as $key => $val)
            {
                if (!isset($this->fields[$key]))
                {

                    foreach ($this->pass_var['edit'] as $pkey => $param)
                    {
                        $data[$key] = $val;
                    }
                }
            }
            if ($data)
                $this->pass_var['edit'][$pkey]['tmp_value'] = $this->replace_text_variables($param['value'], $data);
        }

        return $this->_render_details($mode);
    }

    protected function prepare_query_field($val, $key, $action, $no_processing = false)
    {
        $db = Xcrud_db::get_instance($this->connection);
        if ($no_processing)
        {
            if (isset($this->no_quotes[$key]) && isset($this->pass_var[$action][$key]))
            {
                return $db->escape($val, true);
            }
            else
            {
                return $db->escape($val, false, $this->field_type[$key], $this->field_null[$key], isset($this->bit_field[$key]));
            }
        }
        else
        {
            if (is_array($val))
            {
                return $db->escape(implode(',', $val), false, $this->field_type[$key], $this->field_null[$key], isset($this->bit_field[$key]));
            }
            elseif (isset($this->point_field[$key]))
            {
                return 'Point(' . $db->escape($val, true, 'point', $this->field_null[$key], isset($this->bit_field[$key])) . ')';
            }
            elseif (isset($this->int_field[$key]))
            {
                return $db->escape($val, false, 'int', $this->field_null[$key], isset($this->bit_field[$key]));
            }
            elseif (isset($this->float_field[$key]) && $this->field_type[$key] == 'price')
            {
                $val = $this->cast_number_format($val, $key, true);
                return $db->escape($val, false, 'float', $this->field_null[$key], isset($this->bit_field[$key]));
            }
            else
                if (isset($this->no_quotes[$key]) && isset($this->pass_var[$action][$key]))
                {
                    return $db->escape($val, true);
                }
                else
                {
                    if ($this->field_type[$key] == 'price')
                    {
                        $val = $this->cast_number_format($val, $key, true);
                    }
                    return $db->escape($val, false, $this->field_type[$key], $this->field_null[$key], isset($this->bit_field[$key]));
                }
        }
    }

    /** main insert constructor */
    protected function _insert($postdata, $no_processing = false, $no_processing_fields = array())
    {
        if (!$postdata)
        {
            self::error('$postdata array is empty');
        }
        $set = array();
        $db = Xcrud_db::get_instance($this->connection);
        $fields = array_merge($this->fields, $this->hidden_fields);
        $fk_queries = array();
        foreach ($postdata as $key => $val)
        {
            if (isset($fields[$key]) && !isset($this->locked_fields[$key]) && !isset($this->custom_fields[$key]))
            {
                if (isset($this->field_type[$key]))
                {
                    switch ($this->field_type[$key])
                    {
                        case 'password':
                            if (trim($val) == '')
                            {
                                continue 2;
                            }
                            elseif ($this->defaults[$key])
                            {
                                $val = hash($this->defaults[$key], $val);
                            }
                            break;
                        case 'fk_relation': //
                            continue 2;
                            break;
                    }
                }

                $set[$fields[$key]['table']]['`' . $fields[$key]['field'] . '`'] = $this->prepare_query_field($val, $key, 'create');

                /*if (is_array($val))
                {
                $set[$fields[$key]['table']]['`' . $fields[$key]['field'] . '`'] = $db->escape(implode(',', $val), false, $this->
                field_type[$key], $this->field_null[$key], isset($this->bit_field[$key]));
                }
                elseif (isset($this->point_field[$key]))
                {
                $set[$fields[$key]['table']]['`' . $fields[$key]['field'] . '`'] = 'Point(' . $db->escape($val, true, 'point', $this->
                field_null[$key], isset($this->bit_field[$key])) . ')';
                }
                elseif (isset($this->float_field[$key]))
                {

                }
                elseif (isset($this->float_field[$key]))
                {

                }
                else
                $set[$fields[$key]['table']]['`' . $fields[$key]['field'] . '`'] = ((isset($this->no_quotes[$key]) && isset($this->
                pass_var['create'][$key])) ? $db->escape($val, true) : $db->escape($val, false, $this->field_type[$key], $this->
                field_null[$key], isset($this->bit_field[$key])));*/
            }
            elseif ($no_processing)
            {
                /*$set[$no_processing_fields[$key]['table']]['`' . $no_processing_fields[$key]['field'] . '`'] = ((isset($this->no_quotes[$key]) &&
                isset($this->pass_var['create'][$key])) ? $db->escape($val, true) : $db->escape($val, false, $this->field_type[$key], $this->
                field_null[$key], isset($this->bit_field[$key])));*/
                $set[$no_processing_fields[$key]['table']]['`' . $no_processing_fields[$key]['field'] . '`'] = $this->
                    prepare_query_field($val, $key, 'create', true);
            }
        }
        //$keys = array_keys($set[$this->table]);
        if (!$set)
        {
            self::error('Nothing to insert');
        }
        if (!$this->primary_ai && !isset($postdata[$this->table . '.' . $this->primary_key]))
        {
            self::error('Can\'t insert a row. No primary value.');
        }
        if (!$this->demo_mode)
            $db->query('INSERT INTO `' . $this->table . '` (' . implode(',', array_keys($set[$this->table])) . ') VALUES (' .
                implode(',', $set[$this->table]) . ')');
        if ($this->primary_ai)
        {
            $ins_id = $db->insert_id();
            $set[$this->table]['`' . $this->primary_key . '`'] = $ins_id;
            $postdata[$this->table . '.' . $this->primary_key] = $ins_id;
        }
        else
        {
            $ins_id = $postdata[$this->table . '.' . $this->primary_key];
        }
        if ($this->join)
        {
            foreach ($this->join as $alias => $param)
            {
                $set[$alias]['`' . $param['join_field'] . '`'] = $set[$param['table']]['`' . $param['field'] . '`'];
                if (!$this->demo_mode && !$param['not_insert'])
                {
                    $db->query("INSERT INTO `{$param['join_table']}` (" . implode(',', array_keys($set[$alias])) . ") VALUES (" . implode(',',
                        $set[$alias]) . ")");
                }
            }
        }

        if ($this->fk_relation)
        {
            foreach ($this->fk_relation as $fk)
            {
                $field = $fk['table'] . '.' . $fk['field'];
                if (array_key_exists($fk['alias'], $postdata) && array_key_exists($field, $postdata))
                {
                    $in_val = $db->escape($postdata[$field], false, $this->field_type[$field], $this->field_null[$field], isset($this->
                        bit_field[$field]));
                    $db->query('DELETE FROM `' . $fk['fk_table'] . '` WHERE `' . $fk['in_fk_field'] . '` = ' . $in_val);
                    $fkids = $this->parse_comma_separated($postdata[$fk['alias']]);
                    if ($fkids)
                    {
                        $ins_vals = array();
                        $ins_keys = array();
                        $ins_add = array();
                        if ($fk['add_data'])
                        {
                            foreach ($fk['add_data'] as $add_key => $add_val)
                            {
                                $ins_keys[] = '`' . $add_key . '`';
                                $ins_add[] = $db->escape($add_val);
                            }
                        }
                        $ins_add[] = /*$db->escape(*/ $in_val /*)*/;
                        $ins_keys[] = '`' . $fk['in_fk_field'] . '`';
                        $ins_keys[] = '`' . $fk['out_fk_field'] . '`';
                        foreach ($fkids as $fkid)
                        {
                            $ins_vals[] = '(' . implode(',', $ins_add) . ',' . $db->escape($fkid) . ')';
                        }
                        $db->query('INSERT INTO `' . $fk['fk_table'] . '` (' . implode(',', $ins_keys) . ') VALUES ' . implode(',', $ins_vals));
                    }
                }
            }
        }

        unset($set, $postdata);
        return $ins_id;
    }

    protected function make_fk_remove($rel, $primary)
    {
        $db = Xcrud_db::get_instance($this->connection);
    }
    protected function make_fk_insert($rel, $val, $primary)
    {
        $db = Xcrud_db::get_instance($this->connection);
    }

    /** main update constructor */
    protected function _update($postdata, $primary)
    {
        if (!$postdata)
        {
            self::error('$postdata array is empty');
        }
        $res = false;
        $set = array();
        $db = Xcrud_db::get_instance($this->connection);
        $fields = array_merge($this->fields, $this->hidden_fields);
        foreach ($postdata as $key => $val)
        {
            if (isset($fields[$key]) && !isset($this->locked_fields[$key]) && !isset($this->custom_fields[$key]))
            {
                if (isset($this->field_type[$key]))
                {
                    switch ($this->field_type[$key])
                    {
                        case 'password':
                            if (trim($val) == '')
                            {
                                continue 2;
                            }
                            elseif ($this->defaults[$key])
                            {
                                $val = hash($this->defaults[$key], $val);
                            }
                            break;
                        case 'fk_relation': //
                            continue 2;
                            break;
                    }
                }
                /*
                if (is_array($val))
                {
                $set[] = '`' . $fields[$key]['table'] . '`.`' . $fields[$key]['field'] . '` = ' . $db->escape(implode(',', $val), false,
                $this->field_type[$key], $this->field_null[$key], isset($this->bit_field[$key]));
                }
                elseif (isset($this->point_field[$key]) && trim($val))
                {
                $set[] = '`' . $fields[$key]['table'] . '`.`' . $fields[$key]['field'] . '` = Point(' . $db->escape($val, true, 'point',
                $this->field_null[$key], isset($this->bit_field[$key])) . ')';
                }
                else
                $set[] = '`' . $fields[$key]['table'] . '`.`' . $fields[$key]['field'] . '` = ' . ((isset($this->no_quotes[$key]) &&
                isset($this->pass_var['edit'][$key])) ? $db->escape($val, true) : $db->escape(trim($val), false, $this->field_type[$key],
                $this->field_null[$key], isset($this->bit_field[$key])));
                */
                $set[] = '`' . $fields[$key]['table'] . '`.`' . $fields[$key]['field'] . '` = ' . $this->prepare_query_field($val, $key,
                    'edit');
            }
        }
        if (!$set)
        {
            self::error('Nothing to update');
        }
        if (!$this->join)
        {
            if (!$this->demo_mode)
                $res = $db->query("UPDATE `{$this->table}` SET " . implode(",\r\n", $set) . " WHERE `{$this->primary_key}` = " . $db->
                    escape($primary) . " LIMIT 1");
        }
        else
        {
            //$tables = array('`' . $this->table . '`');
            $joins = array();
            foreach ($this->join as $alias => $param)
            {
                //$tables[] = '`' . $alias . '`';
                $joins[] = "INNER JOIN `{$param['join_table']}` AS `{$alias}`
                    ON `{$param['table']}`.`{$param['field']}` = `{$alias}`.`{$param['join_field']}`";
            }
            if (!$this->demo_mode)
                $res = $db->query("UPDATE `{$this->table}` AS `{$this->table}` " . implode(' ', $joins) . " SET " . implode(",\r\n", $set) .
                    " WHERE `{$this->table}`.`{$this->primary_key}` = " . $db->escape($primary));
        }
        if (isset($postdata[$this->table . '.' . $this->primary_key]) && $res)
            $primary = $postdata[$this->table . '.' . $this->primary_key];
        else
        {
            $postdata[$this->table . '.' . $this->primary_key] = $primary;
        }

        if ($this->fk_relation)
        {
            foreach ($this->fk_relation as $fk)
            {
                $field = $fk['table'] . '.' . $fk['field'];
                if (array_key_exists($fk['alias'], $postdata) && array_key_exists($field, $postdata))
                {
                    $in_val = $db->escape($postdata[$field], false, $this->field_type[$field], $this->field_null[$field], isset($this->
                        bit_field[$field]));
                    $db->query('DELETE FROM `' . $fk['fk_table'] . '` WHERE `' . $fk['in_fk_field'] . '` = ' . $in_val . ' AND ' . $this->
                        _build_rel_ins_where($fk['alias']));
                    $fkids = $this->parse_comma_separated($postdata[$fk['alias']]);
                    if ($fkids)
                    {
                        $ins_vals = array();
                        $ins_keys = array();
                        $ins_add = array();
                        if ($fk['add_data'])
                        {
                            foreach ($fk['add_data'] as $add_key => $add_val)
                            {
                                $ins_keys[] = '`' . $add_key . '`';
                                $ins_add[] = $db->escape($add_val);
                            }
                        }
                        $ins_add[] = /*$db->escape(*/ $in_val /*)*/;
                        $ins_keys[] = '`' . $fk['in_fk_field'] . '`';
                        $ins_keys[] = '`' . $fk['out_fk_field'] . '`';
                        foreach ($fkids as $fkid)
                        {
                            $ins_vals[] = '(' . implode(',', $ins_add) . ',' . $db->escape($fkid) . ')';
                        }
                        $db->query('INSERT INTO `' . $fk['fk_table'] . '` (' . implode(',', $ins_keys) . ') VALUES ' . implode(',', $ins_vals));
                    }
                }
            }
        }

        unset($set, $postdata);
        return $primary;
    }

    /** main delete */
    protected function _remove()
    {
        $del = false;
        if ($this->table_ro)
            return self::error('Forbidden');
        if ($this->before_remove)
        {
            $path = $this->check_file($this->before_remove['path'], 'before_remove');
            include_once ($path);
            if (is_callable($this->before_remove['callable']))
            {
                call_user_func_array($this->before_remove['callable'], array($this->primary_val, $this));
                if ($this->exception)
                {
                    $this->task = 'list';
                    $this->primary_val = null;
                    return false;
                }
            }
        }
        if ($this->replace_remove)
        {
            $path = $this->check_file($this->replace_remove['path'], 'replace_remove');
            include_once ($path);
            if (is_callable($this->replace_remove['callable']))
            {
                $this->primary_val = call_user_func_array($this->replace_remove['callable'], array($this->primary_val, $this));
            }
        }
        else
        {
            // remove case
            $db = Xcrud_db::get_instance($this->connection);
            $del_row = array();
            $del = false;
            $fields = array();
            $this->find_details_text_variables();
            if ($this->direct_select_tags) // tags for unset condition
            {
                foreach ($this->direct_select_tags as $key => $dsf)
                {
                    $fields[$key] = "`{$dsf['table']}`.`{$dsf['field']}` AS `{$key}`";
                }
            }
            if (in_array('image', $this->field_type) or in_array('file', $this->field_type) or in_array('fk_relation', $this->
                field_type)) // images && fk
            {
                foreach ($this->field_type as $key => $type)
                {
                    switch ($type)
                    {
                        case 'image':
                        case 'file':
                            $tmp = explode('.', $key);
                            $fields[$key] = '`' . $tmp[0] . '`.`' . $tmp[1] . '` AS `' . $key . '`';
                            break;
                        case 'fk_relation':
                            $fields[$this->fk_relation[$key]['table'] . '.' . $this->fk_relation[$key]['field']] = '`' . $this->fk_relation[$key]['table'] .
                                '`.`' . $this->fk_relation[$key]['field'] . '` AS `' . $this->fk_relation[$key]['table'] . '.' . $this->fk_relation[$key]['field'] .
                                '`';
                            break;
                    }
                    if ($type == 'image' or $type == 'file')
                    {
                        $tmp = explode('.', $key);
                        $fields[$key] = "`{$tmp[0]}`.`{$tmp[1]}` AS `{$key}`";
                    }
                }
            }
            if (!$this->join)
            {
                if ($fields)
                {
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` WHERE `{$this->primary_key}` = " . $db->escape($this->
                        primary_val) . ' LIMIT 1');
                    $del_row = $db->row();
                }
                if (!$this->is_remove($del_row))
                    return self::error('Forbidden');
                if (!$this->demo_mode)
                    $del = $db->query("DELETE FROM `{$this->table}` WHERE `{$this->primary_key}` = " . $db->escape($this->primary_val) .
                        " LIMIT 1");
            }
            else
            {
                $tables = array('`' . $this->table . '`');
                $joins = array();
                foreach ($this->join as $alias => $param)
                {
                    if (!$param['not_insert'])
                    {
                        $tables[] = '`' . $alias . '`';
                    }
                    $joins[] = "INNER JOIN `{$param['join_table']}` AS `{$alias}`
                    ON `{$param['table']}`.`{$param['field']}` = `{$alias}`.`{$param['join_field']}`";
                }
                if ($fields)
                {
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` AS `{$this->table}` " . implode(' ', $joins) .
                        " WHERE `{$this->table}`.`{$this->primary_key}` = " . $db->escape($this->primary_val));
                    $del_row = $db->row();
                }
                if (!$this->is_remove($del_row))
                    return self::error('Forbidden');
                if (!$this->demo_mode)
                    $del = $db->query("DELETE " . implode(',', $tables) . " FROM `{$this->table}` AS `{$this->table}` " . implode(' ', $joins) .
                        " WHERE `{$this->table}`.`{$this->primary_key}` = " . $db->escape($this->primary_val));
            }
            if ($del_row && !$this->demo_mode)
            {
                foreach ($del_row as $key => $val)
                {
                    if ($val && isset($this->upload_config[$key]) && !isset($this->upload_config[$key]['blob']))
                    {
                        $this->remove_file($val, $key);
                    }
                }
            }
            if ($this->fk_relation) // removing FK relations
            {
                foreach ($this->fk_relation as $fk)
                {
                    $field = $fk['table'] . '.' . $fk['field'];
                    if (array_key_exists($field, $del_row))
                    {
                        $in_val = $db->escape($del_row[$field], false, $this->field_type[$field], $this->field_null[$field], isset($this->
                            bit_field[$field]));
                        $db->query('DELETE FROM `' . $fk['fk_table'] . '` WHERE `' . $fk['in_fk_field'] . '` = ' . $in_val);
                    }
                }
            }
            // end of remove case
        }
        if ($this->after_remove)
        {
            $path = $this->check_file($this->after_remove['path'], 'after_remove');
            include_once ($path);
            if (is_callable($this->after_remove['callable']))
            {
                call_user_func_array($this->after_remove['callable'], array($this->primary_val, $this));
            }
        }
        $this->task = 'list';
        $this->primary_val = null;
        return $del;
    }
    protected function check_postdata($postdata, $primary)
    {
        $mode = $primary ? 'edit' : 'create';
        foreach ($postdata as $key => $val)
        {
            if (isset($this->disabled[$key][$mode]) && !isset($this->readonly[$key][$mode]))
            {
                unset($postdata[$key]);
                continue;
            }
            if (isset($this->field_type[$key]))
            {
                switch ($this->field_type[$key])
                {
                    case 'password':
                        if (trim($val) == '')
                        {
                            unset($postdata[$key]);
                        }
                        break;
                    case 'datetime':
                        if ($val !== '')
                        {
                            if (preg_match('/^\-{0,1}[0-9]+$/u', $val))
                            {
                                $postdata[$key] = gmdate('Y-m-d H:i:s', $val);
                            }
                        }
                        else
                        {
                            if ($this->field_null[$key])
                            {
                                $postdata[$key] = null;
                            }
                            else
                            {
                                $postdata[$key] = '0000-00-00 00:00:00';
                            }
                        }
                        break;
                    case 'date':
                        if ($val !== '')
                        {
                            if (preg_match('/^\-{0,1}[0-9]+$/u', $val))
                            {
                                $postdata[$key] = gmdate('Y-m-d', $val);
                            }
                        }
                        else
                        {
                            if ($this->field_null[$key])
                            {
                                $postdata[$key] = null;
                            }
                            else
                            {
                                $postdata[$key] = '0000-00-00';
                            }
                        }
                        break;
                    case 'time':
                        if ($val !== '')
                        {
                            if (preg_match('/^\-{0,1}[0-9]+$/u', $val))
                            {
                                $postdata[$key] = gmdate('H:i:s', $val);
                            }
                        }
                        else
                        {
                            if ($this->field_null[$key])
                            {
                                $postdata[$key] = null;
                            }
                            else
                            {
                                $postdata[$key] = '00:00:00';
                            }
                        }
                        break;
                }
            }
        }
        return $postdata;
    }
    /** save events switcher */
    protected function _save()
    {
        $postdata = $this->_post('postdata');
        if (!$postdata)
        {
            self::error('No data to save!');
        }

        $postdata = $this->check_postdata($postdata, $this->primary_val);


        if ($this->inner_value !== false) // is nested
        {
            $field = reset($this->inner_where);
            if (!isset($postdata[$field])) // nested table connection field MUST be defined
            {
                $fdata = $this->_parse_field_names($field, 'build_select_details');
                $this->hidden_fields = array_merge($this->hidden_fields, $fdata);
                //$this->hidden_fields[$field] = $fdata[0];
                $postdata[$field] = $this->inner_value;
            }
        }
        $this->validate_postdata($postdata);
        if ($this->exception)
        {
            return $this->call_exception($postdata);
        }
        if (!$this->primary_val)
        {
            if (!$this->is_create || $this->table_ro)
                return self::error('Forbidden');
            if (isset($this->pass_var['create']))
            {
                foreach ($this->pass_var['create'] as $field => $param)
                {
                    if ($param['eval'])
                    {
                        $param['value'] = eval($param['value']);
                    }
                    $postdata[$field] = $this->replace_text_variables($param['value'], $postdata);
                    $this->hidden_fields[$field] = array('table' => $param['table'], 'field' => $param['field']);
                }
            }


            $pd = new Xcrud_postdata($postdata, $this);

            if ($this->alert_create)
            {
                foreach ($this->alert_create as $alert)
                {
                    if ($alert['field'] && $pd->get($alert['field']) != $alert['value'])
                        continue;

                    $send_to = $pd->get($alert['column']) ? $pd->get($alert['column']) : $alert['column'];
                    if (!$send_to or !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $send_to))
                        continue;
                    $alert['message'] = $this->replace_text_variables($alert['message'], $postdata);
                    if (Xcrud_config::$email_enable_html)
                        $message = $alert['message'] . '<br /><br />' . "\r\n" . ($alert['link'] ? '<a href="' . $alert['link'] .
                            '" target="_blank">' . $alert['link'] . '</a>' : '');
                    else
                        $message = $alert['message'] . "\r\n\r\n" . ($alert['link'] ? $alert['link'] : '');
                    $this->send_email($send_to, $alert['subject'], $message, $alert['cc'], Xcrud_config::$email_enable_html);
                }
            }
            if ($this->mass_alert_create)
            {
                foreach ($this->mass_alert_create as $alert)
                {
                    if ($alert['field'] && isset($postdata[$alert['field']]) && $postdata[$alert['field']] != $alert['value'])
                        continue;
                    $alert['message'] = $this->replace_text_variables($alert['message'], $postdata);
                    $alert['where'] = $this->replace_text_variables($alert['where'], $postdata);
                    if (Xcrud_config::$email_enable_html)
                        $message = $alert['message'] . '<br /><br />' . "\r\n" . ($alert['link'] ? '<a href="' . $alert['link'] .
                            '" target="_blank">' . $alert['link'] . '</a>' : '');
                    else
                        $message = $alert['message'] . "\r\n\r\n" . ($alert['link'] ? $alert['link'] : '');
                    $db = Xcrud_db::get_instance($this->connection);
                    $db->query("SELECT `{$alert['email_column']}` FROM `{$alert['email_table']}`" . ($alert['where'] ? ' WHERE ' . $alert['where'] :
                        ''));
                    foreach ($db->result() as $row)
                    {
                        $this->send_email($row[$alert['email_column']], $alert['subject'], $message, array(), Xcrud_config::$email_enable_html);
                    }
                }
            }

            if ($this->before_insert)
            {
                $path = $this->check_file($this->before_insert['path'], 'before_insert');
                include_once ($path);
                if (is_callable($this->before_insert['callable']))
                {
                    call_user_func_array($this->before_insert['callable'], array($pd, $this));
                    $postdata = $pd->to_array();
                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }

            $this->make_upload_process($pd);

            if ($this->replace_insert)
            {
                $path = $this->check_file($this->replace_insert['path'], 'replace_insert');
                include_once ($path);
                if (is_callable($this->replace_insert['callable']))
                {
                    $this->primary_val = call_user_func_array($this->replace_insert['callable'], array($pd, $this));
                    $postdata = $pd->to_array();
                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }
            else
            {
                $this->primary_val = $this->_insert($postdata);
            }
            if ($this->after_insert)
            {
                $path = $this->check_file($this->after_insert['path'], 'after_insert');
                include_once ($path);
                if (is_callable($this->after_insert['callable']))
                {
                    call_user_func_array($this->after_insert['callable'], array(
                        $pd,
                        $this->primary_val,
                        $this));
                    $postdata = $pd->to_array();
                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }

            $this->make_upload_process($pd);

            if ($this->send_external_create)
            {
                if (!$this->send_external_create['where_field'] or $postdata[$this->send_external_create['where_field']] == $this->
                    send_external_create['where_val'])
                {
                    foreach ($this->send_external_create['data'] as $key => $value)
                    {
                        $this->send_external_create['data'][$key] = $this->replace_text_variables($value, $postdata + array($this->table . '.' .
                                $this->primary_key => $this->primary_val));
                    }
                    switch ($this->send_external_create['method'])
                    {
                        case 'include':
                            $path = $this->check_file($this->send_external_create['path'], 'send_external_create');
                            ob_start();
                            extract($this->send_external_create['data']);
                            include ($path);
                            ob_end_clean();
                            break;
                        case 'get':
                        case 'post':
                            $this->send_http_request($this->send_external_create['path'], $this->send_external_create['data'], $this->
                                send_external_create['method'], false);
                            break;
                    }
                }
            }
        }
        else
        {
            if ($this->table_ro)
                return self::error('Forbidden');
            $fields = array();
            $row = array();
            $this->find_details_text_variables();
            if ($this->direct_select_tags)
            {
                foreach ($this->direct_select_tags as $key => $dsf)
                {
                    $fields[$key] = "`{$dsf['table']}`.`{$dsf['field']}` AS `{$key}`";
                }
            }
            if ($fields)
            {
                $db = Xcrud_db::get_instance($this->connection);
                if (!$this->join)
                {
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` WHERE `{$this->primary_key}` = " . $db->escape($this->
                        primary_val) . " LIMIT 1");
                    $row = $db->row();

                }
                else
                {
                    $tables = array('`' . $this->table . '`');
                    $joins = array();
                    foreach ($this->join as $alias => $param)
                    {
                        $tables[] = '`' . $alias . '`';
                        $joins[] = "INNER JOIN `{$param['join_table']}` AS `{$alias}`
                    ON `{$param['table']}`.`{$param['field']}` = `{$alias}`.`{$param['join_field']}`";
                    }
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` AS `{$this->table}` " . implode(' ', $joins) .
                        " WHERE `{$this->table}`.`{$this->primary_key}` = " . $db->escape($this->primary_val));
                    $row = $db->row();
                }
            }

            if (!$this->is_edit($row))
                return self::error('Forbidden');

            if (isset($this->pass_var['edit']))
            {
                foreach ($this->pass_var['edit'] as $field => $param)
                {
                    if (isset($param['tmp_value']))
                    {
                        $param['value'] = $param['tmp_value'];
                        unset($this->pass_var['edit'][$field]['tmp_value']);
                    }
                    if ($param['eval'])
                    {
                        $param['value'] = eval($param['value']);
                    }
                    $postdata[$field] = $this->replace_text_variables($param['value'], $postdata);
                    $postdata[$field] = $this->replace_text_variables($param['value'], $row);
                    $this->hidden_fields[$field] = array('table' => $param['table'], 'field' => $param['field']);
                }
            }

            $pd = new Xcrud_postdata($postdata, $this);

            if ($this->alert_edit)
            {
                foreach ($this->alert_edit as $alert)
                {
                    if ($alert['field'] && $pd->get($alert['field']) != $alert['value'])
                        continue;
                    $send_to = $pd->get($alert['column']) ? $pd->get($alert['column']) : $alert['column'];
                    if (!$send_to or !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $send_to))
                        continue;
                    $alert['message'] = $this->replace_text_variables($alert['message'], $postdata);
                    if (Xcrud_config::$email_enable_html)
                        $message = $alert['message'] . '<br /><br />' . "\r\n" . ($alert['link'] ? '<a href="' . $alert['link'] .
                            '" target="_blank">' . $alert['link'] . '</a>' : '');
                    else
                        $message = $alert['message'] . "\r\n\r\n" . ($alert['link'] ? $alert['link'] : '');
                    $this->send_email($send_to, $alert['subject'], $message, $alert['cc'], Xcrud_config::$email_enable_html);
                }
            }
            if ($this->mass_alert_edit)
            {
                foreach ($this->mass_alert_edit as $alert)
                {
                    if ($alert['field'] && isset($postdata[$alert['field']]) && $postdata[$alert['field']] != $alert['value'])
                        continue;
                    $alert['message'] = $this->replace_text_variables($alert['message'], $postdata);
                    $alert['where'] = $this->replace_text_variables($alert['where'], $postdata);
                    if (Xcrud_config::$email_enable_html)
                        $message = $alert['message'] . '<br /><br />' . "\r\n" . ($alert['link'] ? '<a href="' . $alert['link'] .
                            '" target="_blank">' . $alert['link'] . '</a>' : '');
                    else
                        $message = $alert['message'] . "\r\n\r\n" . ($alert['link'] ? $alert['link'] : '');
                    $db = Xcrud_db::get_instance($this->connection);
                    $db->query("SELECT `{$alert['email_column']}` FROM `{$alert['email_table']}`" . ($alert['where'] ? ' WHERE ' . $alert['where'] :
                        ''));
                    foreach ($db->result() as $row)
                    {
                        $this->send_email($row[$alert['email_column']], $alert['subject'], $message, array(), Xcrud_config::$email_enable_html);
                    }
                }
            }

            if ($this->before_update)
            {
                $path = $this->check_file($this->before_update['path'], 'before_update');
                include_once ($path);
                if (is_callable($this->before_update['callable']))
                {
                    call_user_func_array($this->before_update['callable'], array(
                        $pd,
                        $this->primary_val,
                        $this));
                    $postdata = $pd->to_array();

                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }
            if ($this->replace_update)
            {
                $path = $this->check_file($this->replace_update['path'], 'replace_update');
                include_once ($path);
                if (is_callable($this->replace_update['callable']))
                {
                    $this->primary_val = call_user_func_array($this->replace_update['callable'], array(
                        $pd,
                        $this->primary_val,
                        $this));
                    $postdata = $pd->to_array();
                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }
            else
                $this->primary_val = $this->_update($postdata, $this->primary_val);
            if ($this->after_update)
            {
                $path = $this->check_file($this->after_update['path'], 'after_update');
                include_once ($path);
                if (is_callable($this->after_update['callable']))
                {
                    call_user_func_array($this->after_update['callable'], array(
                        $pd,
                        $this->primary_val,
                        $this));
                    $postdata = $pd->to_array();
                    if ($this->exception)
                    {
                        return $this->call_exception($postdata);
                    }
                }
            }
            if ($this->send_external_edit)
            {
                if (!$this->send_external_edit['where_field'] or $postdata[$this->send_external_edit['where_field']] == $this->
                    send_external_edit['where_val'])
                {
                    foreach ($this->send_external_edit['data'] as $key => $value)
                    {
                        $this->send_external_edit['data'][$key] = $this->replace_text_variables($value, $postdata);
                    }
                    switch ($this->send_external_edit['method'])
                    {
                        case 'include':
                            $path = $this->check_file($this->send_external_edit['path'], 'send_external_edit');
                            ob_start();
                            extract($this->send_external_edit['data']);
                            include ($path);
                            ob_end_clean();
                            break;
                        case 'get':
                        case 'post':
                            $this->send_http_request($this->send_external_edit['path'], $this->send_external_edit['data'], $this->
                                send_external_edit['method'], false);
                            break;
                    }
                }
            }
        }
        unset($postdata);
        $this->task = $this->after;
        $this->after = null;
        return $this->_run_task();
    }
    protected function validate_postdata($postdata)
    {
        foreach ($postdata as $key => $val)
        {
            if (isset($this->validation_required[$key]) && mb_strlen($val) < $this->validation_required[$key])
            {
                $this->set_exception($key, 'validation_error', 'error');
            }
            elseif (isset($this->validation_pattern[$key]) && mb_strlen($val) > 0)
            {
                switch ($this->validation_pattern[$key])
                {
                    case 'email':
                        $reg = '/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/u';
                        break;
                    case 'alpha':
                        $reg = '/^([a-z])+$/ui';
                        break;
                    case 'alpha_numeric':
                        $reg = '/^([a-z0-9])+$/ui';
                        break;
                    case 'alpha_dash':
                        $reg = '/^([-a-z0-9_-])+$/ui';
                        break;
                    case 'numeric':
                        $reg = '/^[\-+]?[0-9]*\.?[0-9]+$/u';
                        break;
                    case 'integer':
                        $reg = '/^[\-+]?[0-9]+$/u';
                        break;
                    case 'decimal':
                        $reg = '/^[\-+]?[0-9]+\.[0-9]+$/u';
                        break;
                    case 'point':
                        $reg = '/^[\-+]?[0-9]+\.{0,1}[0-9]*\,[\-+]?[0-9]+\.{0,1}[0-9]*$/u';
                        break;
                    case 'natural':
                        $reg = '/^[0-9]+$/u';
                        break;
                    default:
                        $reg = '/' . $this->validation_pattern[$key] . '/u';
                        break;
                }
                if (!preg_match($reg, $val))
                {
                    $this->set_exception($key, 'validation_error', 'error');
                }
            }
        }
    }
    protected function call_exception($postdata = array())
    {
        $this->cancel_file_saving = true;
        switch ($this->task)
        {
            case 'upload':
                switch ($this->_post('type'))
                {
                    case 'image':
                        return $this->create_image($this->_post('field'), '') . $this->render_message();
                        break;
                    case 'file':
                        return $this->create_file($this->_post('field'), '') . $this->render_message();
                        break;
                    default:
                        return self::error('Upload Error');
                        break;
                }
                break;
        }

        $this->task = $this->before;
        switch ($this->before)
        {
            case 'create':
                return $this->_create($postdata);
                break;
            case 'edit':
            case 'view':
                return $this->_entry($this->before, $postdata);
                break;
            case 'upload':
                break;
            default:
                return $this->_list();
                break;
        }
    }

    protected function make_upload_process($pd)
    {
        if ($this->upload_config)
        {
            foreach ($this->upload_config as $key => $opts)
            {
                if (isset($opts['blob']) && $opts['blob'] && $pd->get($key))
                {
                    if ($pd->get($key) == 'blob-storage')
                    {
                        $pd->del($key);
                        continue;
                    }
                    else
                    {
                        $folder = $this->upload_folder[$key];
                        $path = $folder . '/' . $pd->get($key);
                        if (is_file($path))
                        {
                            $pd->set(file_get_contents($path));
                            unlink($path);
                        }
                    }
                }
            }
        }
    }


    public function set_exception($fields = '', $message = '', $type = 'note')
    {
        if ($message)
        {
            $this->message = array('type' => $type, 'text' => $this->lang($message));
        }
        if ($fields)
        {
            $fdata = $this->_parse_field_names($fields, 'set_exception');
            foreach ($fdata as $key => $fitem)
            {
                $this->exception_fields[$key] = $fitem;
            }
        }
        $this->exception = true;
        return $this;
    }
    public function set_message($message = '', $type = 'note')
    {
        if ($message)
        {
            $this->message = array('type' => $type, 'text' => $this->lang($message));
        }
        return $this;
    }


    /** grid processing */
    protected function _list()
    {
        if (!$this->is_list)
        {
            return self::error('Forbidden');
        }
        /*if (!$this->search_columns)
        {
        $this->search_columns = $this->columns;
        }*/
        $select = $this->_build_select_list();
        $table_join = $this->_build_table_join();
        $where = $this->_build_where();
        $order_by = $this->_build_order_by();
        $sum_tmp = array();
        if ($this->sum)
        {
            foreach ($this->sum as $field => $param)
            {
                if (isset($this->subselect[$field]))
                    $sum_tmp[$field] = 'SUM(' . $this->subselect_where($field) . ') AS `' . $field . '`';
                else
                    $sum_tmp[$field] = 'SUM(`' . $param['table'] . '`.`' . $param['column'] . '`) AS `' . $field . '`';
            }
        }
        $sum = $sum_tmp ? ', ' . implode(', ', $sum_tmp) : '';
        $db = Xcrud_db::get_instance($this->connection);
        //$db->query("SELECT COUNT(`{$this->table}`.`{$this->primary_key}`) AS `count` {$sum} \r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}");
        $db->query("SELECT COUNT(*) AS `count` {$sum} \r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}");
        $this->sum_row = $db->row();
        $this->result_total = $this->sum_row['count'];
        $limit = $this->_build_limit($this->result_total);
        $db->query("SELECT {$select} \r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}\r\n {$order_by}\r\n {$limit}");
        $this->result_list = $db->result();

        if ($this->before_list)
        {
            $path = $this->check_file($this->before_list['path'], 'before_list');
            include_once ($path);
            if (is_callable($this->before_list['callable']))
            {
                call_user_func_array($this->before_list['callable'], array($this->result_list, $this));
            }
        }

        $this->_set_column_names();
        return $this->_render_list();
    }
    /** defines primary condition for internal usage */
    protected function where_pri($fields = false, $where_val = false, $glue = 'AND', $index = false)
    {

        if ($fields !== false && $where_val !== false)
        {
            $fdata = $this->_parse_field_names($fields, 'where_pri');
            foreach ($fdata as $fitem)
            {
                if ($index)
                {
                    $this->where_pri[$index] = array(
                        'table' => $fitem['table'],
                        'field' => $fitem['field'],
                        'value' => isset($fitem['value']) ? $fitem['value'] : $where_val,
                        'glue' => $glue);
                }
                else
                {
                    $this->where_pri[] = array(
                        'table' => $fitem['table'],
                        'field' => $fitem['field'],
                        'value' => isset($fitem['value']) ? $fitem['value'] : $where_val,
                        'glue' => $glue);
                }
            }
            unset($fields, $fdata);
        }
        elseif ($fields)
        {
            if ($index)
            {
                $this->where_pri[$index] = array('custom' => $fields, 'glue' => $glue);
            }
            else
            {
                $this->where_pri[] = array('custom' => $fields, 'glue' => $glue);
            }
            unset($where_val);
        }
        return $this;
    }

    /** 'select' subquery for grid view */
    protected function _build_select_list($csv = false)
    {
        $this->find_grid_text_variables();
        $db = Xcrud_db::get_instance($this->connection);
        $columns = array();
        //$subselect_before = $this->subselect_before;
        foreach ($this->columns as $field_index => $val)
        {
            if ($val)
            {
                //$field_index = $key;

                if (isset($this->subselect[$field_index]))
                {
                    $columns[] = $this->subselect_query($field_index);
                }
                elseif (isset($this->point_field[$field_index]))
                {
                    $columns[] = 'CONCAT(X(`' . $val['table'] . '`.`' . $val['field'] . '`),\',\',Y(`' . $val['table'] . '`.`' . $val['field'] .
                        '`)) AS `' . $val['table'] . '.' . $val['field'] . '`' . "\r\n";
                }
                elseif (isset($this->relation[$field_index]))
                {
                    if (is_array($this->relation[$field_index]['rel_name']))
                    {
                        $tmp_fields = array();

                        foreach ($this->relation[$field_index]['rel_name'] as $tmp)
                        {
                            $tmp_fields[] = "`{$tmp}` \r\n";
                        }
                        if ($this->relation[$field_index])
                        {
                            $where = "FIND_IN_SET(`{$this->relation[$field_index]['rel_tbl']}`.`{$this->relation[$field_index]['rel_field']}`,`{$this->relation[$field_index]['table']}`.`{$this->relation[$field_index]['field']}`)";
                        }
                        else
                        {
                            $where = "`{$this->relation[$field_index]['rel_tbl']}`.`{$this->relation[$field_index]['rel_field']}` = `{$this->relation[$field_index]['table']}`.`{$this->relation[$field_index]['field']}`";
                        }
                        $columns[] = "(SELECT GROUP_CONCAT(DISTINCT (CONCAT_WS('{$this->relation[$field_index]['rel_separator']}'," . implode(',',
                            $tmp_fields) . ")) SEPARATOR ', ')
                            FROM `{$this->relation[$field_index]['rel_tbl']}`
                            WHERE {$where})
                            AS `rel.{$val['table']}.{$val['field']}`, \r\n `{$val['table']}`.`{$val['field']}` AS `{$val['table']}.{$val['field']}` \r\n";
                    }
                    elseif ($this->relation[$field_index]['multi'])
                    {
                        $columns[] = "(SELECT GROUP_CONCAT(DISTINCT `{$this->relation[$field_index]['rel_name']}` SEPARATOR ', ')
                        FROM `{$this->relation[$field_index]['rel_tbl']}` WHERE
                        FIND_IN_SET(`{$this->relation[$field_index]['rel_tbl']}`.`{$this->relation[$field_index]['rel_field']}`,`{$this->relation[$field_index]['table']}`.`{$this->relation[$field_index]['field']}`)
                        ORDER BY `{$this->relation[$field_index]['rel_name']}` ASC)
                         AS `rel.{$val['table']}.{$val['field']}`, \r\n `{$val['table']}`.`{$val['field']}` AS `{$val['table']}.{$val['field']}` \r\n";
                    }
                    else
                    {
                        $columns[] = "(SELECT `{$this->relation[$field_index]['rel_alias']}`.`{$this->relation[$field_index]['rel_name']}`
                            FROM `{$this->relation[$field_index]['rel_tbl']}` AS `{$this->relation[$field_index]['rel_alias']}`
                            WHERE `{$this->relation[$field_index]['rel_alias']}`.`{$this->relation[$field_index]['rel_field']}` = `{$this->relation[$field_index]['table']}`.`{$this->relation[$field_index]['field']}`
                            LIMIT 1)
                            AS `rel.{$val['table']}.{$val['field']}`, \r\n `{$val['table']}`.`{$val['field']}` AS `{$val['table']}.{$val['field']}` \r\n";
                    }
                }
                //
                elseif (isset($this->fk_relation[$field_index]))
                {
                    $fk = $this->fk_relation[$field_index];
                    if (is_array($fk['rel_name']))
                    {
                        foreach ($fk['rel_name'] as $tmp)
                        {
                            $tmp_fields[] = '`' . $fk['rel_tbl'] . '`.`' . $tmp . '`';
                            $rel_name = 'CONCAT_WS(' . $db->escape($fk['rel_separator']) . ',' . implode(',', $tmp_fields) . ')';
                        }
                    }
                    else
                    {
                        $rel_name = '`' . $fk['rel_tbl'] . '`.`' . $fk['rel_name'] . '`';
                    }
                    $columns[] = '(SELECT GROUP_CONCAT(DISTINCT ' . $rel_name . ' SEPARATOR \', \')
                        FROM `' . $fk['rel_tbl'] . '`
						INNER JOIN `' . $fk['fk_table'] . '` ON `' . $fk['fk_table'] . '`.`' . $fk['out_fk_field'] . '` = `' . $fk['rel_tbl'] .
                        '`.`' . $fk['rel_field'] . '` WHERE `' . $fk['fk_table'] . '`.`' . $fk['in_fk_field'] . '` = `' . $fk['table'] . '`.`' .
                        $fk['field'] . '` AND ' . $this->_build_rel_where($field_index) . ')
                         AS `' . $fk['alias'] . '` ' . "\r\n";
                }
                elseif (isset($this->bit_field[$field_index]))
                {
                    $columns[] = "CAST(`{$val['table']}`.`{$val['field']}` AS UNSIGNED) AS `{$val['table']}.{$val['field']}` \r\n";
                }
                else
                {
                    $columns[] = "`{$val['table']}`.`{$val['field']}` AS `{$val['table']}.{$val['field']}` \r\n";
                }
            }
        }
        if ($this->hidden_columns)
        {
            foreach ($this->hidden_columns as $field_index => $val)
            {
                $columns[] = "`{$val['table']}`.`{$val['field']}` AS `{$field_index}` \r\n";
            }
        }

        if (!$this->primary_key)
        {
            $columns[] = "(0) AS `primary_key` \r\n";
        }
        else
        {
            $columns[] = "`{$this->table}`.`{$this->primary_key}` AS `primary_key` \r\n";
        }

        return implode(',', $columns);
    }
    /** creates subselect subquery for grid view */
    protected function subselect_query($name)
    {
        if (isset($this->subselect_query[$name]))
        {
            $sql = $this->subselect_query[$name];
        }
        else
        {
            $sql = preg_replace_callback('/\{(.+)\}/Uu', array($this, 'subselect_callback'), $this->subselect[$name]);
            $this->subselect_query[$name] = $sql;
        }
        return "({$sql}) AS `{$name}`";
    }
    protected function subselect_where($name)
    {
        if (isset($this->subselect_query[$name]))
        {
            return '(' . $this->subselect_query[$name] . ')';
        }
        else
        {
            $this->subselect_query($name);
            return '(' . $this->subselect_query[$name] . ')';
        }
    }
    protected function subselect_callback($matches)
    {
        if (strpos($matches[1], '.'))
        {
            $tmp = explode('.', $matches[1]);
            if (isset($this->subselect[$this->prefix . $tmp[0] . '.' . $tmp[1]]))
            {
                return $this->subselect_where($this->prefix . $tmp[0] . '.' . $tmp[1]);
            }
            else
                return '`' . $this->prefix . $tmp[0] . '`.`' . $tmp[1] . '`';
        }
        else
        {
            if (isset($this->subselect[$this->table . '.' . $matches[1]]))
            {
                return $this->subselect_where($this->table . '.' . $matches[1]);
            }
            else
                return '`' . $this->table . '`.`' . $matches[1] . '`';
        }
    }

    /** 'select' subquery part for edit/details view */
    protected function _build_select_details($mode)
    {
        $this->find_details_text_variables();
        $fields = array();
        if ($this->inner_table_instance) // nested table
        {
            foreach ($this->inner_table_instance as $inst_name => $field)
            {
                if (!isset($this->fields[$field])) // nested table connection field MUST be extracted from DB, even if not defined
                {
                    $fdata = $this->_parse_field_names($field, 'build_select_details');
                    //$this->hidden_fields[$field] = $fdata[0];
                    $this->hidden_fields = array_merge($this->hidden_fields, $fdata);
                }
            }
        }

        if ($this->fields)
        {
            foreach ($this->fields as $key => $val)
            {
                if ($val && !isset($this->custom_fields[$key]))
                {
                    if (isset($this->subselect[$key]))
                    {
                        $fields[] = $this->subselect_query($key);
                    }
                    elseif (isset($this->fk_relation[$key]))
                    {
                        $fk = $this->fk_relation[$key];
                        $fields[] = '(SELECT GROUP_CONCAT(DISTINCT `' . $fk['rel_tbl'] . '`.`' . $fk['rel_field'] . '` SEPARATOR \',\')
	                        FROM `' . $fk['rel_tbl'] . '`
							INNER JOIN `' . $fk['fk_table'] . '` ON `' . $fk['fk_table'] . '`.`' . $fk['out_fk_field'] . '` = `' . $fk['rel_tbl'] .
                            '`.`' . $fk['rel_field'] . '` WHERE `' . $fk['fk_table'] . '`.`' . $fk['in_fk_field'] . '` = `' . $fk['table'] . '`.`' .
                            $fk['field'] . '` AND ' . $this->_build_rel_where($key) . ')
	                         AS `' . $fk['alias'] . '` ' . "\r\n";
                    }
                    elseif (isset($this->point_field[$key]))
                    {
                        $fields[] = 'CONCAT(X(`' . $val['table'] . '`.`' . $val['field'] . '`),\',\',Y(`' . $val['table'] . '`.`' . $val['field'] .
                            '`)) AS `' . $val['table'] . '.' . $val['field'] . '`' . "\r\n";
                    }
                    elseif (isset($this->bit_field[$key]))
                    {
                        $fields[] = "CAST(`{$val['table']}`.`{$val['field']}` AS UNSIGNED) AS `$key`";
                    }
                    else
                    {
                        $fields[] = "`{$val['table']}`.`{$val['field']}` AS `$key`";
                    }
                }
            }
        }
        if ($this->hidden_fields)
        {
            foreach ($this->hidden_fields as $key => $val)
            {
                if ($val)
                    $fields[] = "`{$val['table']}`.`{$val['field']}` AS `{$key}`";
            }
        }

        $fields[] = "`{$this->table}`.`{$this->primary_key}` AS `primary_key`";
        return implode(',', $fields);
    }
    protected function _build_table_join()
    {
        $join = '';
        if (count($this->join))
        {
            $join_arr = array();
            foreach ($this->join as $alias => $params)
            {
                $join_arr[] = "INNER JOIN `{$params['join_table']}` AS `{$alias}`
                ON `{$params['table']}`.`{$params['field']}` = `{$alias}`.`{$params['join_field']}`";
            }
            $join .= implode(' ', $join_arr);
        }
        return $join;
    }

    /** builds main where condition for query */
    protected function _build_where()
    {
        $db = Xcrud_db::get_instance($this->connection);
        $where_arr = array();
        $where_arr_pri = array();


        // user defined conditions
        if ($this->where)
        {
            foreach ($this->where as $key => $params)
            {
                if ($where_arr)
                    $where_arr[] = $params['glue'];

                if (!isset($params['custom']))
                {
                    $fieldkey = $this->_where_fieldkey($params);
                    if (is_array($params['value']))
                    {
                        $in_arr = array();
                        foreach ($params['value'] as $in_val)
                        {
                            $in_arr[] = $db->escape($in_val);
                        }
                        if (isset($this->subselect[$fieldkey]))
                        {
                            $where_arr[] = $this->subselect_where($fieldkey) . $this->_cond_from_where_in($params['field']) . '(' . implode(',', $in_arr) .
                                ')';
                        }
                        else
                        {
                            $where_arr[] = $this->_where_field($params) . $this->_cond_from_where_in($params['field']) . '(' . implode(',', $in_arr) .
                                ')';
                        }
                    }
                    else
                    {
                        if (isset($this->subselect[$fieldkey]))
                        {
                            $where_arr[] = $this->subselect_where($fieldkey) . $this->_cond_from_where($params['field']) . $db->escape($params['value'],
                                isset($this->no_quotes[$fieldkey]));
                        }
                        elseif (isset($this->point_field[$fieldkey]))
                        {
                            $where_arr[] = 'CONCAT(X(`' . $this->_where_field($params) . '`),\',\',Y(`' . $this->_where_field($params) . '`))' . $this->
                                _cond_from_where($params['field']) . $db->escape($params['value'], isset($this->no_quotes[$fieldkey]));
                        }
                        else
                        {
                            $where_arr[] = $this->_where_field($params) . $this->_cond_from_where($params['field']) . $db->escape($params['value'],
                                isset($this->no_quotes[$fieldkey]));
                        }
                    }
                }
                else
                {
                    $where_arr[] = '(' . $params['custom'] . ')';
                }
            }
        }

        // internal condition
        if ($this->where_pri)
        {
            foreach ($this->where_pri as $params)
            {
                if ($where_arr_pri)
                    $where_arr_pri[] = $params['glue'];
                if (isset($params['custom']))
                {
                    $where_arr_pri[] = '(' . $params['custom'] . ')';
                }
                else
                {
                    $where_arr_pri[] = $this->_where_field($params) . $this->_cond_from_where($params['field']) . $db->escape($params['value']);
                }
            }
        }

        // search condition
        if ($this->search && ($this->task == 'list' or $this->task == 'print' or $this->task == 'csv' or $this->after == 'list'))
        {
            if ($where_arr)
            {
                $where_arr[] = 'AND';
            }
            $search_columns = $this->search_columns ? $this->search_columns : $this->columns;
            if ($this->column && isset($search_columns[$this->column]))
            {
                // if relation
                if (isset($this->relation[$this->column]))
                {
                    $where_arr[] = $this->_build_relation_subwhere($this->column);
                }
                // if fk-relation
                elseif (isset($this->fk_relation[$this->column]))
                {
                    $where_arr[] = $this->_build_fk_relation_subwhere($this->column);
                }
                // search in subselect
                elseif (isset($this->subselect[$this->column]))
                {
                    $where_arr[] = '(' . $this->subselect_query[$this->column] . ') LIKE ' . $db->escape_like($this->phrase, $this->
                        search_pattern);
                }
                elseif (isset($this->point_field[$this->column]))
                {
                    $fdata = $this->_parse_field_names($this->column, 'build_where', false, false);
                    $fitem = reset($fdata);
                    $where_arr[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                        '`))LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                }
                else
                {
                    $fdata = $this->_parse_field_names($this->column, 'build_where', false, false);
                    $fitem = reset($fdata);
                    $key = key($fdata);
                    // search via fild types
                    switch ($this->field_type[$this->column])
                    {
                        case 'timestamp':
                        case 'datetime':
                        case 'date':
                        case 'time':
                            switch ($this->field_type[$this->column])
                            {
                                case 'date':
                                    $format = 'Y-m-d';
                                    break;
                                case 'time':
                                    $format = 'H:i:s';
                                    break;
                                default:
                                    $format = 'Y-m-d H:i:s';
                                    break;
                            }
                            if ($this->phrase['from'] && $this->phrase['to'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` BETWEEN ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['from'])) . ' AND ' . $db->escape(gmdate($format, (int)$this->phrase['to'])) . ')';
                            }
                            elseif ($this->phrase['from'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` >= ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['from'])) . ')';
                            }
                            elseif ($this->phrase['to'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` <= ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['to'])) . ')';
                            }
                            break;
                        case 'select':
                        case 'radio':
                            $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` = ' . $db->escape($this->phrase) . ')';
                            break;
                            /*case 'multiselect':
                            case 'checkboxes':

                            break;*/
                        case 'bool':
                            if (isset($this->bit_field[$key]))
                            {
                                $where_arr[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) = ' . ((int)$this->phrase);
                            }
                            else
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` = ' . ((int)$this->phrase) . ')';
                            }
                            break;
                        default:
                            if (isset($this->point_field[$key]))
                            {
                                $where_arr[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                                    '`)) LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                            }
                            elseif (isset($this->bit_field[$key]))
                            {
                                $where_arr[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) LIKE ' . $db->escape_like($this->
                                    phrase, $this->search_pattern);
                            }
                            else
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                                    search_pattern) . ')';
                            }
                            break;
                    }
                }
            }
            else
            {
                // multicolumn search
                //$f_array = array();
                $or_array = array();
                //$search_columns = $this->search_columns ? $this->search_columns : $this->columns;
                foreach ($search_columns as $key => $fitem)
                {
                    if (isset($this->relation[$key]))
                    {
                        $or_array[] = $this->_build_relation_subwhere($key);
                    }
                    elseif (isset($this->fk_relation[$key]))
                    {
                        $or_array[] = $this->_build_fk_relation_subwhere($key);
                    }
                    elseif (isset($this->subselect[$key]))
                    {
                        $or_array[] = '(' . $this->subselect_query[$key] . ') LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                    }
                    elseif ($this->field_type[$key] == 'date' || $this->field_type[$key] == 'datetime' || $this->field_type[$key] ==
                        'timestamp' || $this->field_type[$key] == 'time')
                    {
                        if (preg_match('/^[0-9\-\:\s]+$/', $this->phrase))
                        {
                            $or_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                                search_pattern);
                        }
                    }
                    elseif (isset($this->point_field[$key]))
                    {
                        $or_array[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                            '`)) LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                    }
                    elseif (isset($this->bit_field[$key]))
                    {
                        $or_array[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) LIKE ' . $db->escape_like($this->
                            phrase, $this->search_pattern);
                    }
                    else
                    {
                        //$f_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '`';
                        $or_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                            search_pattern);
                    }
                }
                $where = '(';
                /*if ($f_array)
                {
                $where .= 'CONCAT_WS(\' \',' . implode(',', $f_array) . ') LIKE ' . $db->escape_like($this->phrase, $this->
                search_pattern);
                }
                if ($f_array && $or_array)
                {
                $where .= ' OR ';
                }*/
                if ($or_array)
                {
                    $where .= implode(' OR ', $or_array);
                }
                $where .= ')';
                $where_arr[] = $where;
            }

        }

        // final part
        if ($where_arr or $where_arr_pri)
            return 'WHERE ' . ($where_arr ? '(' . implode(' ', $where_arr) . ')' : '') . ($where_arr_pri ? ($where_arr ? ' AND ' :
                '') . implode(' ', $where_arr_pri) : '');
        else
            return '';
    }
    /** relation values will be searched by displayed name (not by id) */
    protected function _build_relation_subwhere($key) // multicolumn name
    {
        $db = Xcrud_db::get_instance($this->connection);

        if ($key)
        {
            $rel = $this->relation[$key];
            if (is_array($rel['rel_name']))
            {
                $tmp_fields = array();

                foreach ($rel['rel_name'] as $tmp)
                {
                    $tmp_fields[] = '`' . $tmp . '`' . "\r\n";
                }
                // multiselect relation
                if ($rel['multi'])
                {
                    $where = '`' . $rel['rel_tbl'] . '`.`' . $rel['rel_field'] . '` LIKE `' . $rel['table'] . '`.`' . $rel['field'] . '`';
                }
                else
                {
                    $where = '`' . $rel['rel_tbl'] . '`.`' . $rel['rel_field'] . '` = `' . $rel['table'] . '`.`' . $rel['field'] . '`';
                }
                $select = "(SELECT GROUP_CONCAT(DISTINCT (CONCAT_WS('{$rel['rel_separator']}'," . implode(',', $tmp_fields) .
                    ")) SEPARATOR ', ')
                            FROM `{$rel['rel_tbl']}`
                            WHERE {$where})\r\n";
            }
            // multiselect relation
            elseif ($rel['multi'])
            {
                $select = "(SELECT GROUP_CONCAT(DISTINCT `{$rel['rel_name']}` SEPARATOR ', ')
                        FROM `{$rel['rel_tbl']}` WHERE
                        FIND_IN_SET(`{$rel['rel_tbl']}`.`{$rel['rel_field']}`,`{$rel['table']}`.`{$rel['field']}`)
                        ORDER BY `{$rel['rel_name']}` ASC)\r\n";
            }
            else
            {
                $select = "(SELECT `{$rel['rel_alias']}`.`{$rel['rel_name']}`
                            FROM `{$rel['rel_tbl']}` AS `{$rel['rel_alias']}`
                            WHERE `{$rel['rel_alias']}`.`{$rel['rel_field']}` = `{$rel['table']}`.`{$rel['field']}`
                            LIMIT 1) \r\n";
            }
            return "{$select} LIKE " . $db->escape_like($this->phrase, $this->search_pattern);
        }
        /*
        else
        {
        $or_where = array();
        foreach ($this->relation as $column => $param)
        {
        if (is_array($this->relation[$column]['rel_name']))
        {
        $tmp_fields = array();

        foreach ($this->relation[$column]['rel_name'] as $tmp)
        {
        $tmp_fields[] = '`' . $tmp . '`' . "\r\n";
        }
        // multiselect relation
        if ($this->relation[$column]['multi'])
        {
        $where = '`' . $this->relation[$column]['rel_tbl'] . '`.`' . $this->relation[$column]['rel_field'] . '` LIKE `' . $this->
        relation[$column]['table'] . '`.`' . $this->relation[$column]['field'] . '`';
        }
        else
        {
        $where = '`' . $this->relation[$column]['rel_tbl'] . '`.`' . $this->relation[$column]['rel_field'] . '` = `' . $this->
        relation[$column]['table'] . '`.`' . $this->relation[$column]['field'] . '`';
        }
        $select = "(SELECT GROUP_CONCAT(DISTINCT (CONCAT_WS('{$this->relation[$column]['rel_separator']}'," . implode(',', $tmp_fields) .
        ")) SEPARATOR ', ')
        FROM `{$this->relation[$column]['rel_tbl']}`
        WHERE {$where})\r\n";
        }
        // multiselect relation
        elseif ($this->relation[$column]['multi'])
        {
        $select = "(SELECT GROUP_CONCAT(DISTINCT `{$this->relation[$column]['rel_name']}` SEPARATOR ', ')
        FROM `{$this->relation[$column]['rel_tbl']}` WHERE
        FIND_IN_SET(`{$this->relation[$column]['rel_tbl']}`.`{$this->relation[$column]['rel_field']}`,`{$this->relation[$column]['table']}`.`{$this->relation[$column]['field']}`)
        ORDER BY `{$this->relation[$column]['rel_name']}` ASC)\r\n";
        }
        else
        {
        $select = "(SELECT `{$this->relation[$column]['rel_alias']}`.`{$this->relation[$column]['rel_name']}`
        FROM `{$this->relation[$column]['rel_tbl']}` AS `{$this->relation[$column]['rel_alias']}`
        WHERE `{$this->relation[$column]['rel_alias']}`.`{$this->relation[$column]['rel_field']}` = `{$this->relation[$column]['table']}`.`{$this->relation[$column]['field']}`
        LIMIT 1) \r\n";
        }
        $or_where[] = $select . ' LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
        }
        return implode(' OR ', $or_where);
        }*/
    }

    protected function _build_fk_relation_subwhere($key) // multicolumn name
    {
        $db = Xcrud_db::get_instance($this->connection);
        $fk = $this->fk_relation[$key];

        if (is_array($fk['rel_name']))
        {
            foreach ($fk['rel_name'] as $tmp)
            {
                $tmp_fields[] = '`' . $fk['rel_tbl'] . '`.`' . $tmp . '`';
                $rel_name = 'CONCAT_WS(' . $db->escape($fk['rel_separator']) . ',' . implode(',', $tmp_fields) . ')';
            }
        }
        else
        {
            $rel_name = '`' . $fk['rel_tbl'] . '`.`' . $fk['rel_name'] . '`';
        }
        $select = '(SELECT GROUP_CONCAT(DISTINCT ' . $rel_name . ' SEPARATOR \', \')
            FROM `' . $fk['rel_tbl'] . '`
            INNER JOIN `' . $fk['fk_table'] . '` ON `' . $fk['fk_table'] . '`.`' . $fk['out_fk_field'] . '` = `' . $fk['rel_tbl'] .
            '`.`' . $fk['rel_field'] . '` WHERE `' . $fk['fk_table'] . '`.`' . $fk['in_fk_field'] . '` = `' . $fk['table'] . '`.`' .
            $fk['field'] . '` AND ' . $this->_build_rel_where($key) . ')' . "\r\n";
        return $select . ' LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
    }

    protected function _build_rel_where($name)
    {
        $where_arr = array();
        if ($this->fk_relation[$name]['rel_where'])
        {
            $db = Xcrud_db::get_instance($this->connection);
            if (is_array($this->fk_relation[$name]['rel_where']))
            {
                foreach ($this->fk_relation[$name]['rel_where'] as $field => $val)
                {
                    $val = preg_replace_callback('/\{(.+)\}/Uu', array($this, 'rel_where_callback'), $val);
                    $where_arr[] = $this->_field_from_where($field) . $this->_cond_from_where($field) . $db->escape($val);
                }
            }
            else
            {
                $where_arr[] = preg_replace_callback('/\{(.+)\}/Uu', array($this, 'rel_where_callback'), $this->fk_relation[$name]['rel_where']);
            }
            return implode(' AND ', $where_arr);
        }
        else
        {
            return 1;
        }
    }
    protected function _build_rel_ins_where($name)
    {
        $where_arr = array();
        if ($this->fk_relation[$name]['add_data'])
        {
            $db = Xcrud_db::get_instance($this->connection);
            if (is_array($this->fk_relation[$name]['add_data']))
            {
                foreach ($this->fk_relation[$name]['add_data'] as $field => $val)
                {
                    $val = preg_replace_callback('/\{(.+)\}/Uu', array($this, 'rel_where_callback'), $val);
                    $where_arr[] = $this->_field_from_where($field) . $this->_cond_from_where($field) . $db->escape($val);
                }
            }
            else
            {
                $where_arr[] = preg_replace_callback('/\{(.+)\}/Uu', array($this, 'rel_where_callback'), $this->fk_relation[$name]['add_data']);
            }
            return implode(' AND ', $where_arr);
        }
        else
        {
            return 1;
        }
    }
    protected function rel_where_callback($matches)
    {
        if (strpos($matches[1], '.'))
        {
            $tmp = explode('.', $matches[1]);
            return '`' . $this->prefix . $tmp[0] . '`.`' . $tmp[1] . '`';
        }
        else
        {
            return '`' . $this->table . '`.`' . $matches[1] . '`';
        }
    }

    /** receiving user data */
    protected function _receive_post($task = false, $primary = false)
    {
        if (!$this->table_name && !$this->query)
            $this->table_name = $this->_humanize(mb_substr($this->table, mb_strlen($this->prefix)));
        if ($task)
        {
            switch ($task)
            {
                case 'create':
                    $this->task = $task;
                    $this->before = $task;
                    return;
                    break;
                case 'edit':
                case 'view':
                    if ($primary !== false)
                    {
                        $this->task = $task;
                        $this->before = $task;
                        $this->primary_val = $primary;
                        return;
                    }
                    break;
                case 'list':
                    $this->task = $task;
                    return;
                    break;
            }
        }
        else
        {
            $this->task = $this->_post('task', 'list');
        }
        if ($this->is_get)
        {
            $this->task = $this->_get('task');
            $this->primary_val = $this->_get('primary');
        }
        else
        {
            $this->order_column = $this->_post('orderby', false, 'key');
            //var_dump($this->order_column);
            $this->order_direct = $this->_post('order') == 'desc' ? 'desc' : 'asc';
            if ($this->order_column)
            {
                if (!$this->query)
                    $this->order_column = key($this->_parse_field_names($this->order_column, 'receive_post', false, false));
                if (isset($this->order_by[$this->order_column]))
                    unset($this->order_by[$this->order_column]);
                $this->order_by = array_merge(array($this->order_column => $this->order_direct), $this->order_by);
            }
            //var_dump($this->order_column);

            $this->search = $this->_post('search', $this->search, 'int');
            if ($this->search)
            {
                $this->column = $this->_post('column', false, 'key');
                $this->phrase = $this->_post('phrase');
                $this->range = $this->_post('range', '');
            }
            $this->start = $this->_post('start', 0, 'int');
            $this->limit = $this->_post('limit', ($this->limit ? $this->limit : Xcrud_config::$limit));
            $this->after = $this->_post('after');
            $this->primary_val = $this->_post('primary');

        }
    }
    protected function _build_order_by()
    {
        if (count($this->order_by))
        {
            $order_arr = array();
            foreach ($this->order_by as $field => $direction)
            {
                if ($direction === false)
                {
                    $order_arr[] = $field;
                }
                elseif (isset($this->relation[$field]))
                {
                    $order_arr[] = '`rel.' . $field . '` ' . $direction;
                }
                elseif (isset($this->subselect[$field]) or isset($this->columns[$field]) or isset($this->no_select[$field]) or !strpos($field,
                    '.') or isset($this->fk_relation[$field]))
                {
                    $order_arr[] = '`' . $field . '` ' . $direction;
                }
                else
                {
                    $tmp = explode('.', $field);
                    $order_arr[] = '`' . $tmp[0] . '`.`' . $tmp[1] . '` ' . $direction;
                }
            }
            return 'ORDER BY ' . implode(',', $order_arr);
        }
        else
        {
            /*if (isset($this->columns[$this->table . '.' . $this->primary_key]))
            {
            $this->order_by[$this->table . '.' . $this->primary_key] = 'ASC';
            return "ORDER BY `{$this->table}.{$this->primary_key}` ASC";
            }
            else
            return "ORDER BY `{$this->table}`.`{$this->primary_key}` ASC";*/
            return '';
        }
    }
    protected function _build_limit($total)
    {
        if ($this->limit != 'all' && $this->theme != 'printout')
        {
            if ($this->start > 0 && $this->start >= $this->result_total)
            {
                $this->start = $this->result_total > $this->limit ? $this->result_total - $this->limit : 0;
            }
            $this->start = floor($this->start / $this->limit) * $this->limit;
            return "LIMIT {$this->start},{$this->limit}";
        }
        else
        {
            $this->start = 0;
            return '';
        }
    }
    /** informatiuon about table columns */
    protected function _get_table_info()
    {
        $this->table_info = array();
        $db = Xcrud_db::get_instance($this->connection);
        $db->query("SHOW COLUMNS FROM `{$this->table}`");
        $this->table_info[$this->table] = $db->result();
        if ($this->join)
        {
            foreach ($this->join as $alias => $join)
            {
                $db->query("SHOW COLUMNS FROM `{$join['join_table']}`");
                $this->table_info[$alias] = $db->result();
            }
        }
        return true;
    }
    protected function _set_field_types($mode = 'create', $all_fields = false)
    {
        if (is_array($this->table_info) && count($this->table_info))
        {
            $uni = false;
            $this->primary_ai = false;
            $fields = array();
            foreach ($this->table_info as $table => $types)
            {
                foreach ($types as $row)
                {
                    $field_index = $table . '.' . $row['Field'];
                    if (!$all_fields)
                    {
                        $fields_object_name = 'fields_' . $mode;
                        $fields_object = $this->$fields_object_name;
                    }
                    else
                    {
                        $fields_object = array();
                    }

                    $this->field_null[$field_index] = $row['Null'] == 'YES' ? true : false;
                    if (!$this->field_null[$field_index] && Xcrud_config::$not_null_is_required && !isset($this->validation_required[$field_index]))
                    {
                        $this->validation_required[$field_index] = 1;
                    }
                    if ($row['Type'] == 'point')
                    {
                        $this->point_field[$field_index] = true;
                    }

                    if ($row['Key'] == 'PRI' or $row['Key'] == 'UNI')
                    {
                        $this->unique[$field_index] = true;
                        if ($table == $this->table && !$uni)
                        {
                            $uni = $row['Field'];
                        }
                    }

                    if ($row['Key'] == 'PRI' && $row['Extra'] == 'auto_increment')
                    {
                        if ($table == $this->table)
                        {
                            $this->primary_ai = true;
                            if (!$this->primary_key)
                            {
                                $this->primary_key = $row['Field'];
                            }
                        }

                        if (((!$this->show_primary_ai_column && $mode == 'list') or (!$this->show_primary_ai_field && $mode != 'list')) && !
                            isset($fields_object[$field_index]))
                        {
                            if (!isset($this->field_type[$field_index]))
                            {
                                $this->_define_field_type($row, $field_index);
                            }
                            continue;
                        }
                        else
                        {
                            $this->disabled[$field_index] = $this->parse_mode(false);
                        }

                    }


                    if ($this->join && isset($this->join[$table]) && $this->join[$table]['join_field'] == $row['Field'])
                    {
                        if (!isset($this->field_type[$field_index]))
                        {
                            $this->_define_field_type($row, $field_index);
                        }
                        continue;
                    }

                    if (!$fields_object)
                    {
                        $fields[$field_index] = array(
                            'table' => $table,
                            'field' => $row['Field'],
                            'tab' => '');
                    }
                    elseif ($fields_object && isset($this->reverse_fields[$mode]) && $this->reverse_fields[$mode])
                    {
                        if (!isset($fields_object[$field_index]))
                        {
                            $fields[$field_index] = array(
                                'table' => $table,
                                'field' => $row['Field'],
                                'tab' => '');
                        }
                    }
                    elseif (isset($fields_object[$field_index]))
                    {
                        $fields[$field_index] = $fields_object[$field_index];
                    }

                    if (isset($this->relation[$field_index]))
                    {
                        $this->field_type[$field_index] = 'relation';
                        if (!isset($this->defaults[$field_index]))
                        {
                            $this->defaults[$field_index] = $row['Default'];
                        }
                        $this->_define_field_type($row, $field_index);
                        continue;
                    }

                    $this->_define_field_type($row, $field_index);

                }
            }

            // resorting
            if ($fields_object && (!isset($this->reverse_fields[$mode]) || !$this->reverse_fields[$mode]))
            {
                $fields = array_merge($fields_object, $fields);
                if ($mode == 'list')
                {
                    $this->columns = $fields;
                }
                else
                {
                    $this->fields = $fields;
                }
            }
            else
            {
                if ($mode !== 'view' && $mode != 'list')
                {
                    $fields = array_merge($fields, $this->custom_fields);
                }

                $fk_before = array();
                if ($this->fk_relation)
                {
                    foreach ($this->fk_relation as $fk)
                    {
                        $fk_before[$fk['alias']] = $fk['before'];
                    }
                }

                if ($mode == 'list')
                {
                    $subselect_before = $this->subselect_before;

                    foreach ($fields as $field_index => $field)
                    {
                        // subselect
                        if ($name = array_search($field_index, $subselect_before))
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->columns[$name] = reset($fdata);
                            }
                            unset($subselect_before[$name]);
                        }
                        if ($name = array_search($field_index, $fk_before))
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->columns[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                        $this->columns[$field_index] = $field;
                    }
                    if (count($subselect_before))
                    {
                        foreach ($subselect_before as $name => $before)
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->columns[$name] = reset($fdata);
                            }
                            unset($subselect_before[$name]);
                        }
                    }
                    if (count($fk_before))
                    {
                        foreach ($fk_before as $name => $before)
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->columns[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                    }
                }
                elseif ($mode != 'create')
                {
                    $subselect_before = $this->subselect_before;
                    foreach ($fields as $field_index => $field)
                    {
                        // subselect
                        if ($name = array_search($field_index, $subselect_before))
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($subselect_before[$name]);
                        }
                        if ($name = array_search($field_index, $fk_before))
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                        $this->fields[$field_index] = $field;
                    }
                    if (count($subselect_before))
                    {
                        foreach ($subselect_before as $name => $before)
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($subselect_before[$name]);
                        }

                    }
                    if (count($fk_before))
                    {
                        foreach ($fk_before as $name => $before)
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                    }
                }
                elseif ($fk_before)
                {
                    foreach ($fields as $field_index => $field)
                    {
                        if ($name = array_search($field_index, $fk_before))
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                        $this->fields[$field_index] = $field;
                    }
                    if (count($fk_before))
                    {
                        foreach ($fk_before as $name => $before)
                        {
                            if (!isset($fields_object[$name]))
                            {
                                $fdata = $this->_parse_field_names($name, 'set_field_types');
                                $this->fields[$name] = reset($fdata);
                            }
                            unset($fk_before[$name]);
                        }
                    }
                }
                else
                {
                    $this->fields = $fields;
                }
            }

            //echo'<pre>'.print_r($fields,true).'</pre>';

            if (!$this->primary_key)
            {
                if ($uni)
                    $this->primary_key = $uni;
                else
                { // changed to prevent data rewriting
                    //$this->primary_key = $this->table_info[$this->table][0]['Field'];
                    switch ($this->task)
                    {
                        case 'list':
                        case 'action':
                        case 'print':
                        case 'csv':
                            $this->is_edit = false;
                            $this->is_remove = false;
                            $this->is_create = false;
                            $this->is_view = false;
                            //$this->is_search = false;
                            break;
                        default:
                            self::error('<strong>Table "' . $this->table . '" has no any primary or unique key!</strong><br />
                                This error was made to prevent loss of your data.
                                You must create primary key (the best - primary autoincrement) for this table.
                                See documentation for more info.');
                            break;
                    }
                }
            }
            unset($fields);
        }
    }
    protected function _define_field_type($row, $field_index)
    {
        if (preg_match('/^([A-Za-z]+)\((.+)\)/u', $row['Type'], $matches))
        {
            $type = strtolower($matches[1]);
            $max_l = $matches[2];
        }
        else
        {
            $type = strtolower($row['Type']);
            $max_l = null;
        }
        if (!isset($this->field_attr[$field_index]))
        {
            $this->field_attr[$field_index] = array();
        }
        if ($max_l && (!isset($this->field_attr[$field_index]['maxlength']) or !$this->field_attr[$field_index]['maxlength']))
        {
            $this->field_attr[$field_index]['maxlength'] = (int)$max_l;
        }

        switch ($type)
        {
            case 'tinyint':
            case 'bit':
            case 'bool':
            case 'boolean':
                if ($type == 'bit')
                {
                    $this->bit_field[$field_index] = 1;
                }
                else
                {
                    $this->int_field[$field_index] = 1;
                }

                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                if ($max_l == 1 && Xcrud_config::$make_checkbox)
                {
                    $this->field_type[$field_index] = 'bool';
                    if (!isset($this->defaults[$field_index]))
                        $this->defaults[$field_index] = $row['Default'];
                }
                else
                {
                    $this->field_type[$field_index] = 'int';
                    //$this->field_attr[$field_index]['maxlength'] = (int)$max_l;
                    if (!isset($this->defaults[$field_index]))
                        $this->defaults[$field_index] = $row['Default'];
                }
                break;
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'serial':
                $this->int_field[$field_index] = 1;
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'int';
                //$this->field_attr[$field_index]['maxlength'] = (int)$max_l;
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'decimal':
            case 'numeric':
            case 'float':
            case 'double':
            case 'real':
                $this->float_field[$field_index] = 1;
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'float';
                //if ($max_l)
                //    $this->field_attr[$field_index]['maxlength'] = (int)$max_l + 1;
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'char':
            case 'varchar':
            case 'binary':
            case 'varbinary':
            default:
                $this->text_field[$field_index] = 1;
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'text';
                //$this->field_attr[$field_index]['maxlength'] = (int)$max_l;
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'text':
            case 'tinytext':
            case 'mediumtext':
            case 'longtext':
                $this->text_field[$field_index] = 1;
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                if (!isset($this->no_editor[$field_index]) && Xcrud_config::$auto_editor_insertion)
                    $this->field_type[$field_index] = 'texteditor';
                else
                    $this->field_type[$field_index] = 'textarea';
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'blob':
            case 'tinyblob':
            case 'mediumblob':
            case 'longblob':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'binary';
                $this->defaults[$field_index] = '';
                break;
            case 'date':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'date';
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'datetime':
            case 'timestamp':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'datetime';
                if (!isset($this->defaults[$field_index]))
                {
                    if ($row['Default'] == 'CURRENT_TIMESTAMP')
                    {
                        $db = Xcrud_db::get_instance($this->connection);
                        $db->query('SELECT NOW() AS `now`');
                        $tmstmp = $db->row();
                        $this->defaults[$field_index] = $tmstmp['now'];
                    }
                    else
                        $this->defaults[$field_index] = $row['Default'];
                }
                break;
            case 'time':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'time';
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'year':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'year';
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'enum':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = Xcrud_config::$enum_as_radio ? 'radio' : 'select';
                $this->field_attr[$field_index]['values'] = $max_l;
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'set':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = Xcrud_config::$set_as_checkboxes ? 'checkboxes' : 'multiselect';
                $this->field_attr[$field_index]['values'] = $max_l;
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = $row['Default'];
                break;
            case 'point':
                if (isset($this->field_type[$field_index]))
                {
                    return;
                }
                $this->field_type[$field_index] = 'point';
                $this->field_attr[$field_index] = array( // defaults
                    'text' => Xcrud_config::$default_text,
                    'search_text' => Xcrud_config::$default_search_text,
                    'zoom' => Xcrud_config::$default_zoom,
                    'width' => Xcrud_config::$default_width,
                    'height' => Xcrud_config::$default_height,
                    'search' => Xcrud_config::$default_coord,
                    'coords' => Xcrud_config::$default_search);
                $this->validation_pattern[$field_index] = 'point';
                if (!isset($this->defaults[$field_index]))
                    $this->defaults[$field_index] = Xcrud_config::$default_point ? Xcrud_config::$default_point : '0,0';
                break;
        }
    }

    protected function _set_column_names()
    {
        $subselect_before = $this->subselect_before;
        foreach ($this->columns as $key => $col)
        {
            if ($name = array_search($key, $subselect_before))
            {
                $this->columns_names[$name] = $this->html_safe($this->labels[$name]);
                unset($subselect_before[$name]);
            }
            if (isset($this->column_name[$key]))
            {
                $this->columns_names[$key] = $this->html_safe($this->column_name[$key]);
            }
            elseif (isset($this->labels[$key]))
            {
                $this->columns_names[$key] = $this->html_safe($this->labels[$key]);
            }
            elseif ($this->fk_relation && isset($this->fk_relation[$key]))
            {
                $this->columns_names[$key] = $this->fk_relation[$key]['label'];
            }
            else
            {
                $this->columns_names[$key] = $this->html_safe($this->_humanize($col['field']));
            }
        }
        if ($subselect_before)
        {
            foreach ($this->subselect_before as $name => $none)
            {
                $this->columns_names[$name] = $this->html_safe($this->labels[$name]);
                unset($subselect_before[$name]);
            }
        }
    }
    protected function _set_field_names()
    {
        foreach ($this->fields as $key => $field)
        {
            if (isset($this->labels[$key]))
                $this->fields_names[$key] = $this->html_safe($this->labels[$key]) . (isset($this->validation_required[$key]) ? '&#42;' :
                    '');
            else
                $this->fields_names[$key] = $this->html_safe($this->_humanize($field['field'])) . (isset($this->validation_required[$key]) ?
                    '&#42;' : '');
        }
    }
    protected function _render_list()
    {
        if (count($this->order_by))
        {
            reset($this->order_by);
            $this->order_column = key($this->order_by);
            $this->order_direct = strtolower($this->order_by[$this->order_column]);
        }
        else
        {
            //$this->order_column = $this->table . '.' . $this->primary_key;
            //$this->order_direct = 'asc';
        }

        if ($this->column === false)
        {
            if ($this->search_default)
            {
                $this->column = $this->search_default;
            }
            elseif (!Xcrud_config::$search_all)
            {
                if ($this->search_columns)
                {
                    $this->column = key($this->search_columns);
                }
                else
                {
                    $this->column = key($this->columns);
                }
            }
        }
        $mode = 'list';
        $view_file = XCRUD_PATH . '/' . Xcrud_config::$themes_path . '/' . $this->theme . '/' . $this->load_view['list'];
        $this->check_file($view_file, 'render');
        ob_start();
        include ($view_file);
        $this->data = ob_get_contents();
        ob_end_clean();
        return $this->render_output();
    }

    /** renders details view template */
    protected function _render_details($mode)
    {
        if (count($this->order_by))
        {
            $order_direct = strtolower(reset($this->order_by));
            $order_column = key($this->order_by);
        }
        else
        {
            $order_column = $this->table . '.' . $this->primary_key;
            $order_direct = 'asc';
        }

        /*        if ($mode == 'create')
        {
        $this->disabled = $this->disabled_on_create;
        $this->readonly = $this->readonly_on_create;
        }
        elseif ($mode == 'edit')
        {
        $this->disabled = $this->disabled_on_edit;
        $this->readonly = $this->readonly_on_edit;
        }
        */
        if (isset($this->result_row['primary_key']))
        {
            $this->primary_val = $this->result_row['primary_key'];
        }
        if ($this->result_row)
        {
            foreach ($this->fields as $field => $fitem)
            {
                if (isset($this->custom_fields[$field]))
                {
                    $this->result_row[$field] = $this->defaults[$field];
                }
                if ($this->field_type[$field] == 'hidden')
                {
                    $this->hidden_fields_output[$field] = $this->create_hidden($field, $this->result_row[$field]);
                }
                else
                {

                    if (isset($this->field_callback[$field]) && $mode != 'view')
                    {
                        $path = $this->check_file($this->field_callback[$field]['path'], 'field_callback');
                        include_once ($path);
                        if (is_callable($this->field_callback[$field]['callback']))
                        {
                            $this->fields_output[$field] = array(
                                'label' => $this->fields_names[$field],
                                'field' => call_user_func_array($this->field_callback[$field]['callback'], array(
                                    $this->result_row[$field],
                                    $field,
                                    $mode,
                                    $this->result_row,
                                    $this)),
                                'name' => $field,
                                'value' => $this->result_row[$field]);
                        }
                    }
                    elseif (isset($this->column_callback[$field]) && $mode == 'view')
                    {
                        $path = $this->check_file($this->column_callback[$field]['path'], 'column_callback');
                        include_once ($path);
                        if (is_callable($this->column_callback[$field]['callback']) && $this->result_row)
                        {
                            $this->fields_output[$field] = array(
                                'label' => $this->fields_names[$field],
                                'field' => call_user_func_array($this->column_callback[$field]['callback'], array(
                                    $this->result_row[$field],
                                    $field,
                                    $this->primary_val,
                                    $this->result_row,
                                    $this)),
                                'name' => $field,
                                'value' => $this->result_row[$field]);
                        }

                    }
                    else
                    {
                        $attr = $this->get_field_attr($field, $mode);
                        if ($mode == 'view')
                        {
                            $func = 'create_view_' . $this->field_type[$field];
                        }
                        else
                        {
                            $func = 'create_' . $this->field_type[$field];
                        }
                        if (!method_exists($this, $func))
                            continue;
                        $this->fields_output[$field] = array(
                            'label' => $this->fields_names[$field],
                            'field' => call_user_func_array(array($this, $func), array(
                                $field,
                                $this->result_row[$field],
                                $attr)),
                            'name' => $field,
                            'value' => $this->result_row[$field]);
                        if (isset($this->column_pattern[$field]) && $mode == 'view')
                        {
                            $this->fields_output[$field]['field'] = str_ireplace('{value}', $this->fields_output[$field]['field'], $this->
                                column_pattern[$field]);
                            $this->fields_output[$field]['field'] = $this->replace_text_variables($this->fields_output[$field]['field'], $this->
                                result_row, true);
                        }
                    }
                }
            }
        }

        if ($this->inner_table_instance && ($mode == 'view' or $mode == 'edit')) // restoring nested objects
        {
            foreach ($this->inner_table_instance as $inst_name => $field)
            {
                if (isset($this->result_row[$field]))
                {
                    $instance = self::get_instance($inst_name);
                    $instance->ajax_request = true;
                    $instance->import_vars();
                    $instance->inner_where($this->result_row[$field]);

                    if ($mode == 'view' && Xcrud_config::$nested_readonly_on_view)
                    {
                        $instance->table_ro = true;
                    }
                    else
                    {
                        $instance->table_ro = false;
                    }
                    $this->nested_rendered[$inst_name] =
                        '<div class="xcrud-nested-container xcrud-container"><div class="xcrud-ajax" id="xcrud-ajax-' . base_convert(rand(), 10,
                        36) . '">' . $instance->render('list') . '</div></div>';
                }
            }
        }

        $view_file = XCRUD_PATH . '/' . Xcrud_config::$themes_path . '/' . $this->theme . '/' . $this->load_view[$mode];
        $this->check_file($view_file, 'render');
        ob_start();
        include ($view_file);
        $this->data = $this->render_search_hidden() . ob_get_contents();
        ob_end_clean();
        /*if ($this->inner_table_instance && ($mode == 'view' or $mode == 'edit')) // restoring nested objects
        {
        foreach ($this->inner_table_instance as $inst_name => $field)
        {
        if (isset($this->result_row[$field]))
        {
        $instance = self::get_instance($inst_name);
        $instance->ajax_request = true;
        $instance->import_vars();
        $instance->inner_where($this->result_row[$field]);

        if ($mode == 'view' && Xcrud_config::$nested_readonly_on_view)
        {
        $instance->table_ro = true;
        }
        else
        {
        $instance->table_ro = false;
        }
        //$this->data .= '<div class="xcrud-nested-container xcrud-container"><div class="xcrud-ajax" id="xcrud-ajax-' .
        //    base_convert(rand(), 10, 36) . '">' . $instance->render('list') . '</div></div>';
        $this->nested_rendered[$inst_name] = '<div class="xcrud-nested-container xcrud-container"><div class="xcrud-ajax" id="xcrud-ajax-' .
        base_convert(rand(), 10, 36) . '">' . $instance->render('list') . '</div></div>';
        }
        }
        }*/
        if ($this->nested_rendered)
        {
            $this->data .= implode('', $this->nested_rendered);
        }
        return $this->render_output();
    }
    /** defines nested main condition, must be public. Only for internal usage. */
    public function inner_where($value = false)
    {
        if ($value !== false)
        {
            $this->inner_value = $value;
        }
        // nested table connection
        if ($this->is_inner && $this->inner_where)
        {
            $field = reset($this->inner_where);
            $this->where_pri($field, $this->inner_value, 'AND', 'nstd_tbl');
            $this->pass_default($field, $this->inner_value);
        }
    }
    protected function _pagination($total, $start, $limit, $numpos = 10, $numlr = 2)
    {
        if ($total > $limit)
        {
            $pages = ceil($total / $limit);
            $curent = ceil(($start + $limit) / $limit);
            $links = array();
            /*for ($i = 1; $i <= $pages; ++$i)
            {
            $limit1 = $i * $limit - $limit;
            if ($i == $curent)
            $links[$i] = '<li class="' . $this->theme_config('pagination_active') . '"><span>' . $i . '</span></li>';
            else
            {
            $links[$i] = '<li class="' . $this->theme_config('pagination_item') .
            '"><a href="javascript:;" class="' . $this->theme_config('pagination_link') . ' xcrud-action" data-start="' . $limit1 . '">' . $i . '</a></li>';
            }
            }*/
            $html = '<ul class="' . $this->theme_config('pagination_container') . '" style="    float: right;">';
            if ($pages > $numpos)
            {

                if ($curent <= $numlr + 3)
                {
                    for ($i = 1; $i <= $numpos - $numlr - 1; ++$i)
                    {
                        $html .= $this->_pagination_item($i, $curent, $limit);
                    }
                    $html .= '<li class="' . $this->theme_config('pagination_dots') . '"><span>&#133;</span></li>';
                    for ($i = $pages - $numlr + 1; $i <= $pages; ++$i)
                    {
                        $html .= $this->_pagination_item($i, $curent, $limit);
                    }
                }
                else
                    if ($curent >= $pages - $numlr - 2)
                    {
                        for ($i = 1; $i <= $numlr; ++$i)
                        {
                            $html .= $this->_pagination_item($i, $curent, $limit);
                        }
                        $html .= '<li class="' . $this->theme_config('pagination_dots') . '"><span>&#133;</span></li>';
                        for ($i = $pages - $numpos + $numlr + 2; $i <= $pages; ++$i)
                        {
                            $html .= $this->_pagination_item($i, $curent, $limit);
                        }
                    }
                    else
                    {
                        for ($i = 1; $i <= $numlr; ++$i)
                        {
                            $html .= $this->_pagination_item($i, $curent, $limit);
                        }
                        $html .= '<li class="' . $this->theme_config('pagination_dots') . '"><span>&#133;</span></li>';
                        $offset = floor(($numpos - $numlr - $numlr - 1) / 2);
                        for ($i = $curent - $offset; $i <= $curent + $offset; ++$i)
                        {
                            $html .= $this->_pagination_item($i, $curent, $limit);
                        }

                        $html .= '<li class="' . $this->theme_config('pagination_dots') . '"><span>&#133;</span></li>';
                        for ($i = $pages - $numlr + 1; $i <= $pages; ++$i)
                        {
                            $html .= $this->_pagination_item($i, $curent, $limit);
                        }
                    }

            }
            else
            {
                //$html .= implode('', $links);
                for ($i = 1; $i <= $pages; ++$i)
                {
                    $html .= $this->_pagination_item($i, $curent, $limit);
                }
            }
            $html .= '</ul>';
            return $html;
        }
    }
    protected function _pagination_item($i, $curent, $limit)
    {
        $limit1 = $i * $limit - $limit;
        if ($i == $curent)
            return '<li class="' . $this->theme_config('pagination_active') . '"><span class="' . $this->theme_config('pagination_link') . '">' . $i . '</span></li>';
        else
        {
            return '<li class="' . $this->theme_config('pagination_item') .
                '"><a href="javascript:;" class="' . $this->theme_config('pagination_link') . ' xcrud-action" data-start="' . $limit1 . '">' . $i . '</a></li>';
        }
    }
    protected function _cut($string, $field, $wordsafe = true, $dots = true)
    {
        if (isset($this->column_cut_list[$field]))
        {
            $len = $this->column_cut_list[$field]['count'];
            $safe = $this->column_cut_list[$field]['safe'];
        }
        else
        {
            $len = $this->column_cut;
            $safe = $this->safe_output;
        }

        $string = html_entity_decode($string, ENT_QUOTES, Xcrud_config::$mbencoding);

        if (!$len)
        {
            return $this->output_string($string, $this->strip_tags, $safe);
        }
        $strip_string = trim(strip_tags($string));
        $slen = mb_strlen($strip_string, Xcrud_config::$mbencoding);
        if ($slen <= $len || (Xcrud_config::$print_full_texts && $this->theme == 'printout'))
        {
            return $this->output_string($string, $this->strip_tags, $safe);
        }
        if ($wordsafe)
        {
            $end = $len;
            while ((mb_substr($strip_string, --$len, 1, Xcrud_config::$mbencoding) != ' ') && ($len > 0))
            {
            }
            if ($len == 0)
            {
                $len = $end;
            }
            return $this->output_string(mb_substr($strip_string, 0, $len, Xcrud_config::$mbencoding), false, $safe) . ($dots ?
                '&#133;' : '');
        }
        return $this->output_string(mb_substr($strip_string, 0, $len, Xcrud_config::$mbencoding), false, $safe) . ($dots ?
            '&#133;' : '');
    }
    protected function output_string($string, $strip, $safe)
    {
        if ($strip)
        {
            $string = strip_tags($string);
        }
        if ($safe)
        {
            $string = $this->html_safe($string);
        }
        return $string;
    }
    protected function _humanize($text)
    {
        return mb_convert_case(str_replace('_', ' ', $text), MB_CASE_TITLE, Xcrud_config::$mbencoding);
    }
    protected function _regenerate_key()
    {
        switch ($this->task)
        {
            case 'file':
            case 'depend':
            case 'print':
            case 'csv':
            case 'upload':
            case 'remove_upload':
            case 'crop_image':
            case 'unique':
                break;
            default:
                $this->key = sha1(microtime() . rand());
                break;
        }
    }

    public function _export_vars()
    {

        $inst_name = $this->instance_name;
        $this->time = $time = time();
        // session auto-clearing, must start on first instance
        if ($this->instance_count == 1 && !$this->ajax_request)
        {
            if (isset($_SESSION['lists']['xcrud_session']) && $_SESSION['lists']['xcrud_session'])
            {
                foreach ($_SESSION['lists']['xcrud_session'] as $s_key => $s_val)
                { // workaround on some servers session duplication
                    $old_time = isset($s_val['time']) ? (int)$s_val['time'] : 0;
                    if ($time > $old_time + Xcrud_config::$autoclean_timeout) // autocleaner

                        unset($_SESSION['lists']['xcrud_session'][$s_key]);
                }
            }
        }
        $this->condition_restore();

        foreach ($this->params2save() as $item)
        {
            $_SESSION['lists']['xcrud_session'][$inst_name][$item] = $this->{$item};
        }
        $_SESSION['lists']['xcrud_session'][$inst_name]['before'] = $this->find_prev_task();

        if (Xcrud_config::$alt_session)
        {
            $data = $this->encrypt($_SESSION['lists']['xcrud_session']);

            if (class_exists('Memcache'))
            {
                $mc = new Memcache();
                $mc->connect(Xcrud_config::$mc_host, Xcrud_config::$mc_port);
                $res = $mc->set(self::$sess_id, $data, false, Xcrud_config::$alt_lifetime * 60);
            }
            elseif (class_exists('Memcached'))
            {
                $mc = new Memcached();
                $mc->connect(Xcrud_config::$mc_host, Xcrud_config::$mc_port);
                $res = $mc->set(self::$sess_id, $data, Xcrud_config::$alt_lifetime * 60);
            }
            else
            {
                self::error('Can\'t use alternative session. Memcache(d) is not available');
            }
            unset($_SESSION['lists']['xcrud_session']);
            if (!$res)
            {
                self::error('Can\'t use alternative session. Memcache(d) has invalid parameters or broken. Storing failed');
            }
        }

    }


    protected function params2save()
    {
        return array(
            'key',
            'time',
            'table',
            'table_name',
            'where',
            'order_by',
            'relation',
            'fields_create',
            'fields_edit',
            'fields_view',
            'fields_list',
            'labels',
            'columns_names',
            'is_create',
            'is_edit',
            'is_remove',
            'is_csv',
            'buttons',
            'validation_required',
            'validation_pattern',
            'before_insert',
            'before_update',
            'before_remove',
            'after_insert',
            'after_update',
            'after_remove',
            'field_type',
            'field_attr',
            'limit',
            'limit_list',
            'column_cut',
            'column_cut_list',
            'no_editor',
            'show_primary_ai_field',
            'show_primary_ai_column',
            'disabled',
            'readonly',
            'benchmark',
            'search_pattern',
            'connection',
            'remove_confirm',
            'upload_folder',
            'upload_config',
            'pass_var',
            'reverse_fields',
            'no_quotes',
            'inner_table_instance',
            'inner_where',
            'unique',
            'theme',
            'is_duplicate',
            'links_label',
            'emails_label',
            'sum',
            'alert_create',
            'alert_edit',
            'is_search',
            'is_print',
            'is_pagination',
            'is_limitlist',
            'is_sortable',
            'is_list',
            'subselect',
            'subselect_before',
            'highlight',
            'highlight_row',
            'modal',
            'column_class',
            'no_select',
            'is_inner',
            'join',
            'fk_relation',
            'is_title',
            'is_numbers',
            'language',
            'field_params',
            'mass_alert_create',
            'mass_alert_edit',
            'column_callback',
            'field_callback',
            'replace_insert',
            'replace_update',
            'replace_remove',
            'send_external_create',
            'send_external_edit',
            'column_pattern',
            'field_tabs',
            'field_marker',
            'is_view',
            'field_tooltip',
            'table_tooltip',
            'column_tooltip',
            'search_columns',
            'search_default',
            'column_width',
            'before',
            'before_upload',
            'after_upload',
            'after_resize',
            'custom_vars',
            'tabdesc',
            'column_name',
            'upload_to_save',
            'upload_to_remove',
            'defaults',
            'search',
            'inner_value',
            'bit_field',
            'point_field',
            'buttons_position',
            'grid_condition',
            'condition',
            'hide_button',
            'set_lang',
            'table_ro',
            'grid_restrictions',
            'load_view',
            'action',
            'prefix',
            'query',
            'default_tab',
            'strip_tags',
            'safe_output',
            'before_list',
            'before_create',
            'before_edit',
            'before_view',
            'lists_null_opt',
            'custom_fields',
            'date_format');
    }

    protected function find_prev_task()
    {
        switch ($this->task)
        {
            case 'create':
            case 'view':
            case 'edit':
            case 'list':
                return $this->task;
                break;
            case '':
                return 'list';
                break;
            default:
                return ($this->before ? $this->before : 'list');
                break;
        }
    }

    public function import_vars($key = false)
    {
        if (Xcrud_config::$alt_session)
        {
            if (class_exists('Memcache'))
            {
                $mc = new Memcache();
                $mc->connect(Xcrud_config::$mc_host, Xcrud_config::$mc_port);
                $data = $mc->get(self::$sess_id);
            }
            elseif (class_exists('Memcached'))
            {
                $mc = new Memcached();
                $mc->connect(Xcrud_config::$mc_host, Xcrud_config::$mc_port);
                $data = $mc->get(self::$sess_id);
            }
            else
            {
                self::error('Can\'t use alternative session. Memcache(d) is not available');
            }
            if (!$data)
            {
                self::error('Can\'t use alternative session. Data is not exist');
            }
            $_SESSION['lists']['xcrud_session'] = $this->decrypt($data[0], $data[1]);
            unset($data);
            if (!$_SESSION['lists']['xcrud_session'])
            {
                self::error('Can\'t use alternative session. Data is invalid');
            }
        }

        $inst_name = $this->instance_name;

        foreach ($this->params2save() as $item)
        {
            $this->{$item} = $_SESSION['lists']['xcrud_session'][$inst_name][$item];
        }

        if ($key)
        {
            $this->key = $key;
        }
    }
    protected function get_field_attr($name, $mode)
    {
        $tag = array('class' => 'xcrud-input');
        if (isset($this->validation_required[$name]))
        {
            $tag['data-required'] = $this->validation_required[$name];
        }
        if (isset($this->exception_fields[$name]))
        {
            $tag['class'] .= ' validation-error';
        }
        if (isset($this->validation_pattern[$name]))
        {
            $tag['data-pattern'] = $this->validation_pattern[$name];
        }
        if (isset($this->readonly[$name][$mode]))
        {
            $tag['readonly'] = '';
        }
        if (isset($this->disabled[$name][$mode]))
        {
            $tag['disabled'] = '';
        }
        if (isset($this->unique[$name]))
        {
            $tag['data-unique'] = '';
        }
        if (isset($this->relation[$name]['depend_on']) && $this->relation[$name]['depend_on'])
        {
            $tag['data-depend'] = $this->relation[$name]['depend_on'];
        }
        return $tag;
    }
    protected function create_none($name, $value = '', $tag = array())
    {
        return '<span class="xcrud-none">' . $value . '</span>';
    }
    protected function create_view_none($name, $value = '')
    {
        return '<span class="xcrud-none">' . $value . '</span>';
    }
    protected function create_bool($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_bool($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'checkbox',
            'data-type' => 'bool',
            'value' => 1,
            'name' => $name);
        if ($value)
        {
            $tag['checked'] = '';
        }

        return $this->open_tag('div', $this->theme_config('checkbox_container')) . $this->open_tag('label') . $this->single_tag($tag,
            $this->theme_config('bool_field'), $this->field_attr[$name], true) . $this->close_tag('label') . $this->close_tag('div');
    }
    protected function create_view_bool($name, $value = '', $tag = array())
    {
        return (int)$value ? $this->lang('bool_on') : $this->lang('bool_off');
    }
    protected function create_int($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_int($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'int',
            'value' => $value,
            'name' => $name,
            'data-pattern' => 'integer');

        return $this->single_tag($tag, $this->theme_config('int_field'), $this->field_attr[$name], true);
    }
    protected function create_view_int($name, $value = '', $tag = array())
    {
        return $value;
    }
    protected function create_float($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_float($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'float',
            'value' => $value,
            'name' => $name,
            'data-pattern' => 'numeric');

        return $this->single_tag($tag, $this->theme_config('float_field'), $this->field_attr[$name], true);
    }
    protected function create_view_float($name, $value = '', $tag = array())
    {
        return $value;
    }
    protected function create_price($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_price($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'price',
            'value' => $this->cast_number_format($value, $name, true),
            'name' => $name,
            'data-pattern' => 'numeric');

        return $this->single_tag($tag, $this->theme_config('price_field'), $this->field_attr[$name], true);
    }
    protected function create_view_price($name, $value = '', $tag = array())
    {
        $out = '';
        $out .= $this->cast_number_format($value, $name);
        return $out;
    }
    protected function create_text($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_text($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'text',
            'value' => $value,
            'name' => $name);

        return $this->single_tag($tag, $this->theme_config('text_field'), $this->field_attr[$name], true);
    }
    protected function create_view_text($name, $value = '', $tag = array())
    {
        if (Xcrud_config::$clickable_list_links)
        {
            $value = $this->make_links($value);
            $value = $this->make_mailto($value);
        }
        return $value;
    }
    protected function create_textarea($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_textarea($name, $value);
        }
        $tag = $tag + array(
            'tag' => 'textarea',
            'data-type' => 'textarea',
            'name' => $name);

        return $this->open_tag($tag, $this->theme_config('textarea_field'), $this->field_attr[$name], true) . $this->html_safe($value) .
            $this->close_tag($tag);
    }
    protected function create_view_textarea($name, $value = '', $tag = array())
    {
        return $value;
    }
    protected function create_texteditor($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_texteditor($name, $value, $tag = array());
        }
        $tag = $tag + array(
            'tag' => 'textarea',
            'data-type' => 'texteditor',
            'name' => $name,
            'id' => 'editor_' . base_convert(rand(), 10, 36));
        $tag['class'] .= ' xcrud-texteditor';

        return $this->open_tag($tag, $this->theme_config('texteditor_field'), $this->field_attr[$name], true) . $this->
            html_safe($value) . $this->close_tag($tag);
    }
    protected function create_view_texteditor($name, $value = '', $tag = array())
    {
        return $value;
    }
    protected function create_date($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_date($name, $value, $tag = array());
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'date',
            'value' => $this->mysql2date($value),
            'name' => $name);
        $tag['class'] .= ' xcrud-datepicker';

        $r = isset($this->field_attr[$name]) ? $this->field_attr[$name] : '';
        if ($r)
        {
            if (isset($r['range_end']))
            {
                $fdata = $this->_parse_field_names($r['range_end'], 'create_date');
                $tag['data-rangeend'] = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            }
            if (isset($r['range_start']))
            {
                $fdata = $this->_parse_field_names($r['range_start'], 'create_date');
                $tag['data-rangestart'] = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            }
        }
        return $this->single_tag($tag, $this->theme_config('date_field'), $this->field_attr[$name], true);
    }
    protected function create_view_date($name, $value = '', $tag = array())
    {
        return $this->mysql2date($value);
    }
    protected function create_datetime($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_datetime($name, $value, $tag = array());
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'datetime',
            'value' => $this->mysql2datetime($value),
            'name' => $name);
        $tag['class'] .= ' xcrud-datepicker';

        return $this->single_tag($tag, $this->theme_config('datetime_field'), $this->field_attr[$name], true);
    }
    protected function create_view_datetime($name, $value = '', $tag = array())
    {
        return $this->mysql2datetime($value);
    }
    protected function create_timestamp($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_timestamp($name, $value, $tag = array());
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'timestamp',
            'value' => $this->mysql2datetime($value),
            'name' => $name);
        $tag['class'] .= ' xcrud-datepicker';

        return $this->single_tag($tag, $this->theme_config('timestamp_field'), $this->field_attr[$name], true);
    }
    protected function create_view_timestamp($name, $value = '', $tag = array())
    {
        return $this->mysql2datetime($value);
    }
    protected function create_time($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_time($name, $value, $tag);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'time',
            'value' => $this->mysql2time($value),
            'name' => $name);
        $tag['class'] .= ' xcrud-datepicker';

        return $this->single_tag($tag, $this->theme_config('time_field'), $this->field_attr[$name], true);
    }
    protected function create_view_time($name, $value = '', $tag = array())
    {
        return $this->mysql2time($value);
    }
    protected function create_year($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_year($name, $value, $tag);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'data-type' => 'year',
            'value' => (int)$value,
            'name' => $name);

        return $this->single_tag($tag, $this->theme_config('year_field'), $this->field_attr[$name], true);
    }
    protected function create_view_year($name, $value = '', $tag = array())
    {
        return $value;
    }
    protected function create_select($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_select($name, $value, $tag);
        }
        $out = '';
        $tag = $tag + array(
            'tag' => 'select',
            'data-type' => 'select',
            'name' => $name);

        $out .= $this->open_tag($tag, $this->theme_config('select_field'), $this->field_attr[$name], true);

        if (is_array($this->field_attr[$name]['values']))
        {
            foreach ($this->field_attr[$name]['values'] as $optkey => $opt)
            {
                if (is_array($opt))
                {
                    $out .= $this->open_tag(array('tag' => 'optgroup', 'label' => $optkey));
                    foreach ($opt as $k_key => $k_opt)
                    {
                        $opt_tag = array('tag' => 'option', 'value' => $k_key);
                        if ($k_key == $value)
                        {
                            $opt_tag['selected'] = '';
                        }
                        $out .= $this->open_tag($opt_tag) . $this->html_safe($k_opt) . $this->close_tag($opt_tag);
                    }
                    $out .= $this->close_tag('optgroup');
                }
                else
                {
                    $opt_tag = array('tag' => 'option', 'value' => $optkey);
                    if ($optkey == $value)
                    {
                        $opt_tag['selected'] = '';
                    }
                    $out .= $this->open_tag($opt_tag) . $this->html_safe($opt) . $this->close_tag($opt_tag);
                }
            }
        }
        else
        {
            $tmp = $this->parse_comma_separated($this->field_attr[$name]['values']);
            foreach ($tmp as $opt)
            {
                $opt = trim($opt, '\'');
                $opt_tag = array('tag' => 'option', 'value' => $opt);
                if ($opt == $value)
                {
                    $opt_tag['selected'] = '';
                }
                $out .= $this->open_tag($opt_tag) . $this->html_safe($opt) . $this->close_tag($opt_tag);
            }
        }
        $out .= $this->close_tag($tag);
        return $out;
    }
    protected function create_radio($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_radio($name, $value, $tag);
        }
        $out = '';
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'radio',
            'data-type' => 'radio',
            'name' => $name);

        $label_tag = array('tag' => 'label', 'class' => 'xcrud-radio-label');

        if (is_array($this->field_attr[$name]['values']))
        {
            foreach ($this->field_attr[$name]['values'] as $optkey => $opt)
            {
                $out .= $this->open_tag('div', $this->theme_config('radio_container')) . $this->open_tag($label_tag);
                $attr = array('value' => $optkey);
                if ($optkey == $value)
                {
                    $attr['checked'] = '';
                }
                $out .= $this->single_tag($tag, $this->theme_config('radio_field'), array_merge($this->field_attr[$name], $attr), true);
                $out .= $this->html_safe($opt) . $this->close_tag($label_tag) . $this->close_tag('div');
            }
        }
        else
        {
            $tmp = $this->parse_comma_separated($this->field_attr[$name]['values']);
            foreach ($tmp as $opt)
            {
                $opt = trim(trim($opt, '\''));
                $out .= $this->open_tag('div', $this->theme_config('radio_container')) . $this->open_tag($label_tag);
                $attr = array('value' => $opt);
                if ($opt == $value)
                {
                    $attr['checked'] = '';
                }
                $out .= $this->single_tag($tag, $this->theme_config('radio_field'), array_merge($this->field_attr[$name], $attr), true);
                $out .= $this->html_safe($opt) . $this->close_tag($label_tag) . $this->close_tag('div');
            }
        }
        $out .= $this->close_tag($tag);
        return $out;
    }
    protected function create_view_select($name, $value = '', $tag = array())
    {
        if (is_array($this->field_attr[$name]['values']))
        {
            if (is_array(reset($this->field_attr[$name]['values'])))
            {
                foreach ($this->field_attr[$name]['values'] as $tmp)
                {
                    if (isset($tmp[$value]))
                    {
                        return $tmp[$value];
                    }
                }
            }
            else
            {
                if (isset($this->field_attr[$name]['values'][$value]))
                {
                    return $this->field_attr[$name]['values'][$value];
                }
            }
        }
        else
        {
            return $value;
        }
    }
    protected function create_view_radio($name, $value = '', $tag = array())
    {
        return $this->create_view_select($name, $value, $tag);
    }
    protected function create_multiselect($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_multiselect($name, $value, $tag);
        }
        $out = '';
        $values = $this->parse_comma_separated($value);
        $tag = $tag + array(
            'tag' => 'select',
            'data-type' => 'select',
            'multiple' => '',
            'name' => $name);

        if (is_array($this->field_attr[$name]['values']))
        {
            if (is_array(reset($this->field_attr[$name]['values'])))
            {
                $size = 0;
                foreach ($this->field_attr[$name]['values'] as $tmp)
                {
                    $size += (count($tmp) + 1);
                }
            }
            else
            {
                $size = count($this->field_attr[$name]['values']);
            }
        }
        else
        {
            $tmp = $this->parse_comma_separated($this->field_attr[$name]['values']);
            $size = count($tmp);
        }
        $tag['size'] = $size > 10 ? 10 : $size;

        $out .= $this->open_tag($tag, $this->theme_config('multiselect_field'), $this->field_attr[$name], true);

        if (is_array($this->field_attr[$name]['values']))
        {
            foreach ($this->field_attr[$name]['values'] as $optkey => $opt)
            {
                if (is_array($opt))
                {
                    $out .= $this->open_tag(array('tag' => 'optgroup', 'label' => $optkey));
                    foreach ($opt as $k_key => $k_opt)
                    {
                        $opt_tag = array('tag' => 'option', 'value' => $k_key);
                        if (in_array($k_key, $values))
                        {
                            $opt_tag['selected'] = '';
                        }
                        $out .= $this->open_tag($opt_tag) . $this->html_safe($k_opt) . $this->close_tag($opt_tag);
                    }
                    $out .= $this->close_tag('optgroup');
                }
                else
                {
                    $opt_tag = array('tag' => 'option', 'value' => $optkey);
                    if (in_array($optkey, $values))
                    {
                        $opt_tag['selected'] = '';
                    }
                    $out .= $this->open_tag($opt_tag) . $this->html_safe($opt) . $this->close_tag($opt_tag);
                }
            }
        }
        else
        {
            $tmp = $this->parse_comma_separated($this->field_attr[$name]['values']);
            foreach ($tmp as $opt)
            {
                $opt = trim(trim($opt, '\''));
                $opt_tag = array('tag' => 'option', 'value' => $opt);
                if (in_array($opt, $values))
                {
                    $opt_tag['selected'] = '';
                }
                $out .= $this->open_tag($opt_tag) . $this->html_safe($opt) . $this->close_tag($opt_tag);
            }
        }
        $out .= $this->close_tag($tag);
        return $out;
    }
    protected function create_checkboxes($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_checkboxes($name, $value, $tag);
        }
        $out = '';
        $values = $this->parse_comma_separated($value);
        $tag = $tag + array(
            'tag' => 'input',
            'data-type' => 'checkboxes',
            'type' => 'checkbox',
            'name' => $name);

        $label_tag = array('tag' => 'label', 'class' => 'xcrud-checkboxes-label');

        if (is_array($this->field_attr[$name]['values']))
        {
            foreach ($this->field_attr[$name]['values'] as $optkey => $opt)
            {
                $out .= $this->open_tag('div', $this->theme_config('checkbox_container')) . $this->open_tag($label_tag);
                $attr = array('value' => $optkey);
                if (in_array($optkey, $values))
                {
                    $attr['checked'] = '';
                }
                $out .= $this->single_tag($tag, $this->theme_config('checkboxes_field'), array_merge($this->field_attr[$name], $attr), true);
                $out .= $this->html_safe($opt) . $this->close_tag($label_tag) . $this->close_tag('div');
            }
        }
        else
        {
            $tmp = $this->parse_comma_separated($this->field_attr[$name]['values']);
            foreach ($tmp as $opt)
            {
                $opt = trim(trim($opt, '\''));
                $out .= $this->open_tag('div', $this->theme_config('checkbox_container')) . $this->open_tag($label_tag);
                $attr = array('value' => $opt);
                if (in_array($opt, $values))
                {
                    $attr['checked'] = '';
                }
                $out .= $this->single_tag($tag, $this->theme_config('checkboxes_field'), array_merge($this->field_attr[$name], $attr), true);
                $out .= $this->html_safe($opt) . $this->close_tag($label_tag) . $this->close_tag('div');
            }
        }
        $out .= $this->close_tag($tag);
        return $out;
    }
    protected function create_view_multiselect($name, $value = '', $tag = array())
    {
        $out = array();
        $values = $this->parse_comma_separated($value);
        foreach ($values as $val)
        {
            if (is_array($this->field_attr[$name]['values']))
            {
                if (is_array(reset($this->field_attr[$name]['values'])))
                {
                    foreach ($this->field_attr[$name]['values'] as $tmp)
                    {
                        if (isset($tmp[$val]))
                        {
                            $out[] = $tmp[$val];
                        }
                    }
                }
                else
                {
                    if (isset($this->field_attr[$name]['values'][$val]))
                    {
                        $out[] = $this->field_attr[$name]['values'][$val];
                    }
                }
            }
            else
            {
                $out[] = $val;
            }
        }
        return implode(', ', $out);
    }
    protected function create_view_checkboxes($name, $value = '', $tag = array())
    {
        return $this->create_view_multiselect($name, $value, $tag);
    }
    protected function create_hidden($name, $value = '', $tag = array())
    {
        return $this->single_tag($tag + array(
            'tag' => 'input',
            'type' => 'hidden',
            'value' => $value,
            'name' => $name), 'xcrud-input', $this->field_attr[$name], true);
    }
    protected function create_password($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_password($name, $value, $tag);
        }
        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'password',
            'data-type' => 'password',
            'value' => '',
            'placeholder' => $value ? '*****' : '',
            'name' => $name);

        return $this->single_tag($tag, $this->theme_config('password_field'), $this->field_attr[$name], true);
    }
    protected function create_view_password($name, $value = '', $tag = array())
    {
        return '*****';
    }
    protected function create_relation($name, $value = '', $tag = array(), $dependval = false)
    {
        if (!isset($this->relation[$name]))
        {
            return 'Restricted.';
        }
        $out = '';
        $tag = $tag + array(
            'tag' => 'select',
            'data-type' => 'select',
            'name' => $name);

        if ($this->relation[$name]['multi'])
        {
            $tag['multiple'] = '';
            $tag['size'] = 10;
            $values = $this->parse_comma_separated($value);
            $tag['class'] .= ' ' . $this->theme_config('multiselect_field');
        }
        else
        {
            $values = array($value);
            $tag['class'] .= ' ' . $this->theme_config('select_field');
        }
        $db = Xcrud_db::get_instance($this->connection);
        $where_arr = array();
        if ($this->relation[$name]['rel_where'])
        {
            if (is_array($this->relation[$name]['rel_where']))
            {
                foreach ($this->relation[$name]['rel_where'] as $field => $val)
                {
                    $val = $this->replace_text_variables($val, $this->result_row);
                    $fdata = $this->_parse_field_names($field, 'create_relation', $this->relation[$name]['rel_tbl']);
                    $fitem = reset($fdata);
                    $where_arr[] = $this->_where_field($fitem) . $this->_cond_from_where($field) . $db->escape($val);
                }
            }
            else
            {
                $where_arr[] = $this->replace_text_variables($this->relation[$name]['rel_where'], $this->result_row);
            }
        }
        if ($dependval !== false)
        {
            $where_arr[] = $this->_field_from_where($this->relation[$name]['depend_field']) . $this->_cond_from_where($this->
                relation[$name]['depend_field']) . $db->escape($dependval);
        }
        $out .= $this->open_tag($tag, $this->theme_config('relation_field'), $this->field_attr[$name], true);

        if ($this->relation[$name]['depend_on'] && $dependval === false)
        {
            $options = false;
            if ($this->lists_null_opt)
            {
                foreach ($values as $val)
                {
                    $out .= $this->open_tag(array(
                        'tag' => 'option',
                        'value' => $val,
                        'selected' => '')) . $this->lang('null_option') . $this->close_tag('option');
                }

            }
        }
        else
        {
            if ($where_arr)
                $where = 'WHERE ' . implode(' AND ', $where_arr);
            else
                $where = '';
            if (is_array($this->relation[$name]['rel_name']))
            {
                $name_select = 'CONCAT_WS(' . $db->escape($this->relation[$name]['rel_separator']) . ',`' . implode('`,`', $this->
                    relation[$name]['rel_name']) . '`) AS `name`';
            }
            else
            {
                $name_select = '`' . $this->relation[$name]['rel_name'] . '` AS `name`';
            }
            $db->query('SELECT `' . $this->relation[$name]['rel_field'] . '` AS `field`,' . $name_select . $this->
                get_relation_tree_fields($this->relation[$name]) . ' FROM `' . $this->relation[$name]['rel_tbl'] . '` ' . $where .
                ' GROUP BY `field` ORDER BY ' . $this->get_relation_ordering($this->relation[$name]));
            $options = $this->resort_relation_opts($db->result(), $this->relation[$name]);
            if ($this->lists_null_opt)
            {
                $out .= $this->open_tag(array('tag' => 'option', 'value' => '')) . $this->lang('null_option') . $this->close_tag('option');
            }
        }
        if ($options)
        {
            foreach ($options as $opt)
            {
                $opt_tag = array('tag' => 'option', 'value' => $opt['field']);
                if (in_array($opt['field'], $values))
                {
                    $opt_tag['selected'] = "";
                }
                $out .= $this->open_tag($opt_tag) . $this->html_safe($opt['name']) . $this->close_tag($opt_tag);
            }
        }
        $out .= $this->close_tag($tag);
        unset($options);
        return $out;
    }
    protected function create_view_relation($name, $value = '', $tag = array(), $dependval = false)
    {
        if ($value === null || $value === '')
        {
            return '';
        }
        $db = Xcrud_db::get_instance($this->connection);
        if (is_array($this->relation[$name]['rel_name']))
        {
            $field = 'CONCAT_WS(' . $db->escape($this->relation[$name]['rel_separator']) . ',`' . implode('`,`', $this->relation[$name]['rel_name']) .
                '`) as `name`';
        }
        else
        {
            $field = '`' . $this->relation[$name]['rel_name'] . '` as `name`';
        }
        if ($this->relation[$name]['multi'])
        {
            $values = $this->parse_comma_separated($value);
            foreach ($values as $key => $val)
            {
                $values[$key] = $db->escape($val);
            }
            $where = 'IN(' . implode(',', $values) . ')';
        }
        else
        {
            $where = ' = ' . $db->escape($value);
        }
        $db->query('SELECT ' . $field . ' FROM `' . $this->relation[$name]['rel_tbl'] . '` WHERE `' . $this->relation[$name]['rel_field'] .
            '` ' . $where . ' GROUP BY `' . $this->relation[$name]['rel_field'] . '`');
        $options = $db->result();
        $out = array();
        foreach ($options as $opt)
        {
            $out[] = $opt['name'];
        }
        return implode(', ', $out);
    }
    protected function get_relation_ordering($rel)
    {
        if ($rel['tree'] && isset($rel['tree']['left_key']) && isset($rel['tree']['level_key']))
        {
            return '`' . $rel['tree']['left_key'] . '` ASC';
        }
        elseif ($rel['tree'] && isset($rel['tree']['parent_key']) && isset($rel['tree']['primary_key']))
        {
            return ($rel['order_by'] ? $rel['order_by'] : '`name` ASC');
        }
        elseif ($rel['order_by'])
        {
            return $rel['order_by'];
        }
        else
            return '`name` ASC';
    }
    protected function get_relation_tree_fields($rel)
    {
        if ($rel['tree'] && isset($rel['tree']['left_key']) && isset($rel['tree']['level_key']))
        {
            return ',`' . $rel['tree']['left_key'] . '`,`' . $rel['tree']['level_key'] . '`';
        }
        elseif ($rel['tree'] && isset($rel['tree']['parent_key']) && isset($rel['tree']['primary_key']))
        {
            return ',`' . $rel['tree']['parent_key'] . '` AS `pk`, `' . $rel['tree']['primary_key'] . '` AS `pri`';
        }
        else
            return '';
    }
    protected function resort_relation_opts($options, $rel)
    {
        if ($rel['tree'] && isset($rel['tree']['left_key']) && isset($rel['tree']['level_key']))
        {
            foreach ($options as $key => $opt)
            {
                $level = (int)$opt[$rel['tree']['level_key']];
                $out = '';
                for ($i = 0; $i < $level; ++$i)
                {
                    $out .= '. ';
                }
                if ($out)
                    $out .= ' └ ';
                $out .= $opt['name'];
                $options[$key]['name'] = $out;
            }
        }
        elseif ($rel['tree'] && isset($rel['tree']['parent_key']) && isset($rel['tree']['primary_key']))
        {
            $opts_multiarr = array();
            foreach ($options as $key => $opt)
            {
                $opt['children'] = array();
                $opts_multiarr[] = $opt;
            }
            foreach ($opts_multiarr as $key => $opt)
            {
                $this->recursive_push($opts_multiarr, $opts_multiarr[$key]);
            }
            $new_opts = array();
            $this->recursive_opts($new_opts, $opts_multiarr, 0);
            $options = $new_opts;
        }
        return $options;
    }
    protected function recursive_push(&$options, &$insert)
    {
        foreach ($options as $key => $opt)
        {
            if (!$opt)
            {
                continue;
            }
            if ($opt['pri'] == $insert['pk'])
            {
                $options[$key]['children'][] = $insert;
                $insert = null;
            }
            elseif ($options[$key]['children'])
            {
                $this->recursive_push($options[$key]['children'], $insert);
            }
        }
    }
    protected function recursive_opts(&$options, $array, $level)
    {
        $level = $level + 1;
        foreach ($array as $opt)
        {
            if (!$opt)
            {
                continue;
            }
            $out = '';
            for ($i = 1; $i < $level; ++$i)
            {
                $out .= '. ';
            }
            if ($out)
                $out .= ' └ ';
            $opt['name'] = $out . $opt['name'];
            $options[] = $opt;
            if (count($opt['children']))
            {
                $this->recursive_opts($options, $opt['children'], $level);
            }
        }
    }

    protected function create_fk_relation($name, $value = '', $tag = array())
    {
        if (!isset($this->fk_relation[$name]))
        {
            return 'Restricted.';
        }
        $out = '';
        $tag = $tag + array(
            'tag' => 'select',
            'data-type' => 'select',
            'name' => $name,
            'multiple' => '',
            'size' => 10);
        $tag['class'] .= ' ' . $this->theme_config('multiselect_field');
        $values = $this->parse_comma_separated($value);

        $db = Xcrud_db::get_instance($this->connection);
        $where_arr = array();
        if ($this->fk_relation[$name]['rel_where'])
        {
            if (is_array($this->fk_relation[$name]['rel_where']))
            {
                foreach ($this->fk_relation[$name]['rel_where'] as $field => $val)
                {
                    $val = $this->replace_text_variables($val, $this->result_row);
                    $fitem = reset($this->_parse_field_names($field, 'create_fk_relation', $this->fk_relation[$name]['rel_tbl']));
                    $where_arr[] = $this->_where_field($fitem) . $this->_cond_from_where($field) . $db->escape($val);
                }
            }
            else
            {
                $where_arr[] = $this->replace_text_variables($this->fk_relation[$name]['rel_where'], $this->result_row);
            }
        }
        $out .= $this->open_tag($tag, '', $this->field_attr[$name], true);

        if ($where_arr)
            $where = 'WHERE ' . implode(' AND ', $where_arr);
        else
            $where = '';

        if (is_array($this->fk_relation[$name]['rel_name']))
        {
            $optnames = array();
            foreach ($this->fk_relation[$name]['rel_name'] as $optnms)
            {
                $optnames[] = '`' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $optnms . '`';
            }
            $name_select = 'CONCAT_WS(' . $db->escape($this->fk_relation[$name]['rel_separator']) . ',' . implode(',', $optnames) .
                ') AS `name`';
        }
        else
        {
            $name_select = '`' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $this->fk_relation[$name]['rel_name'] .
                '` AS `name`';
        }

        if ($this->fk_relation[$name]['rel_orderby'])
        {
            $order_by = $this->fk_relation[$name]['rel_orderby'];
        }
        else
        {
            $order_by = '`name` ASC';
        }

        $db->query('SELECT `' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $this->fk_relation[$name]['rel_field'] .
            '` AS `field`,' . $name_select . ' FROM `' . $this->fk_relation[$name]['rel_tbl'] . '` ' . $where .
            ' GROUP BY `field` ORDER BY ' . $order_by);
        $options = $db->result();

        if ($this->lists_null_opt)
        {
            $out .= $this->open_tag(array('tag' => 'option', 'value' => '')) . $this->lang('null_option') . $this->close_tag('option');
        }
        if ($options)
        {
            foreach ($options as $opt)
            {
                $opt_tag = array('tag' => 'option', 'value' => $opt['field']);
                if (in_array($opt['field'], $values))
                {
                    $opt_tag['selected'] = "";
                }
                $out .= $this->open_tag($opt_tag) . $this->html_safe($opt['name']) . $this->close_tag($opt_tag);
            }
        }
        $out .= $this->close_tag($tag);
        unset($options);
        return $out;
    }

    protected function create_view_fk_relation($name, $value = '', $tag = array())
    {
        if (!isset($this->fk_relation[$name]))
        {
            return 'Restricted.';
        }

        if (!$value)
        {
            return '';
        }

        $db = Xcrud_db::get_instance($this->connection);
        if (is_array($this->fk_relation[$name]['rel_name']))
        {
            $optnames = array();
            foreach ($this->fk_relation[$name]['rel_name'] as $optnms)
            {
                $optnames[] = '`' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $optnms . '`';
            }
            $name_select = 'CONCAT_WS(' . $db->escape($this->fk_relation[$name]['rel_separator']) . ',' . implode(',', $optnames) .
                ') AS `name`';
        }
        else
        {
            $name_select = '`' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $this->fk_relation[$name]['rel_name'] .
                '` AS `name`';
        }

        $values = $this->parse_comma_separated($value);
        foreach ($values as $key => $val)
        {
            $values[$key] = $db->escape($val);
        }
        $where = 'IN(' . implode(',', $values) . ')';

        if ($this->fk_relation[$name]['rel_orderby'])
        {
            $order_by = $this->fk_relation[$name]['rel_orderby'];
        }
        else
        {
            $order_by = '`name` ASC';
        }

        $db->query('SELECT `' . $this->fk_relation[$name]['rel_tbl'] . '`.`' . $this->fk_relation[$name]['rel_field'] .
            '` AS `field`,' . $name_select . ' FROM `' . $this->fk_relation[$name]['rel_tbl'] . '` WHERE `' . $this->fk_relation[$name]['rel_tbl'] .
            '`.`' . $this->fk_relation[$name]['rel_field'] . '` ' . $where . ' GROUP BY `field` ORDER BY ' . $order_by);

        $options = $db->result();
        $out = array();
        foreach ($options as $opt)
        {
            $out[] = $opt['name'];
        }
        return implode(', ', $out);
    }

    protected function create_file($name, $value = '', $tag = array(), $is_upload = false)
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_file($name, $value, $tag);
        }
        $out = ''; // upload container
        $out .= $this->open_tag('div', 'xcrud-upload-container'); // file and delete button
        $out .= $this->open_tag('div', $this->theme_config('grid_button_group'));
        if ($value)
        {
            //$out .= $this->open_tag('span', 'xcrud-file-name');
            $binary = isset($this->upload_config[$name]['blob']) ? true : false;
            if ($binary && !$is_upload)
            {
                $file_size = $this->_file_size_bin($value);
                $value = 'blob-storage';
                $ext = 'binary';
            }
            else
            {
                $path = $this->get_image_folder($name);
                $file_size = $this->_file_size($path . '/' . $value);
                $ext = trim(strtolower(strrchr($value, '.')), '.');
            }

            $attr = array(
                'href' => (isset($this->upload_config[$name]['url']) ? $this->real_file_link($value, $this->upload_config[$name], true) :
                    $this->file_link($name, $this->primary_val)),
                'class' => 'xcrud-file-name xcrud-' . $ext,
                'target' => '_blank');

            $out .= $this->open_tag('a', $this->theme_config('file_name'), $attr);
            $out .= $this->open_tag('strong');
            $out .= (isset($this->upload_config[$name]['text']) ? $this->upload_config[$name]['text'] : $value);
            $out .= $this->close_tag('strong');
            $out .= ' ' . $file_size . $this->close_tag('a');

            //$out .= $this->close_tag('span');
            if (!isset($tag['readonly']) && !isset($tag['disabled']))
            {
                $out .= $this->remove_upload_button($name);
            }
        }
        else
        {
            $out .= $this->open_tag('span', 'xcrud-nofile ' . $this->theme_config('no_file'));
            $out .= $this->lang('no_file') . $this->close_tag('span');
        }

        if (!isset($tag['readonly']) && !isset($tag['disabled']))
        {
            // hidden field
            $attr = array(
                'name' => $name,
                'value' => $value,
                'type' => 'hidden');
            $out .= $this->single_tag('input', 'xcrud-input', $attr, true);
            // upload button
            $out .= $this->upload_file_button($name, $value, $tag);
        }

        // close upload container
        $out .= $this->close_tag('div');
        $out .= $this->close_tag('div');

        return $out;
    }
    protected function upload_file_button($name, $value, $tag = array())
    {
        $out = '';
        $out .= $this->open_tag('span', $this->theme_config('upload_button'), array('class' => 'xcrud-add-file'));
        if (!$this->is_rtl && $this->theme_config('upload_button_icon'))
        {
            $out .= $this->open_tag('i', $this->theme_config('upload_button_icon')) . $this->close_tag('i') . ' ';
        }
        $out .= $this->lang('add_file');
        if ($this->is_rtl && $this->theme_config('upload_button_icon'))
        {
            $out .= ' ' . $this->open_tag('i', $this->theme_config('upload_button_icon')) . $this->close_tag('i');
        }
        $attr = array(
            'id' => 'xfupl' . rand(),
            'value' => '',
            'type' => 'file',
            'data-type' => 'file',
            'data-field' => $name,
            'class' => 'xcrud-upload',
            'name' => 'xcrud-attach');
        if (isset($tag['data-required']) && !$value)
        {
            $attr['data-required'] = '';
        }
        $out .= $this->single_tag('input', '', $attr);
        $out .= $this->close_tag('span');
        return $out;
    }
    protected function upload_image_button($name, $value, $tag = array())
    {
        $out = '';
        $out .= $this->open_tag('span', $this->theme_config('upload_button'), array('class' => 'xcrud-add-file'));
        if (!$this->is_rtl && $this->theme_config('upload_button_icon'))
        {
            $out .= $this->open_tag('i', $this->theme_config('upload_button_icon')) . $this->close_tag('i') . ' ';
        }
        $out .= $this->lang('add_image');
        if ($this->is_rtl && $this->theme_config('upload_button_icon'))
        {
            $out .= ' ' . $this->open_tag('i', $this->theme_config('upload_button_icon')) . $this->close_tag('i');
        }
        $attr = array(
            'id' => 'xfupl' . rand(),
            'value' => '',
            'type' => 'file',
            'data-type' => 'image',
            'data-field' => $name,
            'class' => 'xcrud-upload',
            'accept' => 'image/jpeg,image/png,image/gif',
            'name' => 'xcrud-attach',
            'capture' => 'camera');
        if (isset($tag['data-required']) && !$value)
        {
            $attr['data-required'] = '';
        }
        $out .= $this->single_tag('input', '', $attr);
        $out .= $this->close_tag('span');
        return $out;
    }
    protected function remove_upload_button($name)
    {
        $out = '';
        $attr = array(
            'href' => 'javascript:;',
            'class' => 'xcrud-remove-file',
            'data-field' => $name);
        $out .= $this->open_tag('a', $this->theme_config('remove_button'), $attr);
        if (!$this->is_rtl && $this->theme_config('remove_button_icon'))
        {
            $out .= $this->open_tag('i', $this->theme_config('remove_button_icon')) . $this->close_tag('i') . ' ';
        }
        $out .= $this->lang('remove');
        if ($this->is_rtl && $this->theme_config('remove_button_icon'))
        {
            $out .= ' ' . $this->open_tag('i', $this->theme_config('remove_button_icon')) . $this->close_tag('i');
        }
        $out .= $this->close_tag('a');
        return $out;
    }
    protected function create_view_file($name, $value = '', $tag = array(), $is_upload = false)
    {
        $out = '';
        if ($value)
        {
            $binary = isset($this->upload_config[$name]['blob']) ? true : false;
            if ($binary && !$is_upload)
            {
                $file_size = $this->_file_size_bin($value);
                $value = 'blob-storage';
                $ext = 'binary';
            }
            else
            {
                $path = $this->get_image_folder($name);
                $file_size = $this->_file_size($path . '/' . $value);
                $ext = trim(strtolower(strrchr($value, '.')), '.');
            }
            $attr = array(
                'href' => isset($this->upload_config[$name]['url']) ? $this->real_file_link($value, $this->upload_config[$name], true) :
                    $this->file_link($name, $this->primary_val),
                'class' => 'xcrud-file xcrud-' . $ext,
                'target' => '_blank');
            $out .= $this->open_tag('span', 'xcrud-file-name');
            $out .= $this->open_tag('a', '', $attr);
            $out .= (isset($this->upload_config[$name]['text']) ? $this->upload_config[$name]['text'] : $value) . $this->close_tag('a');
            $out .= ' ' . $file_size;
            $this->close_tag('span');
        }
        else
        {
            $out .= $this->open_tag('span', 'xcrud-nofile');
            $out .= $this->lang('no_file') . $this->close_tag('span');
        }
        return $out;
    }
    protected function create_image($name, $value = '', $tag = array(), $is_upload = false)
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_image($name, $value, $tag = array());
        }
        $out = ''; // upload container
        $out .= $this->open_tag('div', 'xcrud-upload-container'); // image and delete button
        if ($value)
        {
            $binary = isset($this->upload_config[$name]['blob']) ? true : false;
            if ($binary && !$is_upload)
            {
                $value = 'blob-storage';
            }
            else
            {

            }
            $attr = array('src' => isset($this->upload_config[$name]['url']) ? $this->real_file_link($value, $this->upload_config[$name], true) :
                    $this->file_link($name, $this->primary_val, (isset($this->upload_config[$name]['detail_thumb']) ? $this->upload_config[$name]['detail_thumb'] : false), false,
                    $value), 'alt' => '');
            $out .= $this->single_tag('img', $this->theme_config('image'), $attr);

            if (!isset($tag['readonly']) && !isset($tag['disabled']))
            {
                $out .= $this->open_tag('div', $this->theme_config('grid_button_group'));
                // delete button
                $out .= $this->remove_upload_button($name);
            }
        }
        else
        {
            $out .= $this->open_tag('div', $this->theme_config('grid_button_group'));
            $out .= $this->open_tag('span', 'xcrud-noimage ' . $this->theme_config('no_file'));
            $out .= $this->lang('no_image') . $this->close_tag('span');
        }

        if (!isset($tag['readonly']) && !isset($tag['disabled']))
        {
            // hidden field
            $attr = array(
                'name' => $name,
                'value' => $value,
                'type' => 'hidden');
            $out .= $this->single_tag('input', 'xcrud-input', $attr, true);
            // upload button
            $out .= $this->upload_image_button($name, $value, $tag);
            // close upload container
            $out .= $this->close_tag('div');
        }
        $out .= $this->close_tag('div');

        return $out;
    }
    protected function create_view_image($name, $value = '', $tag = array())
    {
        $out = ''; // image and delete button
        if ($value)
        {
            /*$binary = isset($this->upload_config[$name]['blob']) ? true : false;
            if ($binary)
            {
            $value = 'blob-storage';
            }
            else
            {

            }*/
            $attr = array('src' => isset($this->upload_config[$name]['url']) ? $this->real_file_link($value, $this->upload_config[$name], true) :
                    $this->file_link($name, $this->primary_val, (isset($this->upload_config[$name]['detail_thumb']) ? $this->upload_config[$name]['detail_thumb'] : false), false,
                    $value), 'alt' => '');
            $out .= $this->single_tag('img', $this->theme_config('image'), $attr);
        }
        else
        {
            $out .= $this->open_tag('span', 'xcrud-noimage');
            $out .= $this->lang('no_image') . $this->close_tag('span');
        }

        return $out;
    }
    protected function create_binary($name, $value = '', $tag = array())
    {
        return $value ? '[binary data]' : '[binary null]';
    }
    protected function create_view_binary($name, $value = '', $tag = array())
    {
        return $value ? '[binary data]' : '[binary null]';
    }
    protected function create_remote_image($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_remote_image($name, $value, $tag);
        }
        $out = '';

        $attr = $this->field_attr[$name];

        $tag = $tag + array(
            'tag' => 'input',
            'type' => 'text',
            'name' => $name,
            'value' => $value);


        if ($value)
        {
            $img = array(
                'tag' => 'img',
                'alt' => '',
                'src' => $attr['link'] . $value);
            $out .= $this->single_tag($img, $this->theme_config('remote_image'));
        }
        unset($attr['link']);
        $out .= $this->single_tag($tag, $this->theme_config('remote_image_field'), $attr, true);
        return $out;
    }
    protected function create_view_remote_image($name, $value = '', $tag = array())
    {
        if ($value)
        {
            $attr = $this->field_attr[$name];
            $img = array(
                'tag' => 'img',
                'alt' => '',
                'src' => $attr['link'] . $value);
            return $this->single_tag($img, $this->theme_config('remote_image'));
        }
    }

    protected function create_point($name, $value = '', $tag = array())
    {
        if (isset($this->subselect[$name]))
        {
            return $this->create_view_point($name, $value, $tag);
        }
        $out = '';
        $attr = $this->field_attr[$name];
        if (!$value)
        {
            $value = Xcrud_config::$default_point ? Xcrud_config::$default_point : '0,0';
        }

        $tag = $tag + array(
            'tag' => 'input',
            'name' => $name,
            'value' => $value,
            'data-type' => 'point');
        if ($attr['search'])
        {
            $search = array(
                'tag' => 'input',
                'type' => 'text',
                'placeholder' => $this->lang($attr['search_text']),
                'name' => $name . '.search',
                'class' => 'xcrud-map-search');
            if (isset($this->disabled[$name]))
            {
                $search['disabled'] = '';
            }
        }
        else
        {
            $search = false;
        }

        if (isset($this->exception_fields[$name]))
        {
            $tag['class'] .= ' validation-error';
        }

        if ($attr['coords'])
        {
            $tag['type'] = 'text';
        }
        else
        {
            $tag['type'] = 'hidden';
        }

        $map = array(
            'tag' => 'div',
            'class' => 'xcrud-map',
            'data-text' => $this->lang($attr['text']),
            'data-zoom' => $attr['zoom'],
            'style' => 'width:' . $attr['width'] . 'px;height:' . $attr['height'] . 'px;');

        if (isset($this->readonly[$name]) or isset($this->disabled[$name]))
        {
            $map['data-draggable'] = '0';
        }
        else
        {
            $map['data-draggable'] = '1';
        }
        unset($attr['text'], $attr['zoom'], $attr['width'], $attr['height'], $attr['search_text']);

        $out .= $this->single_tag($tag, $this->theme_config('point_field'), $attr, true);
        if ($search)
        {
            $out .= $this->single_tag($search, $this->theme_config('point_search'));
        }
        $out .= $this->open_tag($map, $this->theme_config('point_map')) . $this->close_tag($map);

        return $out;
    }
    protected function create_view_point($name, $value = '', $tag = array())
    {
        $out = '';
        $attr = $this->field_attr[$name];
        if (!$value)
        {
            $value = Xcrud_config::$default_point;
        }
        if ($value)
        {
            $tag = array(
                'tag' => 'input',
                'name' => $name,
                'value' => $value,
                'class' => 'xcrud-input',
                'data-type' => 'point',
                'type' => 'hidden');
            $map = array(
                'tag' => 'div',
                'class' => 'xcrud-map',
                'data-text' => $this->lang($attr['text']),
                'data-zoom' => $attr['zoom'],
                'style' => 'width:' . $attr['width'] . 'px;height:' . $attr['height'] . 'px;',
                'data-draggable' => 0,
                );
            unset($attr['text'], $attr['zoom'], $attr['width'], $attr['height'], $attr['search_text']);
            $out .= $this->single_tag($tag, $this->theme_config('point_field'), $attr);
            $out .= $this->open_tag($map, $this->theme_config('point_map'));
        }
        return $out;
    }


    protected function benchmark_start()
    {
        if ($this->benchmark)
        {
            $start = explode(' ', microtime());
            $this->time_start = (float)($start[1] + $start[0]);
            $this->memory_start = memory_get_usage();
        }
    }
    protected function benchmark_end()
    {
        if ($this->benchmark)
        {
            $end = explode(' ', microtime());
            $this->time_end = (float)($end[1] + $end[0]);
            $this->memory_end = memory_get_usage();
            $out = '<span>' . $this->lang('exec_time') . ' ' . (number_format($this->time_end - $this->time_start, 3, '.', '')) .
                ' s</span>';
            $out .= '<span>' . $this->lang('memory_usage') . ' ' . (number_format(($this->memory_end - $this->memory_start) / 1024 /
                1024, 2, '.', '')) . ' MB</span>';
            return $out;
        }
    }
    protected static function error($text = 'Error!')
    {
        exit('<div class="xcrud-error" style="position:relative;line-height:1.25;padding:15px;color:#BA0303;margin:10px;border:1px solid #BA0303;border-radius:4px;font-family:Arial,sans-serif;background:#FFB5B5;box-shadow:inset 0 0 80px #E58989;">
            <span style="position:absolute;font-size:10px;bottom:3px;right:5px;">xCRUD</span>' . $text . '</div>');
    }
    protected function _upload()
    {
        switch ($this->_post('type'))
        {
            case 'image':
                return $this->_upload_image();
                break;
            case 'file':
                return $this->_upload_file();
                break;
            default:
                return self::error('Upload Error');
                break;
        }
    }
    protected function _upload_file()
    {
        $field = $this->_post('field');
        $oldfile = $this->_post('oldfile', 0);
        if (isset($_FILES) && isset($_FILES['xcrud-attach']) && !$_FILES['xcrud-attach']['error'])
        {
            $file = $_FILES['xcrud-attach'];
            $this->check_file_folders($field);
            $filename = $this->safe_file_name($file, $field);
            $filename = $this->get_filename_noconfict($filename, $field);

            if ($this->before_upload)
            {
                $path = $this->check_file($this->before_upload['path'], 'before_upload');
                include_once ($path);
                $callable = $this->before_upload['callable'];
                if (is_callable($callable))
                {
                    call_user_func_array($callable, array(
                        $field,
                        $filename,
                        $this->upload_config[$field],
                        $this));
                    if ($this->exception)
                    {
                        $out = $this->call_exception();
                        $this->after_render();
                        return $out;
                    }
                }
            }

            $this->save_file($file, $filename, $field);
            if ($this->exception)
            {
                $out = $this->call_exception();
                $this->upload_to_remove[$oldfile] = $field;
                $this->after_render();
                return $out;
            }
            if ($oldfile != $filename)
                $this->upload_to_remove[$oldfile] = $field;
            $this->upload_to_save[$filename] = $field;
            $out = $this->create_file($field, $filename, array(), true);
            $this->after_render();
            return $out;
        }
        else
            return self::error('File is not uploaded');
    }
    protected function _upload_image()
    {
        $field = $this->_post('field');
        $oldfile = $this->_post('oldfile', 0);
        if (isset($_FILES) && isset($_FILES['xcrud-attach']) && !$_FILES['xcrud-attach']['error'])
        {
            $file = $_FILES['xcrud-attach'];
            $this->check_file_folders($field);
            $filename = $this->safe_file_name($file, $field);
            $filename = $this->get_filename_noconfict($filename, $field);

            if ($this->before_upload)
            {
                $path = $this->check_file($this->before_upload['path'], 'before_upload');
                include_once ($path);
                $callable = $this->before_upload['callable'];
                if (is_callable($callable))
                {
                    call_user_func_array($callable, array(
                        $field,
                        $filename,
                        $this->upload_config[$field],
                        $this));
                }
                if ($this->exception)
                {
                    $out = $this->call_exception();
                    $this->after_render();
                    return $out;
                }
            }

            if ($oldfile != $filename)
                $this->upload_to_remove[$oldfile] = $field;
            $this->upload_to_save[$filename] = $field;
            if ($this->is_resize($field))
            {
                $this->save_file_to_tmp($file, $filename, $field);
                if ($this->exception)
                {
                    $out = $this->call_exception();
                    $this->after_render();
                    return $out;
                }
                if ($this->is_manual_crop($field))
                {
                    //$this->make_bg($filename, $field);
                    $out = $this->render_crop_window($filename, $field);
                }
                else
                {
                    $this->make_autoresize($filename, $field);
                    $this->remove_tmp_image($filename, $field);
                    if ($this->exception)
                    {
                        $out = $this->call_exception();
                        $this->after_render();
                        return $out;
                    }
                    //$this->render_image_field($filename, $field);
                    $out = $this->create_image($field, $filename, array(), true);
                }
            }
            else
            {
                //$this->save_file($file, $filename, $field); //$this->render_image_field($filename, $field);
                $this->save_file_to_tmp($file, $filename, $field);
                if ($this->exception)
                {
                    $out = $this->call_exception();
                    $this->after_render();
                    return $out;
                }
                $this->filter_image($filename, $field);
                $this->remove_tmp_image($filename, $field);
                if ($this->exception)
                {
                    $out = $this->call_exception();
                    $this->after_render();
                    return $out;
                }
                $out = $this->create_image($field, $filename, array(), true);
            }
            $this->after_render();
            return $out;
        }
        else
            return self::error('File is not uploaded');
    }

    protected function render_crop_window($filename, $field)
    {
        $out = ''; // upload container
        $out .= $this->open_tag('div', 'xcrud-upload-container');
        $tmp_name = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        if (isset($this->labels[$field]))
            $title = $this->html_safe($this->labels[$field]);
        else
        {
            list($tmp, $fieldname) = explode('.', $field);
            $title = $this->html_safe($this->_humanize($fieldname));
        }
        $path = $this->get_image_folder($field) . '/' . $tmp_name;
        list($width, $height) = getimagesize($path);
        $ratio = isset($this->upload_config[$field]['ratio']) ? $this->upload_config[$field]['ratio'] : '';
        $attr = array(
            'src' => $this->file_link($field, $this->primary_val, false, true),
            'title' => $title,
            'data-width' => $width,
            'data-height' => $height,
            'data-ratio' => $ratio,
            'style' => 'display:none;max-width:none;',
            'alt' => '');
        $out .= $this->single_tag('img', 'xcrud-crop', $attr);
        /*$out .= $this->single_tag('input', 'new_key', array(
        'name' => 'new_key',
        'value' => $this->key,
        'type' => 'hidden'));*/
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'field',
            'value' => $field,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'filename',
            'value' => $filename,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'x',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'y',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'x2',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'y2',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'w',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->single_tag('input', 'xrud-crop-data', array(
            'name' => 'h',
            'value' => 0,
            'type' => 'hidden'));
        $out .= $this->close_tag('div');
        return $out;
    }
    protected function filter_image($filename, $field)
    {
        $tmp_name = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        $settings = $this->upload_config[$field];
        $tmp_path = $this->get_image_folder($field) . '/' . $tmp_name;
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        $watermark = (isset($settings['watermark']) && $settings['watermark']) ? $this->check_file($settings['watermark'],
            'try_crop_image') : false;
        $watermark_position = (isset($settings['watermark_position']) && is_array($settings['watermark_position']) && count($settings['watermark_position'] ==
            2)) ? array_values($settings['watermark_position']) : array(50, 50);
        $quality = (isset($settings['quality']) && $settings['quality']) ? $settings['quality'] : 92;
        $this->_draw_watermark($tmp_path, $file_path, $quality, $watermark, $watermark_position);
        if (isset($settings['thumbs']) && is_array($settings['thumbs']))
        {
            foreach ($settings['thumbs'] as $thumb)
            {
                $thumb_file = $this->get_thumb_path($filename, $field, $thumb);
                $this->_try_change_size($tmp_path, $thumb_file, $field, $thumb);
            }
        }
    }
    protected function make_autoresize($filename, $field)
    {
        $tmp_name = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        $settings = $this->upload_config[$field];
        $tmp_path = $this->get_image_folder($field) . '/' . $tmp_name;
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        $this->_try_change_size($tmp_path, $file_path, $field, $settings);
        if (isset($settings['thumbs']) && is_array($settings['thumbs']))
        {
            foreach ($settings['thumbs'] as $thumb)
            {
                $thumb_file = $this->get_thumb_path($filename, $field, $thumb);
                $this->_try_change_size($tmp_path, $thumb_file, $field, $thumb);
            }
        }
    }
    protected function manual_crop()
    {
        $field = $this->_post('field');
        $filename = $this->_post('filename');
        $tmp_name = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        $x = round($this->_post('x'));
        $y = round($this->_post('y'));
        $x2 = round($this->_post('x2'));
        $y2 = round($this->_post('y2'));
        $w = round($this->_post('w'));
        $h = round($this->_post('h'));
        if (!$w or !$h)
        {
            $this->remove_tmp_image($filename, $field);
            $this->after_render();
            return $this->create_image($field, '');
        }
        $settings = $this->upload_config[$field];
        $ratio = (isset($settings['ratio']) && !empty($settings['ratio'])) ? (float)$settings['ratio'] : $w / $h;
        $tmp_path = $this->get_image_folder($field) . '/' . $tmp_name;
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        $this->_try_crop_image($tmp_path, $file_path, $field, $settings, $x, $y, $w, $h, $ratio);
        if (isset($settings['thumbs']) && is_array($settings['thumbs']))
        {
            foreach ($settings['thumbs'] as $thumb)
            {
                $thumb_path = $this->get_thumb_path($filename, $field, $thumb);
                $this->_try_crop_image($tmp_path, $thumb_path, $field, $thumb, $x, $y, $w, $h, $ratio);
            }
        }
        $this->remove_tmp_image($filename, $field);
        $this->after_render();
        return $this->create_image($field, $filename);
    }
    protected function _try_crop_image($tmp_path, $file_path, $field, $settings, $x, $y, $w, $h, $ratio)
    {
        $set_w = (isset($settings['width']) && !empty($settings['width'])) ? (int)$settings['width'] : false;
        $set_h = (isset($settings['height']) && !empty($settings['height'])) ? (int)$settings['height'] : false;
        //$set_ratio = (isset($settings['ratio']) && !empty($settings['ratio'])) ? (float)$settings['ratio'] : false;
        $sizes = $this->_calculate_crop_sizes($w, $h, $set_w, $set_h, $ratio);
        $watermark = (isset($settings['watermark']) && $settings['watermark']) ? $this->check_file($settings['watermark'],
            'try_crop_image') : false;
        $watermark_position = (isset($settings['watermark_position']) && is_array($settings['watermark_position']) && count($settings['watermark_position'] ==
            2)) ? array_values($settings['watermark_position']) : array(50, 50);
        $quality = (isset($settings['quality']) && $settings['quality']) ? $settings['quality'] : 92;
        $this->_custom_image_crop($tmp_path, $file_path, $sizes['w'], $sizes['h'], $quality, $x, $y, $w, $h, $watermark, $watermark_position);
    }
    protected function _calculate_crop_sizes($w, $h, $set_w, $set_h, $set_ratio)
    {
        $sizes = array();
        if ($set_w && $set_h)
        {
            $tmp_ratio = $set_w / $set_h;
            if ($set_ratio > $tmp_ratio)
            {
                $sizes['w'] = $set_w;
                $sizes['h'] = $set_w / $set_ratio;
            }
            else
            {
                $sizes['h'] = $set_h;
                $sizes['w'] = $set_h * $set_ratio;
            }
        }
        elseif (!$set_w && !$set_h)
        {
            $sizes['w'] = $w;
            $sizes['h'] = $h;
        }
        elseif (!$set_h)
        {
            $sizes['w'] = $set_w;
            $sizes['h'] = round($set_w / $set_ratio);
        }
        elseif (!$set_w)
        {
            $sizes['h'] = $set_h;
            $sizes['w'] = round($set_h * $set_ratio);
        }
        return $sizes;
    }
    protected function _try_change_size($tmp_path, $file_path, $field, $settings)
    {
        $crop = (isset($settings['crop']) && $settings['crop'] == true) ? true : false;
        $width = (isset($settings['width']) && $settings['width']) ? $settings['width'] : false;
        $height = (isset($settings['height']) && $settings['height']) ? $settings['height'] : false;
        $watermark = (isset($settings['watermark']) && $settings['watermark']) ? $this->check_file($settings['watermark'],
            'try_change_size') : false;
        $watermark_position = (isset($settings['watermark_position']) && is_array($settings['watermark_position']) && count($settings['watermark_position'] ==
            2)) ? array_values($settings['watermark_position']) : array(50, 50);
        $quality = (isset($settings['quality']) && $settings['quality']) ? $settings['quality'] : 92;
        if ($crop && $width && $height)
        {
            $this->_image_crop($tmp_path, $file_path, $width, $height, $quality, $watermark, $watermark_position);
        }
        elseif ($width or $height)
        {
            $this->_image_resize($tmp_path, $file_path, $width, $height, $quality, $watermark, $watermark_position);
        }
    }
    protected function _remove_upload()
    {
        $type = isset($this->field_type[$this->_post('field')]) ? $this->field_type[$this->_post('field')] : false;
        switch ($type)
        {
            case 'image':
                return $this->_remove_image();
                break;
            case 'file':
                return $this->_remove_file();
                break;
            default:
                return self::error('Remove Error');
                break;
        }
    }
    protected function _remove_file()
    {
        $field = $this->_post('field');
        $file = $this->_post('file');
        $this->upload_to_remove[$file] = $field;
        $this->after_render();
        return $this->create_file($field, '');
    }
    protected function _remove_image()
    {
        $field = $this->_post('field');
        $file = $this->_post('file');
        $this->upload_to_remove[$file] = $field;
        $this->after_render();
        return $this->create_image($field, '');
    }
    protected function _remove_and_save_uploads()
    {
        if (!$this->cancel_file_saving)
        {
            switch ($this->task)
            {
                case 'save':
                    if (!$this->demo_mode)
                    {
                        if ($this->upload_to_remove)
                        {
                            foreach ($this->upload_to_remove as $file => $field)
                            {
                                if ($file)
                                {
                                    $this->remove_file($file, $field);
                                }
                            }
                        }
                    }
                    $this->upload_to_save = array();
                    $this->upload_to_remove = array();
                    break;
                case 'list':
                case 'create':
                case 'edit':
                case 'view':
                case '':
                    if ($this->upload_to_save)
                    {
                        foreach ($this->upload_to_save as $file => $field)
                        {
                            $this->remove_file($file, $field);
                        }
                        $f_bak = array();
                        foreach ($this->upload_to_remove as $file => $field)
                        {
                            if (!isset($f_bak[$field]))
                            {
                                $f_bak[$field] = true;
                                continue;
                            }
                            $this->remove_file($file, $field);
                        }
                    }
                    $this->upload_to_save = array();
                    $this->upload_to_remove = array();
                    break;
            }
        }
        else
        {
            $this->cancel_file_saving = false;
        }
    }
    protected function _image_resize($src_file, $dest_file, $new_size_w = false, $new_size_h = false, $dest_qual = 92, $watermark = false,
        $watermark_position = array(50, 50))
    {
        list($srcWidth, $srcHeight, $type) = getimagesize($src_file);
        switch ($type)
        {
            case 1:
                $srcHandle = imagecreatefromgif($src_file);
                break;
            case 2:
                $srcHandle = imagecreatefromjpeg($src_file);
                break;
            case 3:
                $srcHandle = imagecreatefrompng($src_file);
                break;
            default:
                self::error('NO FILE');
                return false;
        }
        if ($srcWidth >= $srcHeight)
        {
            $ratio = (($new_size_w ? $srcWidth : $srcHeight) / ($new_size_w ? $new_size_w : $new_size_h));
            $ratio = max($ratio, 1.0);
            $destWidth = ($srcWidth / $ratio);
            $destHeight = ($srcHeight / $ratio);
            if ($destHeight > $new_size_h)
            {
                $ratio = ($destHeight / ($new_size_h ? $new_size_h : $new_size_w));
                $ratio = max($ratio, 1.0);
                $destWidth = ($destWidth / $ratio);
                $destHeight = ($destHeight / $ratio);
            }
        }
        elseif ($srcHeight > $srcWidth)
        {
            $ratio = (($new_size_h ? $srcHeight : $srcWidth) / ($new_size_h ? $new_size_h : $new_size_w));
            $ratio = max($ratio, 1.0);
            $destWidth = ($srcWidth / $ratio);
            $destHeight = ($srcHeight / $ratio);
            if ($destWidth > $new_size_w)
            {
                $ratio = ($destWidth / ($new_size_w ? $new_size_w : $new_size_h));
                $ratio = max($ratio, 1.0);
                $destWidth = ($destWidth / $ratio);
                $destHeight = ($destHeight / $ratio);
            }
        }
        $dstHandle = imagecreatetruecolor($destWidth, $destHeight);
        switch ($type)
        {
            case 1:
                $transparent_source_index = imagecolortransparent($srcHandle);
                if ($transparent_source_index !== -1)
                {
                    $transparent_color = imagecolorsforindex($srcHandle, $transparent_source_index);
                    $transparent_destination_index = imagecolorallocate($dstHandle, $transparent_color['red'], $transparent_color['green'],
                        $transparent_color['blue']);
                    imagecolortransparent($dstHandle, $transparent_destination_index);
                    imagefill($dstHandle, 0, 0, $transparent_destination_index);
                }
                break;
            case 3:
                imagealphablending($dstHandle, false);
                imagesavealpha($dstHandle, true);
                break;
        }
        imagecopyresampled($dstHandle, $srcHandle, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
        imagedestroy($srcHandle);
        if ($watermark)
        {
            list($water_w, $water_h, $water_type) = getimagesize($watermark);
            $offsets = $this->calculate_watermark_offsets($destWidth, $destHeight, $water_w, $water_h, $watermark_position);
            switch ($water_type)
            {
                case 1:
                    $waterHandle = imagecreatefromgif($watermark);
                    $transparent_source_index = imagecolortransparent($waterHandle);
                    if ($transparent_source_index !== -1)
                    {
                        $transparent_color = imagecolorsforindex($waterHandle, $transparent_source_index);
                        $transparent_destination_index = imagecolorallocate($waterHandle, $transparent_color['red'], $transparent_color['green'],
                            $transparent_color['blue']);
                        imagecolortransparent($waterHandle, $transparent_destination_index);
                        imagefill($waterHandle, 0, 0, $transparent_destination_index);
                    }
                    break;
                case 2:
                    $waterHandle = imagecreatefromjpeg($watermark);
                    break;
                case 3:
                    $waterHandle = imagecreatefrompng($watermark);
                    imagealphablending($waterHandle, false);
                    imagesavealpha($waterHandle, true);
                    imagealphablending($dstHandle, true);
                    break;
                    break;
                default:
                    self::error('NO WATERMARK FILE');
                    return false;
            }
            imagecopy($dstHandle, $waterHandle, $offsets['x'], $offsets['y'], 0, 0, $water_w, $water_h);
            imagedestroy($waterHandle);
        }
        switch ($type)
        {
            case 1:
                imagegif($dstHandle, $dest_file);
                break;
            case 2:
                imagejpeg($dstHandle, $dest_file, $dest_qual);
                break;
            case 3:
                imagepng($dstHandle, $dest_file);
                break;
            default:
                self::error('File Type Not Supported!');
                return false;
        }
        imagedestroy($dstHandle);
        $newimgarray = array($destWidth, $destHeight);
        return $newimgarray;
    }
    protected function _image_crop($src_file, $dest_file, $new_size_w, $new_size_h, $dest_qual = 92, $watermark = false, $watermark_position =
        array(50, 50))
    {
        list($srcWidth, $srcHeight, $type) = getimagesize($src_file);
        switch ($type)
        {
            case 1:
                $srcHandle = imagecreatefromgif($src_file);
                break;
            case 2:
                $srcHandle = imagecreatefromjpeg($src_file);
                break;
            case 3:
                $srcHandle = imagecreatefrompng($src_file);
                break;
            default:
                self::error('NO FILE');
                return false;
        }
        if (!$srcHandle)
        {
            self::error('Could not execute imagecreatefrom() function! ');
            return false;
        }
        if ($srcHeight < $srcWidth)
        {
            $ratio = (double)($srcHeight / $new_size_h);
            $cpyWidth = round($new_size_w * $ratio);
            if ($cpyWidth > $srcWidth)
            {
                $ratio = (double)($srcWidth / $new_size_w);
                $cpyWidth = $srcWidth;
                $cpyHeight = round($new_size_h * $ratio);
                $xOffset = 0;
                $yOffset = round(($srcHeight - $cpyHeight) / 2);
            }
            else
            {
                $cpyHeight = $srcHeight;
                $xOffset = round(($srcWidth - $cpyWidth) / 2);
                $yOffset = 0;
            }
        }
        else
        {
            $ratio = (double)($srcWidth / $new_size_w);
            $cpyHeight = round($new_size_h * $ratio);
            if ($cpyHeight > $srcHeight)
            {
                $ratio = (double)($srcHeight / $new_size_h);
                $cpyHeight = $srcHeight;
                $cpyWidth = round($new_size_w * $ratio);
                $xOffset = round(($srcWidth - $cpyWidth) / 2);
                $yOffset = 0;
            }
            else
            {
                $cpyWidth = $srcWidth;
                $xOffset = 0;
                $yOffset = round(($srcHeight - $cpyHeight) / 2);
            }
        }
        $dstHandle = ImageCreateTrueColor($new_size_w, $new_size_h);
        switch ($type)
        {
            case 1:
                $transparent_source_index = imagecolortransparent($srcHandle);
                if ($transparent_source_index !== -1)
                {
                    $transparent_color = imagecolorsforindex($srcHandle, $transparent_source_index);
                    $transparent_destination_index = imagecolorallocate($dstHandle, $transparent_color['red'], $transparent_color['green'],
                        $transparent_color['blue']);
                    imagecolortransparent($dstHandle, $transparent_destination_index);
                    imagefill($dstHandle, 0, 0, $transparent_destination_index);
                }
                break;
            case 3:
                imagealphablending($dstHandle, false);
                imagesavealpha($dstHandle, true);
                break;
        }
        if (!imagecopyresampled($dstHandle, $srcHandle, 0, 0, $xOffset, $yOffset, $new_size_w, $new_size_h, $cpyWidth, $cpyHeight))
        {
            self::error('Could not execute imagecopyresampled() function!');
            return false;
        }
        imagedestroy($srcHandle);
        if ($watermark)
        {
            list($water_w, $water_h, $water_type) = getimagesize($watermark);
            $offsets = $this->calculate_watermark_offsets($new_size_w, $new_size_h, $water_w, $water_h, $watermark_position);
            switch ($water_type)
            {
                case 1:
                    $waterHandle = imagecreatefromgif($watermark);
                    $transparent_source_index = imagecolortransparent($waterHandle);
                    if ($transparent_source_index !== -1)
                    {
                        $transparent_color = imagecolorsforindex($waterHandle, $transparent_source_index);
                        $transparent_destination_index = imagecolorallocate($waterHandle, $transparent_color['red'], $transparent_color['green'],
                            $transparent_color['blue']);
                        imagecolortransparent($waterHandle, $transparent_destination_index);
                        imagefill($waterHandle, 0, 0, $transparent_destination_index);
                    }
                    break;
                case 2:
                    $waterHandle = imagecreatefromjpeg($watermark);
                    break;
                case 3:
                    $waterHandle = imagecreatefrompng($watermark);
                    imagealphablending($waterHandle, false);
                    imagesavealpha($waterHandle, true);
                    imagealphablending($dstHandle, true);
                    break;
                    break;
                default:
                    self::error('NO WATERMARK FILE');
                    return false;
            }
            imagecopy($dstHandle, $waterHandle, $offsets['x'], $offsets['y'], 0, 0, $water_w, $water_h);
            imagedestroy($waterHandle);
        }
        switch ($type)
        {
            case 1:
                imagegif($dstHandle, $dest_file);
                break;
            case 2:
                imagejpeg($dstHandle, $dest_file, $dest_qual);
                break;
            case 3:
                imagepng($dstHandle, $dest_file);
                break;
            default:
                self::error('File Type Not Supported!');
                return false;
        }
        imagedestroy($dstHandle);
        return true;
    }
    protected function _custom_image_crop($src_file, $dest_file, $new_size_w, $new_size_h, $dest_qual, $x, $y, $w, $h, $watermark = false,
        $watermark_position = array(50, 50))
    {
        list($srcWidth, $srcHeight, $type) = getimagesize($src_file);
        switch ($type)
        {
            case 1:
                $srcHandle = imagecreatefromgif($src_file);
                break;
            case 2:
                $srcHandle = imagecreatefromjpeg($src_file);
                break;
            case 3:
                $srcHandle = imagecreatefrompng($src_file);
                break;
            default:
                self::error('NO FILE');
                return false;
        }
        if (!$srcHandle)
        {
            self::error('Could not execute imagecreatefrom() function!');
            return false;
        }
        $dstHandle = ImageCreateTrueColor($new_size_w, $new_size_h);
        switch ($type)
        {
            case 1:
                $transparent_source_index = imagecolortransparent($srcHandle);
                if ($transparent_source_index !== -1)
                {
                    $transparent_color = imagecolorsforindex($srcHandle, $transparent_source_index);
                    $transparent_destination_index = imagecolorallocate($dstHandle, $transparent_color['red'], $transparent_color['green'],
                        $transparent_color['blue']);
                    imagecolortransparent($dstHandle, $transparent_destination_index);
                    imagefill($dstHandle, 0, 0, $transparent_destination_index);
                }
                break;
            case 3:
                imagealphablending($dstHandle, false);
                imagesavealpha($dstHandle, true);
                break;
        }
        if (!imagecopyresampled($dstHandle, $srcHandle, 0, 0, $x, $y, $new_size_w, $new_size_h, $w, $h))
        {
            self::error('Could not execute imagecopyresampled() function!');
            return false;
        }
        imagedestroy($srcHandle);
        if ($watermark)
        {
            list($water_w, $water_h, $water_type) = getimagesize($watermark);
            $offsets = $this->calculate_watermark_offsets($new_size_w, $new_size_h, $water_w, $water_h, $watermark_position);
            switch ($water_type)
            {
                case 1:
                    $waterHandle = imagecreatefromgif($watermark);
                    $transparent_source_index = imagecolortransparent($waterHandle);
                    if ($transparent_source_index !== -1)
                    {
                        $transparent_color = imagecolorsforindex($waterHandle, $transparent_source_index);
                        $transparent_destination_index = imagecolorallocate($waterHandle, $transparent_color['red'], $transparent_color['green'],
                            $transparent_color['blue']);
                        imagecolortransparent($waterHandle, $transparent_destination_index);
                        imagefill($waterHandle, 0, 0, $transparent_destination_index);
                    }
                    break;
                case 2:
                    $waterHandle = imagecreatefromjpeg($watermark);
                    break;
                case 3:
                    $waterHandle = imagecreatefrompng($watermark);
                    imagealphablending($waterHandle, false);
                    imagesavealpha($waterHandle, true);
                    imagealphablending($dstHandle, true);
                    break;
                    break;
                default:
                    self::error('NO WATERMARK FILE');
                    return false;
            }
            imagecopy($dstHandle, $waterHandle, $offsets['x'], $offsets['y'], 0, 0, $water_w, $water_h);
            imagedestroy($waterHandle);
        }
        switch ($type)
        {
            case 1:
                imagegif($dstHandle, $dest_file);
                break;
            case 2:
                imagejpeg($dstHandle, $dest_file, $dest_qual);
                break;
            case 3:
                imagepng($dstHandle, $dest_file);
                break;
            default:
                self::error('File Type Not Supported!');
                return false;
        }
        imagedestroy($dstHandle);
        return true;
    }
    protected function _draw_watermark($src_file, $dest_file, $dest_qual = 95, $watermark = false, $watermark_position =
        array(50, 50))
    {
        list($srcWidth, $srcHeight, $type) = getimagesize($src_file);
        switch ($type)
        {
            case 1:
                $srcHandle = imagecreatefromgif($src_file);
                break;
            case 2:
                $srcHandle = imagecreatefromjpeg($src_file);
                break;
            case 3:
                $srcHandle = imagecreatefrompng($src_file);
                break;
            default:
                self::error('NO FILE');
                return false;
        }
        $dstHandle = imagecreatetruecolor($srcWidth, $srcHeight);
        switch ($type)
        {
            case 1:
                $transparent_source_index = imagecolortransparent($srcHandle);
                if ($transparent_source_index !== -1)
                {
                    $transparent_color = imagecolorsforindex($srcHandle, $transparent_source_index);
                    $transparent_destination_index = imagecolorallocate($dstHandle, $transparent_color['red'], $transparent_color['green'],
                        $transparent_color['blue']);
                    imagecolortransparent($dstHandle, $transparent_destination_index);
                    imagefill($dstHandle, 0, 0, $transparent_destination_index);
                }
                break;
            case 3:
                imagealphablending($dstHandle, false);
                imagesavealpha($dstHandle, true);
                $transparent_color = imagecolorallocatealpha($dstHandle, 0, 0, 0, 127);
                imagefill($dstHandle, 0, 0, $transparent_color);
                break;
        }
        imagecopy($dstHandle, $srcHandle, 0, 0, 0, 0, $srcWidth, $srcHeight);
        imagedestroy($srcHandle);
        if ($watermark)
        {
            list($water_w, $water_h, $water_type) = getimagesize($watermark);
            $offsets = $this->calculate_watermark_offsets($srcWidth, $srcHeight, $water_w, $water_h, $watermark_position);
            switch ($water_type)
            {
                case 1:
                    $waterHandle = imagecreatefromgif($watermark);
                    $transparent_source_index = imagecolortransparent($waterHandle);
                    if ($transparent_source_index !== -1)
                    {
                        $transparent_color = imagecolorsforindex($waterHandle, $transparent_source_index);
                        $transparent_destination_index = imagecolorallocate($waterHandle, $transparent_color['red'], $transparent_color['green'],
                            $transparent_color['blue']);
                        imagecolortransparent($waterHandle, $transparent_destination_index);
                        imagefill($waterHandle, 0, 0, $transparent_destination_index);
                    }
                    break;
                case 2:
                    $waterHandle = imagecreatefromjpeg($watermark);
                    break;
                case 3:
                    $waterHandle = imagecreatefrompng($watermark);
                    imagealphablending($waterHandle, false);
                    imagesavealpha($waterHandle, true);
                    break;
                    break;
                default:
                    self::error('NO WATERMARK FILE');
                    return false;
            }
            imagecopy($dstHandle, $waterHandle, $offsets['x'], $offsets['y'], 0, 0, $water_w, $water_h);
            imagedestroy($waterHandle);
        }
        switch ($type)
        {
            case 1:
                imagegif($dstHandle, $dest_file);
                break;
            case 2:
                imagejpeg($dstHandle, $dest_file, $dest_qual);
                break;
            case 3:
                imagepng($dstHandle, $dest_file);
                break;
            default:
                self::error('File Type Not Supported!');
                return false;
        }
        imagedestroy($dstHandle);
        return true;
    }
    protected function calculate_watermark_offsets($img_w, $img_h, $water_w, $water_h, $water_pos)
    {
        $offsets = array();
        $pos_x = ($water_pos[0] < 0 or $water_pos[0] > 100) ? 0 : $water_pos[0];
        $pos_y = ($water_pos[1] < 0 or $water_pos[1] > 100) ? 0 : $water_pos[1];
        $avail_w = $img_w - $water_w;
        $avail_h = $img_h - $water_h;
        if ($avail_w < 0)
            $avail_w = 0;
        if ($avail_h < 0)
            $avail_h = 0;
        if (!$avail_w)
            $offsets['x'] = 0;
        else
        {
            $offsets['x'] = round($avail_w / 100 * $pos_x);
        }
        if (!$avail_h)
            $offsets['y'] = 0;
        else
        {
            $offsets['y'] = round($avail_h / 100 * $pos_y);
        }
        return $offsets;
    }

    protected function _clean_file_name($txt)
    {
        $replace = array(
            'Š' => 'S',
            'Œ' => 'O',
            'Ž' => 'Z',
            'š' => 's',
            'œ' => 'oe',
            'ž' => 'z',
            'Ÿ' => 'Y',
            '¥' => 'Y',
            'µ' => 'u',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'І' => 'I',
            'Ð' => 'D',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'ß' => 'ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'і' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ý' => 'y',
            'ÿ' => 'y',
            'ă' => 'a',
            'ş' => 's',
            'ţ' => 't',
            'ț' => 't',
            'Ț' => 'T',
            'Ș' => 'S',
            'ș' => 's',
            'Ş' => 'S',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'J',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'I',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'CH',
            'Ш' => 'SH',
            'Щ' => 'SH',
            'Ы' => 'Y',
            'Э' => 'E',
            'Ю' => 'YU',
            'Я' => 'YA',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'j',
            'з' => 'z',
            'и' => 'i',
            'й' => 'i',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'H',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sh',
            'ы' => 'y',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'Ā' => 'A',
            'ā' => 'a',
            'Č' => 'C',
            'č' => 'c',
            'Ē' => 'E',
            'ē' => 'e',
            'Ģ' => 'G',
            'ģ' => 'g',
            'Ī' => 'I',
            'ī' => 'i',
            'Ķ' => 'K',
            'ķ' => 'k',
            'Ļ' => 'L',
            'ļ' => 'l',
            'Ņ' => 'N',
            'ņ' => 'n',
            'Ū' => 'U',
            'ū' => 'u',
            ' ' => '_');
        $txt = str_replace(array_keys($replace), array_values($replace), $txt);
        $txt = preg_replace('/[^a-zA-Z0-9_\-\.]+/', '', $txt);
        return $txt;
    }
    protected function _file_size($path)
    {
        return number_format(is_file($path) ? filesize($path) / 1024 : 0, 2, '.', ' ') . ' KB';
    }
    protected function _file_size_bin($text)
    {
        return number_format(strlen($text) / 1024, 2, '.', ' ') . ' KB';
    }
    protected function _prepare_field($field)
    {
        preg_match_all('/([^<>!=]+)/u', $field, $matches);
        preg_match_all('/([<>!=]+)/u', $field, $matches2);
        return '`' . trim($matches[0][0]) . '`' . ($matches2[0] ? implode('', $matches2[0]) : '=');
    }
    protected function _field_from_where($field)
    {
        return preg_replace('/\s*[<>!=]+\s*$/u', '', $field);
    }
    protected function _cond_from_where($field)
    {
        if (preg_match('/\s*([<>!=]+)\s*$/u', $field, $matches))
        {
            return $matches[1];
        }
        else
        {
            return '=';
        }
    }
    protected function _where_field($param)
    {
        return '`' . $param['table'] . '`.`' . $this->_field_from_where($param['field']) . '`';
    }
    protected function _where_fieldkey($param)
    {
        return $param['table'] . '.' . $this->_field_from_where($param['field']);
    }
    protected function _cond_from_where_in($field)
    {
        if (preg_match('/\s*[!]+\s*$/u', $field))
        {
            return ' NOT IN';
        }
        else
        {
            return ' IN';
        }
    }
    protected function _prepare_field_in($field)
    {
        preg_match_all('/([^!]+)/u', $field, $matches);
        preg_match_all('/([!]+)/u', $field, $matches2);
        return '`' . trim($matches[0][0]) . '`' . ($matches2[0] ? ' NOT IN' : ' IN');
    }

    protected function _compare($val1, $operator, $val2)
    {
        switch ($operator)
        {
            case '=':
                return ($val1 == $val2) ? true : false;
            case '>':
                return ($val1 > $val2) ? true : false;
            case '<':
                return ($val1 < $val2) ? true : false;
            case '>=':
                return ($val1 >= $val2) ? true : false;
            case '<=':
                return ($val1 <= $val2) ? true : false;
            case '!=':
                return ($val1 != $val2) ? true : false;
            case '^=':
                return (mb_strpos($val1, $val2, 0, Xcrud_config::$mbencoding) === 0) ? true : false;
            case '$=':
                return (mb_strpos($val1, $val2, 0, Xcrud_config::$mbencoding) == (mb_strlen($val1, Xcrud_config::$mbencoding) -
                    mb_strlen($val2, Xcrud_config::$mbencoding))) ? true : false;
            case '~=':
                return (mb_strpos($val1, $val2, 0, Xcrud_config::$mbencoding) !== false) ? true : false;
            default:
                return false;
        }
    }
    protected function create_modal($field, $content, $image = false)
    {
        $out = '';
        $attr = array(
            'href' => 'javascript:;',
            'data-header' => $this->columns_names[$field],
            'data-content' => $content);
        if ($image)
        {
            $attr['data-content'] = $this->single_tag('img', '', array('alt' => '', 'src' => $image));
        }
        else
        {
            $attr['data-content'] = $content;
        }
        $out .= $this->open_tag('a', 'xcrud_modal', $attr);
        if (Xcrud_config::$images_in_grid && $image)
        {
            $out .= $content;
        }
        else
        {
            $out .= $this->open_tag('i', $this->modal[$field] ? $this->modal[$field] : $this->theme_config('modal_icon')) . $this->
                close_tag('i');
        }
        $out .= $this->close_tag('a');
        return $out;
    }
    protected function _render_list_item($field, $value, $primary_val, $row)
    {
        $modal = '';
        $out = '';
        $image = '';
        if (isset($this->relation[$field]))
        {
            $value = $row['rel.' . $field];
        }
        if (isset($this->column_callback[$field]))
        {
            $path = $this->check_file($this->column_callback[$field]['path'], 'column_callback');
            include_once ($path);
            if (is_callable($this->column_callback[$field]['callback']) && $row)
            {
                $value = call_user_func_array($this->column_callback[$field]['callback'], array(
                    $value,
                    $field,
                    $primary_val,
                    $row,
                    $this));
                return $value;
            }
        }

        if (isset($this->field_type[$field]))
        {
            switch ($this->field_type[$field])
            {
                case 'select':
                case 'radio':
                    $out .= $this->create_view_select($field, $value);
                    break;
                case 'multiselect':
                case 'checkboxes':
                    $out .= $this->create_view_multiselect($field, $value);
                    break;
                case 'timestamp':
                case 'datetime':
                    if ($value)
                    {
                        $out .= $this->mysql2datetime($value);
                    }
                    break;
                case 'date':
                    if ($value)
                    {
                        $out .= $this->mysql2date($value);
                    }
                    break;
                case 'time':
                    if ($value)
                    {
                        $out .= $this->mysql2time($value);
                    }
                    break;
                case 'price':
                    $out .= $this->cast_number_format($value, $field);
                    break;
                case 'bool':
                    $out .= $value ? $this->lang('bool_on') : $this->lang('bool_off');
                    break;
                case 'file':
                    if ($value)
                    {
                        $out .= $this->open_tag('a', '', array('target' => '_blank', 'href' => isset($this->upload_config[$field]['url']) ? $this->
                                real_file_link($value, $this->upload_config[$field]) : $this->file_link($field, $primary_val)));

                        if (isset($this->upload_config[$field]['text']))
                        {
                            $out .= $this->upload_config[$field]['text'];
                        }
                        elseif (isset($this->upload_config[$field]['filename']))
                        {
                            $out .= $this->upload_config[$field]['filename'];
                        }
                        elseif (isset($this->upload_config[$field]['blob']) && $this->upload_config[$field]['blob'])
                        {
                            $out .= 'blob-storage';
                        }
                        else
                        {
                            $out .= $value;
                        }

                        $out .= $this->close_tag('a');
                        break;
                    }
                case 'image':
                    if ($value)
                    {
                        if (Xcrud_config::$images_in_grid)
                        {
                            $settings = $this->upload_config[$field];
                            if (isset($settings['grid_thumb']) && isset($settings['thumbs'][$settings['grid_thumb']]))
                            {
                                $thumb = $settings['grid_thumb'];
                            }
                            else
                            {
                                $thumb = false;
                            }
                            $out .= $this->single_tag('img', '', array(
                                'alt' => '',
                                'src' => isset($this->upload_config[$field]['url']) ? $this->real_file_link($value, $this->upload_config[$field]) : $this->
                                    file_link($field, $primary_val, $thumb, false, $value),
                                'style' => 'max-height: ' . Xcrud_config::$images_in_grid_height . 'px;'));
                        }
                        else
                        {
                            $out .= $this->open_tag('a', '', array('target' => '_blank', 'href' => isset($this->upload_config[$field]['url']) ? $this->
                                    real_file_link($value, $this->upload_config[$field]) : $this->file_link($field, $primary_val, false, false, $value)));
                            $out .= isset($this->upload_config[$field]['text']) ? $this->upload_config[$field]['text'] : $value;
                            $out .= $this->close_tag('a');
                        }
                    }
                    $image = isset($this->upload_config[$field]['url']) ? $this->real_file_link($value, $this->upload_config[$field]) : $this->
                        file_link($field, $primary_val, false, false, $value);
                    break;
                case 'remote_image':
                    if ($value)
                    {
                        if (Xcrud_config::$images_in_grid)
                        {
                            $out .= $this->single_tag('img', '', array(
                                'alt' => '',
                                'src' => $value,
                                'style' => 'max-height: ' . Xcrud_config::$images_in_grid_height . 'px;'));
                        }
                        else
                        {
                            $out .= $this->open_tag('a', '', array('target' => '_blank', 'href' => $value));
                            $out .= isset($this->upload_config[$field]['text']) ? $this->upload_config[$field]['text'] : $value;
                            $out .= $this->close_tag('a');
                        }
                    }
                    $image = $value;
                    break;
                case 'binary':
                    $out .= $value ? '[binary data]' : '';
                    break;
                case 'text':
                    $value = $this->_cut($value, $field);
                    if (Xcrud_config::$clickable_list_links)
                    {
                        $value = $this->make_links($value);
                        $value = $this->make_mailto($value);
                    }
                    $out .= $value;
                    break;
                case 'textarea':
                case 'texteditor':
                    if (isset($this->modal[$field]))
                    {
                        $out .= $value;
                    }
                    else
                    {
                        $out .= nl2br($this->_cut($value, $field));
                    }
                    break;
                default:
                    $out .= $this->_cut($value, $field);
                    break;
            }

        }
        else
        {
            $out .= $this->_cut($value, $field);
        }
        if (isset($this->column_pattern[$field]))
        {
            $out = str_ireplace('{value}', $out, $this->column_pattern[$field]);
            $out = $this->replace_text_variables($out, $row, false);
        }

        if (isset($this->modal[$field]) && $value)
        {
            return $this->create_modal($field, $out, $image);
        }
        else
        {
            return $out;
        }
    }
    protected function make_mailto($txt)
    {
        if ($this->emails_label)
            return preg_replace('/([A-Za-z0-9_\-\.]+)\@([A-Za-z0-9_\-\.]+)\.([A-Za-z]{2,4})/',
                '<a target="_blank" href="mailto:$1@$2.$3">' . $this->emails_label['text'] . '</a>', $txt);
        else
            return preg_replace('/([A-Za-z0-9_\-\.]+)\@([A-Za-z0-9_\-\.]+)\.([A-Za-z]{2,4})/',
                '<a target="_blank" href="mailto:$1@$2.$3">$1@$2.$3</a>', $txt);
    }
    protected function make_links($txt)
    {
        if ($this->links_label)
            return preg_replace('/(http:\/\/|https:\/\/)([^\s]+)/u', '<a target="_blank" href="$1$2">' . $this->links_label['text'] .
                '</a>', $txt);
        else
            return preg_replace('/(http:\/\/|https:\/\/)([^\s]+)/u', '<a target="_blank" href="$1$2">$1$2</a>', $txt);
    }

    /** renders grid cell content, srips tags and prepares values for export in csv or other */
    protected function _render_export_item($field, $value, $primary_val, $row)
    {
        $out = '';
        if (isset($this->relation[$field]))
        {
            $value = strip_tags($row['rel.' . $field]);
        }
        if (isset($this->column_callback[$field]))
        {
            $path = $this->check_file($this->column_callback[$field]['path'], 'column_callback');
            include_once ($path);
            if (is_callable($this->column_callback[$field]['callback']) && $row)
            {
                $value = strip_tags(call_user_func_array($this->column_callback[$field]['callback'], array(
                    $value,
                    $field,
                    $primary_val,
                    $row,
                    $this)));
                return $value;
            }
        }

        if (isset($this->field_type[$field]))
        {
            switch ($this->field_type[$field])
            {
                case 'select':
                case 'radio':
                    $out .= $this->create_view_select($field, $value);
                    break;
                case 'multiselect':
                case 'checkboxes':
                    $out .= $this->create_view_multiselect($field, $value);
                    break;
                case 'timestamp':
                case 'datetime':
                    if ($value)
                    {
                        $out .= $this->mysql2datetime($value);
                    }
                    break;
                case 'date':
                    if ($value)
                    {
                        $out .= $this->mysql2date($value);
                    }
                    break;
                case 'time':
                    if ($value)
                    {
                        $out .= $this->mysql2time($value);
                    }
                    break;
                case 'price':
                    $out .= $this->cast_number_format($value, $field);
                    break;
                case 'bool':
                    $out .= $value ? $this->lang('bool_on') : $this->lang('bool_off');
                    break;
                case 'file':
                case 'image':
                    if (isset($this->upload_config[$field]['blob']))
                    {
                        $out .= $value ? '[binary data]' : '';
                    }
                    else
                    {
                        $out .= isset($this->upload_config[$field]['text']) ? $this->upload_config[$field]['text'] : $value;
                    }
                    break;
                case 'remote_image':
                    $out .= $value;
                    break;
                case 'binary':
                    $out .= $value ? '[binary data]' : '';
                    break;
                case 'text':
                case 'textarea':
                case 'texteditor':
                default:
                    $out .= $value;
                    break;
            }
        }
        else
        {
            $out .= $value;
        }
        if (isset($this->column_pattern[$field]))
        {
            $out = str_ireplace('{value}', $out, $this->column_pattern[$field]);
            $out = $this->replace_text_variables($out, $row, true);
            $out = strip_tags($out);
        }
        return $out;
    }
    protected function _render_list_buttons(&$row)
    {
        $out = '';
        $group = array('tag' => 'span', 'class' => $this->theme_config('grid_button_group'));
        $out .= $this->open_tag($group);
        if ($this->buttons)
        {
            foreach ($this->buttons as $button)
            {
                if ($this->is_button($button['name'], $row))
                {
                    //$href = '';
                    /*if ($button['params'])
                    {
                    $href = http_build_query($button['params']);
                    }*/
                    $link = $this->replace_text_variables($button['link'], $row, true);
                    /*if ($href)
                    {
                    $link = $link . ((mb_strpos($button['link'], '?') === false) ? '?' : '&amp;') . $href;
                    }*/
                    if ($button['params'])
                    {
                        foreach ($button['params'] as $pkey => $pval)
                        {
                            $button['params'][$pkey] = $this->replace_text_variables($pval, $row, true);
                        }
                    }
                    $tag = array(
                        'tag' => 'a',
                        'class' => $this->theme_config('grid_default'),
                        'href' => $link,
                        'title' => $button['name']);
                    $out .= $this->open_tag($tag, $button['class'], $button['params']);
                    if ($button['icon'])
                    {
                        $out .= $this->open_tag('i', $button['icon']) . $this->close_tag('i');
                    }
                    elseif ($this->theme_config('grid_default_icon'))
                    {
                        $out .= $this->open_tag('i', $this->theme_config('grid_default_icon')) . $this->close_tag('i');
                    }
                    if (Xcrud_config::$button_labels)
                    {
                        $out .= ' ' . $this->html_safe($button['name']);
                    }
                    $out .= $this->close_tag($tag);
                }
            }
        }
        if (!isset($this->hide_button['duplicate']) && !$this->table_ro && $this->is_duplicate($row))
        {
            $tag = array(
                'tag' => 'a',
                'class' => 'xcrud-action',
                'title' => $this->lang('duplicate'),
                'href' => 'javascript:;',
                'data-primary' => $row['primary_key'],
                'data-task' => 'clone');
            $out .= $this->open_tag($tag, $this->theme_config('grid_duplicate'));
            if ($this->theme_config('grid_duplicate_icon'))
            {
                $out .= $this->open_tag('i', $this->theme_config('grid_duplicate_icon')) . $this->close_tag('i');
            }
            if (Xcrud_config::$button_labels)
            {
                $out .= ' ' . $this->lang('duplicate');
            }
            $out .= $this->close_tag($tag);
        }
        if (!isset($this->hide_button['view']) && $this->is_view($row))
        {
            $tag = array(
                'tag' => 'a',
                'class' => 'xcrud-action',
                'title' => $this->lang('view'),
                'href' => 'javascript:;',
                'data-primary' => $row['primary_key'],
                'data-task' => 'view');
            $out .= $this->open_tag($tag, $this->theme_config('grid_view'));
            if ($this->theme_config('grid_view_icon'))
            {
                $out .= $this->open_tag('i', $this->theme_config('grid_view_icon')) . $this->close_tag('i');
            }
            if (Xcrud_config::$button_labels)
            {
                $out .= ' ' . $this->lang('view');
            }
            $out .= $this->close_tag($tag);
        }
        if (!isset($this->hide_button['edit']) && !$this->table_ro && $this->is_edit($row))
        {
            $tag = array(
                'tag' => 'a',
                'class' => 'xcrud-action',
                'title' => $this->lang('edit'),
                'href' => 'javascript:;',
                'data-primary' => $row['primary_key'],
                'data-task' => 'edit');
            $out .= $this->open_tag($tag, $this->theme_config('grid_edit'));
            if ($this->theme_config('grid_edit_icon'))
            {
                $out .= $this->open_tag('i', $this->theme_config('grid_edit_icon')) . $this->close_tag('i');
            }
            if (Xcrud_config::$button_labels)
            {
                $out .= ' ' . $this->lang('edit');
            }
            $out .= $this->close_tag($tag);
        }
        if (!isset($this->hide_button['remove']) && !$this->table_ro && $this->is_remove($row))
        {
            $tag = array(
                'tag' => 'a',
                'class' => 'xcrud-action',
                'title' => $this->lang('remove'),
                'href' => 'javascript:;',
                'data-primary' => $row['primary_key'],
                'data-task' => 'remove');
            if ($this->remove_confirm)
            {
                $tag['data-confirm'] = $this->lang('deleting_confirm');
            }
            $out .= $this->open_tag($tag, $this->theme_config('grid_remove'));
            if ($this->theme_config('grid_remove_icon'))
            {
                $out .= $this->open_tag('i', $this->theme_config('grid_remove_icon')) . $this->close_tag('i');
            }
            if (Xcrud_config::$button_labels)
            {
                $out .= ' ' . $this->lang('remove');
            }
            $out .= $this->close_tag($tag);
        }

        $out .= $this->close_tag($group);
        return $out;
    }
    protected function render_sum_item($field)
    {
        if (isset($this->sum_row[$field]))
        {
            if ($this->sum[$field]['custom'])
            {
                return str_replace('{value}', $this->_render_list_item($field, $this->sum_row[$field], 0, null), $this->sum[$field]['custom']);
            }
            else
            {
                return $this->_render_list_item($field, $this->sum_row[$field], 0, null);
            }
        }
        else
            return '&nbsp;';
    }

    protected function _check_unique_value()
    {
        $db = Xcrud_db::get_instance($this->connection);
        $unique = $this->_post('unique');
        $fdata = $this->_parse_field_names($unique, '_check_unique_value');
        $out = array();
        $table_join = $this->_build_table_join();
        if ($this->primary_val)
        {
            $primary_where = '`' . $this->table . '`.`' . $this->primary_key . '` != ' . $db->escape($this->primary_val) . ' AND';
        }
        else
        {
            $primary_where = '';
        }
        foreach ($fdata as $fkey => $fitem)
        {
            $q = 'SELECT COUNT(*) AS `count` FROM `' . $this->table . '`' . $table_join . ' WHERE ' . $primary_where . ' `' . $fitem['table'] .
                '`.`' . $fitem['field'] . '` = ' . $db->escape($fitem['value']);
            $db->query($q);
            $this->result_row = $db->row();
            if ($this->result_row['count'] > 0)
            {
                $out[] = '[name="' . $this->fieldname_encode($fkey) . '"]';
            }
        }
        if ($out)
        {
            $data = array('error' => array('selector' => implode(',', $out)));
        }
        else
        {
            $data = array('success' => 1);
        }
        //$data['key'] = $this->key;
        return json_encode($data);
    }
    public static function check_url($url, $scr_url = false)
    {
        if (!$url && !$scr_url)
            return false;
        $url = rtrim($url, '/');
        $host = trim($_SERVER['HTTP_HOST'], '/');
        $scheme = (!isset($_SERVER['HTTPS']) or !$_SERVER['HTTPS'] or strtolower($_SERVER['HTTPS']) == 'off' or strtolower($_SERVER['HTTPS']) ==
            'no') ? 'http://' : 'https://';
        // some troubles with sym links between private and public
        $doc_root = trim(str_replace('\\', '/', str_replace(array('/public_html', '/private_html'), '', $_SERVER['DOCUMENT_ROOT'])),
            '/');
        $file_dir = trim(str_replace('\\', '/', str_replace(array('/public_html', '/private_html'), '', dirname(__file__))), '/');

        $curr_host = $scheme . $host;
        $is_full_url = mb_strpos($url, '://') === false ? false : true;
        if ($is_full_url)
        { //www fix
            $curr_www = preg_match('/:\/\/www\./u', $curr_host) ? true : false;
            $url_www = preg_match('/:\/\/www\./u', $url) ? true : false;
            if ($curr_www != $url_www)
            {
                if ($curr_www)
                {
                    $url = preg_replace('/(:\/\/)/u', '$1www.', $url, 1);
                }
                else
                {
                    $url = preg_replace('/(:\/\/)www\./u', '$1', $url, 1);
                }
            }
        }
        elseif (Xcrud_config::$urls2abs)
        {
            if (mb_substr($url, 0, 1) == '/' or mb_substr($url, 0, 2) == './')
            {
                $url = $curr_host . ltrim($url, '.');
            }
            elseif ($scr_url && !$url)
            {
                //$script_uri = ltrim(mb_substr($file_dir, mb_strpos($file_dir, $doc_root) + mb_strlen($doc_root)), '/');

                $file_dir = explode('/', $file_dir);
                $max_root = array();
                $file_dir = array_reverse($file_dir);
                foreach ($file_dir as $segment)
                {

                    if (mb_substr($doc_root, -mb_strlen($segment) - 1, mb_strlen($segment) + 1) != '/' . $segment)
                    {
                        array_unshift($max_root, $segment);
                    }
                    else
                    {
                        break;
                    }
                }
                $script_uri = implode('/', $max_root);

                //$script_uri = trim(str_replace(str_replace('\\', '/', $document_root), '', str_replace('\\', '/', $file_dir)),
                //    '/');
                $url = $curr_host . '/' . $script_uri;
            }
            else
            {
                //$script_uri = ltrim(mb_substr($file_dir, mb_strpos($file_dir, $doc_root) + mb_strlen($doc_root)), '/');
                $file_dir = explode('/', $file_dir);
                $max_root = array();
                $file_dir = array_reverse($file_dir);
                foreach ($file_dir as $segment)
                {

                    if (mb_substr($doc_root, -mb_strlen($segment) - 1, mb_strlen($segment) + 1) != '/' . $segment)
                    {
                        array_unshift($max_root, $segment);
                    }
                    else
                    {
                        break;
                    }
                }
                $script_uri = implode('/', $max_root);


                //$script_uri = trim(str_replace(str_replace('\\', '/', $document_root), '', str_replace('\\', '/', $file_dir)),
                //   '/');
                $request_uri = trim($_SERVER['REQUEST_URI'], '/');


                $script_uri_a = /*explode('/', $script_uri)*/ $max_root;
                $request_uri_a = explode('/', $request_uri);
                $count = count($request_uri_a);
                $new_url = array();
                for ($i = 0; $i < $count; ++$i)
                {
                    if (isset($script_uri_a[$i]) && $script_uri_a[$i] == $request_uri_a[$i])
                    {
                        $new_url[] = $request_uri_a[$i];
                    }
                    else
                    {
                        break;
                    }
                }
                if (dirname($request_uri) != $script_uri)
                {
                    foreach (explode('/', ltrim($url, '/')) as $segment)
                    {
                        if ($segment == '..')
                        {
                            array_pop($new_url);
                        }
                        else
                        {
                            $new_url[] = $segment;
                        }
                    }
                }
                if ($new_url)
                {
                    $url = $curr_host . '/' . implode('/', $new_url);
                }

            }
        } //echo $url.'<br />';

        return $url;
    }
    protected function file_link($field, $primary_val, $thumb = false, $crop = false, $filename = false)
    {

        $params = array('xcrud' => array(
                'instance' => $this->instance_name,
                'field' => $field,
                'primary' => $primary_val,
                'key' => $this->key,
                'task' => 'file',
                'rand' => base_convert(sha1($this->instance_name . ($filename ? $filename : rand())), 10, 36)));
        if ($thumb !== false)
        {
            $params['xcrud']['thumb'] = $thumb;
        }
        if ($crop)
        {
            $params['xcrud']['crop'] = $crop;
        }
        if (Xcrud_config::$dynamic_session)
        {
            $params['xcrud']['sess_name'] = session_name();
        }
        return Xcrud_config::$scripts_url . '/' . Xcrud_config::$ajax_uri . '?' . http_build_query($params);
    }
    protected function real_file_link($filename, $params, $is_details = false)
    {
        $url = rtrim($params['url'], '/');
        if ($is_details && isset($params['detail_thumb']) && isset($params['thumbs'][$params['detail_thumb']]))
        {
            $th = $params['thumbs'][$params['detail_thumb']];
            if (isset($th['folder']))
            {
                $url .= '/' . trim($th['folder'], '/');
            }
            if (isset($th['marker']))
            {
                $url .= '/' . $this->_thumb_name($filename, $th['marker']);
            }
            else
            {
                $url .= '/' . $filename;
            }
        }
        elseif (!$is_details && isset($params['grid_thumb']) && isset($params['thumbs'][$params['grid_thumb']]))
        {
            $th = $params['thumbs'][$params['grid_thumb']];
            if (isset($th['folder']))
            {
                $url .= '/' . trim($th['folder'], '/');
            }
            if (isset($th['marker']))
            {
                $url .= '/' . $this->_thumb_name($filename, $th['marker']);
            }
            else
            {
                $url .= '/' . $filename;
            }
        }
        else
        {
            $url .= '/' . $filename;
        }
        return $url;
    }
    protected function html_safe($text)
    {
        return htmlspecialchars((string )$text, ENT_QUOTES, Xcrud_config::$mbencoding);
    }
    protected function _clone_row()
    {
        if (is_array($this->table_info) && count($this->table_info) && !$this->table_ro)
        {
            $db = Xcrud_db::get_instance($this->connection);
            $fields = array();
            $row = array();
            $this->find_details_text_variables();
            if ($this->direct_select_tags)
            {
                foreach ($this->direct_select_tags as $key => $dsf)
                {
                    $fields[$key] = "`{$dsf['table']}`.`{$dsf['field']}` AS `{$key}`";
                }
            }
            if ($fields)
            {
                if (!$this->join)
                {
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` WHERE `{$this->primary_key}` = " . $db->escape($this->
                        primary_val) . " LIMIT 1");
                    $row = $db->row();

                }
                else
                {
                    $tables = array('`' . $this->table . '`');
                    $joins = array();
                    foreach ($this->join as $alias => $param)
                    {
                        $tables[] = '`' . $alias . '`';
                        $joins[] = "INNER JOIN `{$param['join_table']}` AS `{$alias}`
                    ON `{$param['table']}`.`{$param['field']}` = `{$alias}`.`{$param['join_field']}`";
                    }
                    $db->query('SELECT ' . implode(',', $fields) . " FROM `{$this->table}` AS `{$this->table}` " . implode(' ', $joins) .
                        " WHERE `{$this->table}`.`{$this->primary_key}` = " . $db->escape($this->primary_val));
                    $row = $db->row();
                }
            }

            if (!$this->is_duplicate($row))
                return self::error('Forbidden');

            $columns = array();
            $this->primary_ai = false;
            foreach ($this->table_info as $table => $types)
            {
                foreach ($types as $row)
                {
                    $field_index = "{$table}.{$row['Field']}";
                    if ($row['Key'] == 'PRI' && $row['Extra'] == 'auto_increment')
                    {
                        if ($table == $this->table)
                            $this->primary_ai = "`{$table}`.`{$row['Field']}`";
                    }
                    elseif ($row['Key'] == 'UNI' or $row['Key'] == 'PRI')
                    {
                        self::error('Duplication impossible. The table has a unique field.');
                    }
                    else
                    {
                        $columns[$field_index] = array('table' => $table, 'field' => $row['Field']);
                    }
                }
            }
            if (!$this->primary_ai)
                self::error('Duplication impossible. Table does not have a primary autoincrement field.');
            $select = $this->_build_select_clone($columns);
            $where = $this->_build_where();
            $table_join = $this->_build_table_join();
            $where_ai = $where ? "AND {$this->primary_ai} = " . (int)$this->primary_val : "WHERE {$this->primary_ai} = " . (int)$this->
                primary_val;
            $db->query("SELECT {$select}\r\n FROM `{$this->table}`\r\n {$table_join}\r\n {$where}\r\n {$where_ai} LIMIT 1");
            $postdata = $db->row();
            if (isset($this->pass_var['create']))
            {
                foreach ($this->pass_var['create'] as $field => $pv)
                {
                    $postdata[$field] = $pv['value'];
                }
            }
            if (!$this->demo_mode)
                $this->_insert($postdata, true, $columns);
        }
        $this->task = 'list';
    }
    protected function _build_select_clone($columns)
    {
        $fields = array();
        foreach ($columns as $key => $val)
        {
            if ($val)
                $fields[] = "`{$val['table']}`.`{$val['field']}` AS `$key`";
        }
        return implode(',', $fields);
    }
    protected function send_email($to, $subject = '(No subject)', $message = '', $cc = array(), $html = true)
    {
        $header = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/' . ($html ? 'html' : 'plain') . '; charset=UTF-8' . "\r\n" .
            'From: ' . Xcrud_config::$email_from_name . ' <' . Xcrud_config::$email_from . ">\r\n";
        if ($cc)
            $header .= 'Cc: ' . implode(',', $cc) . "\r\n";
        if ($html)
            $message = '<!DOCTYPE HTML><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /><title>' . $subject .
                '</title></head><body>' . $message . '</body></html>';
        mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $header);
    }
    protected function _cell_attrib($field, $value, $order, &$row, $is_sum = false, $row_color = false, $row_class = false)
    {
        $attr = array();
        if (isset($this->column_class[$field]))
            $column_class = $this->column_class[$field];
        else
            $column_class = array();
        if ($row_class)
            $column_class[] = $row_class;
        if ($field == $order && $this->is_sortable)
            $column_class[] = 'xcrud-current';
        if ($is_sum)
            $column_class[] = 'xcrud-sum';
        if ($row_color)
        {
            $attr['style'] = $row_color;
        }
        if (isset($this->highlight[$field]))
        {
            foreach ($this->highlight[$field] as $params)
            {
                $params['value'] = $this->replace_text_variables($params['value'], $row, true);
                if ($this->_compare($value, $params['operator'], $params['value']))
                {
                    if ($params['color'])
                        $attr['style'] = 'background-color:' . $params['color'] . ';';
                    if ($params['class'])
                        $column_class[] = $params['class'];
                }
            }
        }
        if ($column_class)
        {
            $column_class = array_unique($column_class);
            $attr['class'] = implode(' ', $column_class);
            $attr['class'] = $this->replace_text_variables($attr['class'], $row, true);
        }
        return $attr;
    }
    protected function _get_table($method)
    {
        if (!$this->table && !$this->query)
            self::error('You must define your table before using the <strong>' . $method . '</strong> method.');
        else
            return $this->table ? $this->table : '';
        return false;
    }
    protected function _get_language() // loads language array from ini file
    {
        if (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/' . $this->language . '.ini'))
            self::$lang_arr = parse_ini_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/' . $this->language . '.ini');
        elseif (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/en.ini'))
            self::$lang_arr = parse_ini_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/en.ini');
        if ($this->set_lang)
        {
            self::$lang_arr = array_merge(self::$lang_arr, $this->set_lang);
        }
    }
    protected static function _get_language_static()
    {
        if (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/' . Xcrud_config::$language . '.ini'))
            self::$lang_arr = parse_ini_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/' . Xcrud_config::$language . '.ini');
        elseif (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/en.ini'))
            self::$lang_arr = parse_ini_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/en.ini');
    }
    protected function _get_theme_config()
    { // loads theme configuration from ini file
        if (is_file(XCRUD_PATH . '/' . Xcrud_config::$themes_path . '/' . $this->theme . '/xcrud.ini'))
            $this->theme_config = parse_ini_file(XCRUD_PATH . '/' . Xcrud_config::$themes_path . '/' . $this->theme . '/xcrud.ini');
        else
            self::error('xcrud.ini does not exist in your theme folder');
    }
    protected function lang($text = '')
    {
        $langtext = mb_convert_case($text, MB_CASE_LOWER, Xcrud_config::$mbencoding);
        return htmlspecialchars((isset(self::$lang_arr[$langtext]) ? self::$lang_arr[$langtext] : $text), ENT_QUOTES,
            Xcrud_config::$mbencoding);
    }
    protected function theme_config($text = '')
    {
        $text = mb_convert_case($text, MB_CASE_LOWER, Xcrud_config::$mbencoding);
        return htmlspecialchars((isset($this->theme_config[$text]) ? $this->theme_config[$text] : ''), ENT_QUOTES, Xcrud_config::
            $mbencoding);
    }
    protected function _thumb_name($name, $marker)
    {
        return substr_replace($name, $marker, strrpos($name, '.'), 0);
    }

    public function _parse_field_names($fields = '', $location = '', $table = false, $insert_prefix = true)
    {
        $field_names = array();
        if ($fields)
        {
            if (!$table)
            {
                $table = $this->_get_table($location);
            }

            if ($insert_prefix)
            {
                $prefix = $this->prefix;
            }
            else
            {
                $prefix = '';
            }

            if (is_array($fields))
            {
                foreach ($fields as $key => $val)
                {
                    if (is_int($key))
                    {
                        if (!strpos($val, '.'))
                            $field_names[$this->make_field_alias($table, $val)] = array('table' => $table, 'field' => $val);
                        else
                        {
                            $tmp = explode('.', $val, 2);
                            $field_names[$this->make_field_alias($tmp[0], $tmp[1], $prefix)] = array('table' => $prefix . $tmp[0], 'field' => $tmp[1]);
                            unset($tmp);
                        }
                    }
                    else
                    {
                        if (!strpos($key, '.'))
                            $field_names[$this->make_field_alias($table, $key)] = array(
                                'table' => $table,
                                'field' => $key,
                                'value' => $val);
                        else
                        {
                            $tmp = explode('.', $key, 2);
                            $field_names[$this->make_field_alias($tmp[0], $tmp[1], $prefix)] = array(
                                'table' => $prefix . $tmp[0],
                                'field' => $tmp[1],
                                'value' => $val);
                            unset($tmp);
                        }
                    }
                }
            }
            else
            {
                $fields = explode(',', $fields);
                foreach ($fields as $key => $val)
                {
                    $val = trim($val);
                    if (!strpos($val, '.'))
                        $field_names[$this->make_field_alias($table, $val)] = array('table' => $table, 'field' => $val);
                    else
                    {
                        $tmp = explode('.', $val, 2);
                        $field_names[$this->make_field_alias($tmp[0], $tmp[1], $prefix)] = array('table' => $prefix . $tmp[0], 'field' => $tmp[1]);
                        unset($tmp);
                    }
                }
            }
            unset($fields);
        }
        else
            self::error('You must set field name(s) for the <strong>' . $location . '</strong> method.');
        return $field_names;
    }
    protected function make_field_alias($table, $field, $pefix = '')
    {
        if ($table)
        {
            return $pefix . $table . '.' . $field;

        }
        else
        {
            return $field;
        }
    }
    protected function parse_comma_separated($param)
    {
        /*if (!is_array($param))
        {
        $param = explode(',', (string )$param);
        foreach ($param as $key => $p)
        {
        $param[$key] = trim($p);
        }
        }
        return $param;*/
        if (is_array($param))
        {
            return $param;
        }
        $param = trim($param);
        if (!$param)
        {
            return array();
        }
        $param = preg_replace('/\s*\,\s*/u', ',', $param);
        return explode(',', $param);
    }
    public static function load_css()
    {
        $out = '';

        if (!self::$js_loaded && !self::$instance)
        {
            Xcrud_config::$scripts_url = self::check_url(Xcrud_config::$scripts_url, true);
            Xcrud_config::$editor_url = self::check_url(Xcrud_config::$editor_url);
            Xcrud_config::$editor_init_url = self::check_url(Xcrud_config::$editor_init_url);
        }

        if (self::$css_loaded)
        {
            self::error('Xcrud\'s styles already rendered! Please, set <strong>$manual_load = true</strong> in your configuration file');
        }

        self::$css_loaded = true;
        if (Xcrud_config::$load_bootstrap)
        {
            $out .= '<link href="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
        }
        if (Xcrud_config::$load_jquery_ui)
            $out .= '<link href="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />';
        if (Xcrud_config::$load_jcrop)
            $out .= '<link href="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />';
        $out .= '<link href="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
            '/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" /><link href="' . Xcrud_config::$scripts_url .
            '/' . Xcrud_config::$themes_uri . '/' . Xcrud_config::$theme . '/xcrud.css" rel="stylesheet" type="text/css" />';
        return $out;
    }
    public static function load_js()
    {
        $out = '';
        if (self::$instance)
        {
            $instance = reset(self::$instance);
            $language = $instance->language;
            $instance->_get_language();
        }
        else
        {
            $language = Xcrud_config::$language;
            self::_get_language_static();
        }

        if (!self::$css_loaded && !self::$instance)
        {
            Xcrud_config::$scripts_url = self::check_url(Xcrud_config::$scripts_url, true);
            Xcrud_config::$editor_url = self::check_url(Xcrud_config::$editor_url);
            Xcrud_config::$editor_init_url = self::check_url(Xcrud_config::$editor_init_url);
        }

        if (self::$js_loaded)
        {
            self::error('Xcrud\'s scripts already rendered! Please, set <strong>$manual_load = true</strong> in your configuration file');
        }
        self::$js_loaded = true;
        if (Xcrud_config::$load_jquery)
            $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri . '/jquery.min.js"></script>';
        if (Xcrud_config::$jquery_no_conflict)
        {
            $out .= '
            <script type="text/javascript">
            <!--

            jQuery.noConflict();

            -->
            </script>';
        }
        if (Xcrud_config::$load_jquery_ui)
            $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/jquery-ui/jquery-ui.min.js"></script>';
        if (Xcrud_config::$load_jcrop)
        {
            $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/jcrop/jquery.Jcrop.min.js"></script>';
        }
        if (Xcrud_config::$load_bootstrap)
            $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
                '/bootstrap/js/bootstrap.min.js"></script>';
        $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri .
            '/timepicker/jquery-ui-timepicker-addon.js"></script>';
        if (Xcrud_config::$editor_url)
            $out .= '<script src="' . Xcrud_config::$editor_url . '"></script>';
        if (Xcrud_config::$load_googlemap)
            $out .= '<script src="//maps.google.com/maps/api/js?sensor=false&language=' . $language . '"></script>';
        $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$plugins_uri . '/xcrud.js"></script>';

        $config = array(
            'url' => Xcrud_config::$scripts_url . '/' . Xcrud_config::$ajax_uri,
            'editor_url' => Xcrud_config::$editor_url,
            'editor_init_url' => Xcrud_config::$editor_init_url,
            'force_editor' => Xcrud_config::$force_editor,
            'date_first_day' => Xcrud_config::$date_first_day,
            'date_format' => Xcrud_config::$date_format,
            'time_format' => Xcrud_config::$time_format,
            'lang' => self::$lang_arr,
            'rtl' => Xcrud_config::$is_rtl ? 1 : 0);
        $out .= '
            <script type="text/javascript">
            <!--

           	var xcrud_config = ' . json_encode($config) . ';

            -->
            </script>';
        if ($language != 'en')
        {
            if (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/datepicker/jquery.ui.datepicker-' . $language . '.js'))
                $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$lang_uri .
                    '/datepicker/jquery.ui.datepicker-' . $language . '.js"></script>';
            if (is_file(XCRUD_PATH . '/' . Xcrud_config::$lang_path . '/timepicker/jquery-ui-timepicker-' . $language . '.js'))
                $out .= '<script src="' . Xcrud_config::$scripts_url . '/' . Xcrud_config::$lang_uri .
                    '/timepicker/jquery-ui-timepicker-' . $language . '.js"></script>';
        }

        return $out;
    }

    protected function get_limit_list($limit = 20, $buttons = false)
    {
        $out = '';
        if (!in_array($this->limit, $this->limit_list))
        {
            $this->limit_list = array_merge(array($this->limit), $this->limit_list);
        }
        if ($buttons)
        {
            $out .= $this->open_tag(array(
                'tag' => 'div',
                'class' => 'btn-group xcrud-limit-buttons',
                'data-toggle' => 'buttons-radio'));
            foreach ($this->limit_list as $limts)
            {
                if ($limts == $limit)
                {
                    $active = ' active';
                }
                else
                {
                    $active = '';
                }
                $out .= $this->open_tag(array(
                    'tag' => 'button',
                    'type' => 'button',
                    'class' => $this->theme_config('limit_list') . $active,
                    'data-limit' => $limts), 'xcrud-action') . $this->lang($limts) . $this->close_tag(array('tag' => 'button'));
            }
            $out .= $this->close_tag(array('tag' => 'div'));
        }
        else
        {
            $out .= $this->open_tag(array(
                'tag' => 'select',
                'class' => $this->theme_config('limit_list'),
                'name' => 'limit'), 'xcrud-actionlist xcrud-data');
            foreach ($this->limit_list as $limts)
            {
                $tag = array('tag' => 'option', 'value' => $limts);
                if ($limts == $limit)
                {
                    $tag['selected'] = 'selected';
                }
                $out .= $this->open_tag($tag) . $this->lang($limts) . $this->close_tag($tag);
            }
            $out .= $this->close_tag(array('tag' => 'select'));
        }
        return $out;
    }

    protected function check_file($path, $func_name)
    {
        $path = str_replace('\\', '/', $path);
        list($root_folder) = explode('/', trim(XCRUD_PATH, '/'), 2);
        list($root_path) = explode('/', trim($path, '/'), 2);
        if (strpos($path, '../') !== false or $root_folder != $root_path)
            $path = XCRUD_PATH . '/' . trim($path, '/');
        if (!is_file($path))
            self::error('Wrong path or file is not exist! The <strong>' . $func_name . '</strong> method fails.<br /><small>' . $path .
                '</small>');
        return $path;
    }
    protected function check_folder($path, $func_name)
    {
        $path = str_replace('\\', '/', $path);
        list($root_folder) = explode('/', trim(XCRUD_PATH, '/'), 2);
        list($root_path) = explode('/', trim($path, '/'), 2);
        if (strpos($path, '../') !== false or $root_folder != $root_path)
            $path = XCRUD_PATH . '/' . trim($path, '/');
        if (!is_dir($path))
        {
            if (!@mkdir($path))
                self::error('Wrong path or folder is not exist! The <strong>' . $func_name . '</strong> method fails.<br /><small>' . $path .
                    '</small>');
        }
        return $path;
    }


    protected function additional_columns($fields = '')
    {
        if ($fields)
        {
            $fdata = $this->_parse_field_names($fields, 'additional_column');
            foreach ($fdata as $key => $fitem)
            {
                if (!isset($this->subselect[$key]) && $fitem['field'] != 'value' && mb_substr_count($key, '.') < 2)
                {
                    if (!isset($this->columns[$key]))
                    {
                        $this->hidden_columns[$key] = $fitem;
                    }
                    $this->direct_select_tags[$key] = array('field' => $fitem['field'], 'table' => $fitem['table']); // will be get from db anyway
                }
            }
        }
    }
    protected function additional_fields($fields = '')
    {
        if ($fields)
        {
            $fdata = $this->_parse_field_names($fields, 'additional_field');
            foreach ($fdata as $key => $fitem)
            {
                if (!isset($this->subselect[$key]) && $fitem['field'] != 'value' && mb_substr_count($key, '.') < 2)
                {
                    if (!isset($this->fields[$key]))
                    {
                        $this->hidden_fields[$key] = array('field' => $fitem['field'], 'table' => $fitem['table']);
                        $this->locked_fields[$key] = true;
                    }
                    $this->direct_select_tags[$key] = array('field' => $fitem['field'], 'table' => $fitem['table']); // will be get from db anyway
                }
            }
        }
    }

    /** Unlocks additional postdata fields (locked with security reason). This can be used only with callbacks */
    public function unlock_field($fields = '')
    {
        if ($fields)
        {
            $fdata = $this->_parse_field_names($fields, 'unlock_field');
            foreach ($fdata as $key => $fitem)
            {
                if (!isset($this->fields[$key]))
                {
                    $this->fields[$key] = $fitem;
                }
                if (isset($this->locked_fields[$key]))
                {
                    unset($this->locked_fields[$key]);
                }
            }
        }
    }

    protected function extract_fields_from_text($text, $mode = 'columns')
    {
        $found = preg_match_all('/\{([^\}]+)\}/u', $text, $matches);
        if ($found)
        {
            switch ($mode)
            {
                case 'columns':
                    $this->additional_columns($matches[1]);
                    break;
                case 'fields':
                    $this->additional_fields($matches[1]);
                    break;
            }
        }
    }
    protected function find_grid_text_variables()
    {
        if (!Xcrud_config::$performance_mode)
        {
            if ($this->column_pattern)
            {
                foreach ($this->column_pattern as $item)
                {
                    $this->extract_fields_from_text($item, 'columns');
                }
            }
            if ($this->buttons)
            {
                foreach ($this->buttons as $button)
                {
                    $this->extract_fields_from_text($button['link'], 'columns');
                    if ($button['params'])
                    {
                        foreach ($button['params'] as $param)
                        {
                            $this->extract_fields_from_text($param, 'columns');
                        }
                    }
                }
            }
            /*if ($this->condition)
            {
            foreach ($this->condition as $item)
            {
            $this->extract_fields_from_text($item['value'], 'columns');
            }
            }
            if ($this->grid_condition)
            {
            foreach ($this->grid_condition as $item)
            {
            $this->extract_fields_from_text($item['value'], 'columns');
            }
            }*/
            if ($this->highlight)
            {
                foreach ($this->highlight as $item)
                {
                    foreach ($item as $itm)
                    {
                        $this->extract_fields_from_text($itm['value'], 'columns');
                    }
                }
            }
            if ($this->highlight_row)
            {
                foreach ($this->highlight_row as $item)
                {
                    $this->extract_fields_from_text($item['value'], 'columns');
                }
            }
            if ($this->column_class)
            {
                foreach ($this->column_class as $item)
                {
                    $this->extract_fields_from_text(implode(' ', $item), 'columns');
                }
            }
            if ($this->grid_restrictions)
            {
                foreach ($this->grid_restrictions as $item)
                {
                    $this->extract_fields_from_text($item['value'], 'columns');
                    $this->additional_columns($item['field']);
                }
            }
        }
    }
    protected function find_details_text_variables()
    {
        if ($this->send_external_create)
        {
            foreach ($this->send_external_create['data'] as $item)
            {
                $this->extract_fields_from_text($item, 'fields');
            }
            if ($this->send_external_create['where_field'])
                $this->additional_fields($this->send_external_create['where_field']);
        }
        if ($this->send_external_edit)
        {
            foreach ($this->send_external_edit['data'] as $item)
            {
                $this->extract_fields_from_text($item, 'fields');
            }
            if ($this->send_external_edit['where_field'])
                $this->additional_fields($this->send_external_edit['where_field']);
        }
        if ($this->pass_var)
        {
            foreach ($this->pass_var as $mode => $actions)
            {
                foreach ($actions as $vars)
                {
                    $this->extract_fields_from_text($vars['value'], 'fields');
                }
            }
        }
        if ($this->relation)
        {
            foreach ($this->relation as $field => $params)
            {
                if ($params['rel_where'])
                {
                    if (is_array($params['rel_where']))
                    {
                        foreach ($params['rel_where'] as $vars)
                        {
                            $this->extract_fields_from_text($vars, 'fields');
                        }
                    }
                    else
                    {
                        $this->extract_fields_from_text($params['rel_where'], 'fields');
                    }
                }
            }
        }
        if ($this->fk_relation)
        {
            foreach ($this->fk_relation as $field => $params)
            {
                if ($params['rel_where'])
                {
                    if (is_array($params['rel_where']))
                    {
                        foreach ($params['rel_where'] as $vars)
                        {
                            $this->extract_fields_from_text($vars, 'fields');
                        }
                    }
                    else
                    {
                        $this->extract_fields_from_text($params['rel_where'], 'fields');
                    }
                }
            }
        }
        if ($this->grid_restrictions)
        {
            foreach ($this->grid_restrictions as $item)
            {
                $this->extract_fields_from_text($item['value'], 'fields');
                $this->additional_fields($item['field']);
            }
        }
        if ($this->column_pattern)
        {
            foreach ($this->column_pattern as $item)
            {
                $this->extract_fields_from_text($item, 'fields');
            }
        }
        if ($this->condition)
        {
            foreach ($this->condition as $item)
            {
                $this->extract_fields_from_text($item['value'], 'fields');
                $this->additional_fields($item['field']);
            }
        }
    }
    protected function replace_text_variables($value, array $data, $safety = false, $null_if_empty = false)
    {
        if (!is_array($value) && !Xcrud_config::$performance_mode && $value)
        {
            foreach ($data as $key => $val)
            {
                $tmp = explode('.', $key);
                if (count($tmp) > 1)
                {
                    list($tbl, $fld) = $tmp;
                }
                else
                {
                    $tbl = $this->table;
                    $fld = $val;
                }
                if (!is_array($val))
                {
                    $value = str_ireplace('{' . $key . '}', $safety ? $this->html_safe($val) : $val, $value);
                    if ($tbl == $this->table)
                        $value = str_ireplace('{' . $fld . '}', $safety ? $this->html_safe($val) : $val, $value);
                }
            }
        }
        if ($value === '' && $null_if_empty)
        {
            $value = 'NULL';
        }
        return $value;
    }


    protected function get_browser_info($ch)
    {
        if ($_COOKIE)
        {
            $ca = http_build_query($_COOKIE);
            $ca = str_replace('&', ';', $ca);
            curl_setopt($ch, CURLOPT_COOKIE, $ca);
        }
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }
    protected function send_http_request($url, $data, $method, $return_result = false)
    {
        //$path = self::check_url($url);
        $path = $url;
        $data = http_build_query($data);
        switch ($method)
        {
            case 'get':
                $ch = curl_init($path . ((mb_strpos($path, '?', 0, Xcrud_config::$mbencoding) === false) ? '?' : '&') . $data);
                break;
            case 'post':
                $ch = curl_init($path);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                return;
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (!$return_result)
        {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        }
        else
        {
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        }
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        if (Xcrud_config::$use_browser_info)
        {
            $this->get_browser_info($ch);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    protected function open_tag($tag = '', $class = '', $attr = array(), $enc_name = false)
    {
        if ($tag)
        {
            if (!is_array($tag))
            {
                $tag = array('tag' => $tag);
            }
            if (isset($tag['tag']))
            {
                $out = '<' . $tag['tag'];
                unset($tag['tag']);
                if ($attr)
                {
                    if (isset($attr['values']))
                    {
                        unset($attr['values']);
                    }
                    $tag = array_merge($tag, $attr);
                }
                if ($class && isset($tag['class']))
                {
                    $tag['class'] .= ' ' . $class;
                }
                elseif ($class)
                {
                    $tag['class'] = $class;
                }
                if ($enc_name && isset($tag['name']))
                {
                    $tag['name'] = $this->fieldname_encode($tag['name']);
                }
                if ($enc_name && isset($tag['data-depend']))
                {
                    $tag['data-depend'] = $this->fieldname_encode($tag['data-depend']);
                }
                if ($enc_name && isset($tag['data-rangestart']))
                {
                    $tag['data-rangestart'] = $this->fieldname_encode($tag['data-rangestart']);
                }
                if ($enc_name && isset($tag['data-rangeend']))
                {
                    $tag['data-rangeend'] = $this->fieldname_encode($tag['data-rangeend']);
                }
                if ($tag)
                {
                    foreach ($tag as $key => $val)
                    {
                        if ($key == 'href' or $key == 'src')
                        {
                            $out .= ' ' . (string )$key . '="' . (string )$val . '"';
                        }
                        else
                        {
                            $out .= ' ' . (string )$key . '="' . $this->html_safe((string )$val) . '"';
                        }
                    }
                }
                $out .= '>';
                return $out;
            }
        }
        else
            return '';
    }
    protected function close_tag($tag = '')
    {
        if ($tag)
        {
            if (!is_array($tag))
            {
                $tag = array('tag' => $tag);
            }
            if (isset($tag['tag']))
            {
                return '</' . $tag['tag'] . '>';
            }
        }
        else
            return '';
    }
    protected function single_tag($tag = '', $class = '', $attr = array(), $enc_name = false)
    {
        if ($tag)
        {
            if (!is_array($tag))
            {
                $tag = array('tag' => $tag);
            }
            if (isset($tag['tag']))
            {
                $out = '<' . $tag['tag'];
                unset($tag['tag']);
                if ($attr)
                {
                    if (isset($attr['values']))
                    {
                        unset($attr['values']);
                    }
                    $tag = array_merge($tag, $attr);
                }
                if ($class && isset($tag['class']))
                {
                    $tag['class'] .= ' ' . $class;
                }
                elseif ($class)
                {
                    $tag['class'] = $class;
                }
                if ($enc_name && isset($tag['name']))
                {
                    $tag['name'] = $this->fieldname_encode($tag['name']);
                }
                if ($enc_name && isset($tag['data-depend']))
                {
                    $tag['data-depend'] = $this->fieldname_encode($tag['data-depend']);
                }
                if ($enc_name && isset($tag['data-rangestart']))
                {
                    $tag['data-rangestart'] = $this->fieldname_encode($tag['data-rangestart']);
                }
                if ($enc_name && isset($tag['data-rangeend']))
                {
                    $tag['data-rangeend'] = $this->fieldname_encode($tag['data-rangeend']);
                }
                if ($tag)
                {
                    foreach ($tag as $key => $val)
                    {
                        if ($key == 'href' or $key == 'src')
                        {
                            $out .= ' ' . (string )$key . '="' . (string )$val . '"';
                        }
                        else
                        {
                            $out .= ' ' . (string )$key . '="' . $this->html_safe((string )$val) . '"';
                        }
                    }
                }
                $out .= ' />';
                return $out;
            }
        }
        else
            return '';
    }

    /** autorender of details fields and tabs */
    protected function render_fields_list($mode, $container = 'table', $row = 'tr', $label = 'td', $field = 'td', $tabs_block =
        'div', $tabs_head = 'ul', $tabs_row = 'li', $tabs_link = 'a', $tabs_content = 'div', $tabs_pane = 'div')
    {
        $out = '';
        $tabs_out = array();
        $raw_out = array();
        foreach ($this->fields_output as $key => $item)
        {
            $row_class = $this->theme_config('details_row');
            if ($this->primary_key == $item['name'])
            {
                $row_class .= ' primary';
            }
            if (isset($this->fields[$item['name']]['tab']) && $this->fields[$item['name']]['tab'])
            {
                $tabs_out[$this->fields[$item['name']]['tab']][] = $this->open_tag($row, $row_class) . $this->open_tag($label, $this->
                    theme_config('details_label_cell')) . $item['label'] . $this->get_field_tooltip($item['name'], $mode) . $this->
                    close_tag($label) . $this->open_tag($field, $this->theme_config('details_field_cell')) . $item['field'] . $this->
                    close_tag($field) . $this->close_tag($row);
            }
            else
            {
                $raw_out[] = $this->open_tag($row, $row_class) . $this->open_tag($label, $this->theme_config('details_label_cell')) . $item['label'] .
                    $this->get_field_tooltip($item['name'], $mode) . $this->close_tag($label) . $this->open_tag($field, $this->theme_config
                    ('details_field_cell')) . $item['field'] . $this->close_tag($field) . $this->close_tag($row);
            }
        }
        if (isset($this->field_tabs[$mode]) or $this->default_tab !== false)
        {
            $tabs_header = $this->open_tag($tabs_block, $this->theme_config('tabs_container'), array('class' => 'xcrud-tabs')) . $this->
                open_tag($tabs_head, $this->theme_config('tabs_header_row'));
            $tabs_body = $this->open_tag($tabs_content, $this->theme_config('tabs_content'));
            $k = 0;

            if ($this->default_tab !== false && $raw_out)
            {
                $tabid = 'tabid_' . base_convert(rand(), 10, 36);
                $tabs_header .= $this->open_tag($tabs_row, $this->theme_config('tabs_header_cell') . ($k == 0 ? ' ' . $this->
                    theme_config('tabs_first_element') : '')) . $this->open_tag($tabs_link, $this->theme_config('tabs_header_link') . ($k ==
                    0 ? ' ' . $this->theme_config('tabs_first_element') : ''), array('href' => '#' . $tabid)) . $this->default_tab . $this->
                    close_tag($tabs_link) . $this->close_tag($tabs_row);
                $tabs_body .= $this->open_tag($tabs_pane, $this->theme_config('tabs_content_pane') . ($k == 0 ? ' ' . $this->
                    theme_config('tabs_first_element') : ''), array('id' => $tabid)) . $this->open_tag($container, $this->theme_config('details_container')) .
                    implode('', $raw_out) . $this->close_tag($container) . $this->close_tag($tabs_pane);
                ++$k;
                $raw_out = array();
            }

            if (isset($this->field_tabs[$mode]) && $tabs_out)
            {
                foreach ($this->field_tabs[$mode] as $key => $tabname)
                {
                    if (isset($tabs_out[$tabname]))
                    {
                        $tabid = 'tabid_' . base_convert(rand(), 10, 36);
                        $tabs_header .= $this->open_tag($tabs_row, $this->theme_config('tabs_header_cell') . ($k == 0 ? ' ' . $this->
                            theme_config('tabs_first_element') : '')) . $this->open_tag($tabs_link, $this->theme_config('tabs_header_link') . ($k ==
                            0 ? ' ' . $this->theme_config('tabs_first_element') : ''), array('href' => '#' . $tabid)) . $tabname . $this->close_tag($tabs_link) .
                            $this->close_tag($tabs_row);
                        $tabs_body .= $this->open_tag($tabs_pane, $this->theme_config('tabs_content_pane') . ($k == 0 ? ' ' . $this->
                            theme_config('tabs_first_element') : ''), array('id' => $tabid)) . $this->open_tag($container, $this->theme_config('details_container')) .
                            implode('', $tabs_out[$tabname]) . $this->close_tag($container) . $this->close_tag($tabs_pane);
                        ++$k;
                    }
                }
            }

            if ($this->nested_rendered && Xcrud_config::$nested_in_tab)
            {
                foreach ($this->nested_rendered as $tabname => $content)
                {
                    $tabid = 'tabid_' . base_convert(rand(), 10, 36);
                    $tabs_header .= $this->open_tag($tabs_row, $this->theme_config('tabs_header_cell') . ($k == 0 ? ' ' . $this->
                        theme_config('tabs_first_element') : '')) . $this->open_tag($tabs_link, $this->theme_config('tabs_header_link') . ($k ==
                        0 ? ' ' . $this->theme_config('tabs_first_element') : ''), array('href' => '#' . $tabid)) . $tabname . $this->close_tag($tabs_link) .
                        $this->close_tag($tabs_row);
                    $tabs_body .= $this->open_tag($tabs_pane, $this->theme_config('tabs_content_pane') . ($k == 0 ? ' ' . $this->
                        theme_config('tabs_first_element') : ''), array('id' => $tabid)) . $content . $this->close_tag($tabs_pane);
                    ++$k;
                    unset($this->nested_rendered[$tabname]);
                }
            }

            $out .= $tabs_header . $this->close_tag($tabs_head) . $tabs_body . $this->close_tag($tabs_content) . $this->close_tag($tabs_block);
        }

        if ($raw_out)
        {
            $out .= $this->open_tag($container, $this->theme_config('details_container')) . implode('', $raw_out) . $this->
                close_tag($container);
        }
        //$out .= implode('', $this->hidden_fields_output);
        return $out;
    }
    /** table tooltip render */
    protected function get_table_tooltip()
    {
        $out = '';
        if ($this->table_tooltip)
        {
            $out .= ' ';
            $out .= $this->open_tag(array(
                'tag' => 'a',
                'href' => 'javascript:;',
                'class' => 'xcrud-tooltip xcrud-button-link',
                'title' => $this->table_tooltip['tooltip']));
            $out .= $this->open_tag(array('tag' => 'i', 'class' => ($this->table_tooltip['icon'] ? $this->table_tooltip['icon'] : $this->
                    theme_config('tooltip_icon'))));
            $out .= $this->close_tag('i');
            $out .= $this->close_tag('a');
        }
        return $out;
    }
    /** field tooltip render */
    protected function get_field_tooltip($field, $mode)
    {
        $out = '';
        if ($this->field_tooltip && isset($this->field_tooltip[$field]))
        {
            $out .= ' ';
            $out .= $this->open_tag(array(
                'tag' => 'a',
                'href' => 'javascript:;',
                'class' => 'xcrud-tooltip xcrud-button-link',
                'title' => $this->field_tooltip[$field]['tooltip']));
            $out .= $this->open_tag(array('tag' => 'i', 'class' => ($this->field_tooltip[$field]['icon'] ? $this->field_tooltip[$field]['icon'] :
                    $this->theme_config('tooltip_icon'))));
            $out .= $this->close_tag('i');
            $out .= $this->close_tag('a');
        }
        return $out;
    }
    /** column tooltip render */
    protected function get_column_tooltip($field)
    {
        $out = '';
        if ($this->column_tooltip && isset($this->column_tooltip[$field]))
        {
            $out .= ' ';
            $out .= $this->open_tag(array(
                'tag' => 'a',
                'href' => 'javascript:;',
                'class' => 'xcrud-tooltip xcrud-button-link',
                'title' => $this->column_tooltip[$field]['tooltip']));
            $out .= $this->open_tag(array('tag' => 'i', 'class' => ($this->column_tooltip[$field]['icon'] ? $this->column_tooltip[$field]['icon'] :
                    $this->theme_config('tooltip_icon'))));
            $out .= $this->close_tag('i');
            $out .= $this->close_tag('a');
        }
        return $out;
    }
    /** search constructor and renderer */
    protected function render_search()
    {
        $out = '';
        $phrase = '';
        $optlist = array();
        $fieldlist = array();
        $is_daterange = false;
        if ($this->is_search)
        {
            if (is_array($this->phrase) && isset($this->field_type[$this->column]))
            {
                switch ($this->field_type[$this->column])
                {
                    case 'timestamp':
                    case 'datetime':
                        $phrase = array('from' => $this->unix2datetime((int)$this->phrase['from'], true), 'to' => $this->unix2datetime((int)$this->
                                phrase['to'], true));
                        $is_daterange = true;
                        break;
                    case 'date':
                        $phrase = array('from' => $this->unix2date((int)$this->phrase['from'], true), 'to' => $this->unix2date((int)$this->
                                phrase['to'], true));
                        $is_daterange = true;
                        break;
                    case 'time':
                        $phrase = array('from' => $this->unix2time((int)$this->phrase['from'], true), 'to' => $this->unix2time((int)$this->
                                phrase['to'], true));
                        break;
                    default:
                        $phrase = '';
                        break;
                }
            }
            else
            {
                $phrase = $this->phrase;
            }

            $attr = array('class' => 'xcrud-search-toggle', 'href' => 'javascript:;');
            if ($this->search or Xcrud_config::$search_opened)
            {
                $attr['style'] = 'display:none;';
            }
            $out .= $this->open_tag('a', $this->theme_config('search_open'), $attr);
            $out .= $this->lang('search') . $this->close_tag('a');
            $attr = array('class' => 'xcrud-search');
            if (!$this->search && !Xcrud_config::$search_opened)
            {
                $attr['style'] = 'display:none;';
            }
            $out .= $this->open_tag('span', $this->theme_config('search_container'), $attr);
            if (Xcrud_config::$search_all)
            {
                $optlist[] = $this->open_tag('option', '', array('value' => '')) . $this->lang('all_fields') . $this->close_tag('option');
                $fieldlist = $this->search_fieldlist('', $phrase, $fieldlist);
            }
            if ($this->search_columns)
            {
                foreach ($this->search_columns as $field => $tmp)
                {
                    if (isset($this->columns_names[$field]))
                    {
                        $name = $this->columns_names[$field];
                    }
                    else
                    {
                        if (isset($this->labels[$field]))
                            $name = $this->html_safe($this->labels[$field]);
                        else
                            $name = $this->html_safe($this->_humanize($tmp['field']));
                    }
                    $attr = array('value' => $field, 'data-type' => $this->field_type[$field]);
                    if ($field == $this->column)
                    {
                        $attr['selected'] = '';
                    }
                    $optlist[] = $this->open_tag('option', '', $attr) . $name . $this->close_tag('option');
                    $fieldlist = $this->search_fieldlist($field, $phrase, $fieldlist);
                }
            }
            elseif ($this->search_default !== false) // not only 'all'
            {
                foreach ($this->columns_names as $field => $title)
                {
                    $attr = array('value' => $field, 'data-type' => $this->field_type[$field]);
                    if ($field == $this->column)
                    {
                        $attr['selected'] = '';
                    }
                    $optlist[] = $this->open_tag('option', '', $attr) . $title . $this->close_tag('option');
                    $fieldlist = $this->search_fieldlist($field, $phrase, $fieldlist);
                }
            }

            $out .= implode('', $fieldlist);
            $attr = array('class' => 'xcrud-data', 'name' => 'column');
            if ($this->search_default === false && !$this->search_columns)
            {
                $out .= $this->open_tag(array('tag' => 'input', 'type' => 'hidden'), 'xcrud-columns-select ' . $this->theme_config('search_fieldlist'),
                    $attr);
            }
            else
            {
                $out .= $this->open_tag('select', 'xcrud-columns-select ' . $this->theme_config('search_fieldlist'), $attr);
                //$out .= $this->open_tag('option', '', array('value' => '')) . $this->lang('all_fields') . $this->close_tag('option');
                $out .= implode('', $optlist);
                $out .= $this->close_tag('select');
            }

            $group = array('tag' => 'span', 'class' => $this->theme_config('grid_button_group'));
            $out .= $this->open_tag($group);

            $attr = array(
                'class' => 'xcrud-action',
                'href' => 'javascript:;',
                'data-search' => 1);
            $out .= $this->open_tag('a', $this->theme_config('search_go'), $attr);
            $out .= $this->lang('go') . $this->close_tag('a');
            if ($this->search)
            {
                $attr = array(
                    'class' => 'xcrud-action',
                    'href' => 'javascript:;',
                    'data-search' => 0);
                $out .= $this->open_tag('a', $this->theme_config('search_reset'), $attr);
                $out .= $this->lang('reset') . $this->close_tag('a');
            }

            $out .= $this->close_tag($group);

            $out .= $this->close_tag('span');
        }
        return $out;
    }
    /** this creates unique field types for search */
    protected function search_fieldlist($field, $phrase, $fieldlist)
    {
        $attr_preset = array('class' => 'xcrud-searchdata', 'name' => 'phrase');
        if ($field == $this->column)
        {
            $class = 'xcrud-search-active';
        }
        else
        {
            $class = '';
            $attr_preset['style'] = 'display:none';
        }
        $attr = $attr_preset;
        $attr['data-type'] = $field ? $this->field_type[$field] : 'text';
        switch ($attr['data-type'])
        {
            case 'text':
            case 'textarea':
            case 'int':
            case 'float':
            default:
                if (!isset($fieldlist['default']) or $field == $this->column)
                {
                    if (!$this->column)
                    {
                        $class = 'xcrud-search-active';
                        $attr['style'] = '';
                    }
                    $attr['data-fieldtype'] = 'default';
                    $attr['type'] = 'text';
                    $attr['value'] = (!is_array($phrase) && $field == $this->column or !$this->column) ? $phrase : '';
                    $fieldlist['default'] = $this->single_tag('input', $class . ' ' . $this->theme_config('search_phrase'), $attr);
                }
                break;
            case 'bool':
                if (!isset($fieldlist['bool']) or $field == $this->column)
                {
                    $attr['data-fieldtype'] = 'bool';
                    $attr['type'] = 'select';
                    $fieldlist['bool'] = $this->open_tag('select', $class . ' ' . $this->theme_config('search_phrase_dropdown'), $attr);
                    $attr = array('value' => 1);
                    if ($phrase == 1)
                    {
                        $attr['selected'] = '';
                    }
                    $fieldlist['bool'] .= $this->open_tag('option', '', $attr) . $this->lang('bool_on') . $this->close_tag('option');
                    $attr = array('value' => 0);
                    if ($phrase == 0)
                    {
                        $attr['selected'] = '';
                    }
                    $fieldlist['bool'] .= $this->open_tag('option', '', $attr) . $this->lang('bool_off') . $this->close_tag('option');
                    $fieldlist['bool'] .= $this->close_tag('select');
                }
                break;
            case 'date':
            case 'datetime':
            case 'time':
            case 'timestamp':
                if (!isset($fieldlist['date']) or $field == $this->column)
                {
                    $attr['data-fieldtype'] = 'date';
                    $attr_range = array(
                        'class' => 'xcrud-daterange xcrud-searchdata ' . $this->theme_config('search_range'),
                        'name' => 'range',
                        'data-fieldtype' => 'date');
                    if ($field != $this->column)
                    {
                        $attr_range['style'] = 'display:none';
                    }

                    $fieldlist['date'] = $this->open_tag('select', $class, $attr_range);
                    $fieldlist['date'] .= $this->open_tag('option', '', array('value' => '')) . $this->lang('choose_range') . $this->
                        close_tag('option');
                    if (Xcrud_config::$available_date_ranges)
                    {
                        foreach (Xcrud_config::$available_date_ranges as $range)
                        {
                            $attr_rs = array('value' => $range);
                            if ($range == $this->range)
                            {
                                $attr_rs['selected'] = '';
                            }
                            $curr_range = $this->get_range($range);
                            if ($curr_range)
                            {
                                $attr_rs['data-from'] = $curr_range['from'];
                                $attr_rs['data-to'] = $curr_range['to'];
                                $fieldlist['date'] .= $this->open_tag('option', '', $attr_rs) . $this->lang($range) . $this->close_tag('option');
                            }
                        }
                    }
                    $fieldlist['date'] .= $this->close_tag('select');
                    $attr['type'] = 'text';
                    $attr['name'] = 'phrase][from';
                    $attr['value'] = ((isset($phrase['from']) && $field == $this->column) ? $phrase['from'] : '');
                    $fieldlist['date'] .= $this->single_tag('input', 'xcrud-datepicker-from ' . $class . ' ' . $this->theme_config('search_from'),
                        $attr);
                    $attr['name'] = 'phrase][to';
                    $attr['value'] = (isset($phrase['to']) && $field == $this->column) ? $phrase['to'] : '';
                    $fieldlist['date'] .= $this->single_tag('input', 'xcrud-datepicker-to ' . $class . ' ' . $this->theme_config('search_to'),
                        $attr);
                }
                break;
            case 'select':
            case 'multiselect':
            case 'radio':
            case 'checkboxes':
                $attr['data-fieldtype'] = 'dropdown';
                $attr['data-fieldname'] = $field;
                $tmp = '';
                $tmp .= $this->open_tag('select', $class . ' ' . $this->theme_config('search_phrase_dropdown'), $attr);
                if (is_array($this->field_attr[$field]['values']))
                {
                    foreach ($this->field_attr[$field]['values'] as $optkey => $opt)
                    {
                        if (is_array($opt))
                        {
                            $tmp .= $this->open_tag(array('tag' => 'optgroup', 'label' => $optkey));
                            foreach ($opt as $k_key => $k_opt)
                            {
                                $opt_tag = array('tag' => 'option', 'value' => $k_key);
                                if ($k_key == $phrase && $field == $this->column)
                                {
                                    $opt_tag['selected'] = '';
                                }
                                $tmp .= $this->open_tag($opt_tag) . $this->html_safe($k_opt) . $this->close_tag($opt_tag);
                            }
                            $tmp .= $this->close_tag('optgroup');
                        }
                        else
                        {
                            $opt_attr = array('value' => $optkey);
                            if ($optkey == $phrase && $field == $this->column)
                            {
                                $opt_attr['selected'] = '';
                            }
                            $tmp .= $this->open_tag('option', '', $opt_attr) . $this->html_safe($opt) . $this->close_tag('option');
                        }
                    }
                }
                else
                {
                    $opts = $this->parse_comma_separated($this->field_attr[$field]['values']);
                    foreach ($opts as $opt)
                    {
                        $opt = trim($opt, '\'');
                        $opt_attr = array('value' => $opt);
                        if ($opt == $phrase && $field == $this->column)
                        {
                            $opt_attr['selected'] = '';
                        }
                        $tmp .= $this->open_tag('option', '', $opt_attr) . $this->html_safe($opt) . $this->close_tag('option');
                    }
                }
                $tmp .= $this->close_tag('select');
                $fieldlist[] = $tmp;
                break;
        }
        return $fieldlist;
    }

    protected function render_search_hidden()
    {
        $out = '';

        $tag = array(
            'tag' => 'input',
            'type' => 'hidden',
            'class' => 'xcrud-data');
        if ($this->search)
        {
            if ($this->column)
            {
                switch ($this->field_type[$this->column])
                {
                    case 'timestamp':
                    case 'datetime':
                    case 'date':
                    case 'time':
                        $out .= $this->single_tag($tag, '', array('name' => 'phrase][from', 'value' => (isset($this->phrase['from']) ? $this->
                                phrase['from'] : '')));
                        $out .= $this->single_tag($tag, '', array('name' => 'phrase][to', 'value' => (isset($this->phrase['to']) ? $this->
                                phrase['to'] : '')));
                        break;
                    default:
                        $out .= $this->single_tag($tag, '', array('name' => 'phrase', 'value' => (!is_array($this->phrase) ? $this->phrase : '')));
                        break;
                }
            }
            else
            {
                $out .= $this->single_tag($tag, '', array('name' => 'phrase', 'value' => (!is_array($this->phrase) ? $this->phrase : '')));
            }

            $out .= $this->single_tag($tag, '', array('name' => 'column', 'value' => $this->column));
            $out .= $this->single_tag($tag, '', array('name' => 'range', 'value' => $this->range));
        }
        return $out;
    }

    protected function render_grid_head($row = array('tag' => 'tr'), $item = array('tag' => 'th'), $arrows = array('asc' =>
            '&uarr; ', 'desc' => '&darr; '))
    {
		$out = '';



        $out .= $this->open_tag($row, 'xcrud-th');
        if ($this->is_numbers)
        {

                   $out .= $this->open_tag($item, 'xcrud-num') .  $this->close_tag($item);



        }
        if (($this->is_edit || $this->is_remove || $this->is_view || $this->is_duplicate || $this->buttons || $this->
            grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'left')
        {
            $out .= $this->open_tag($item, 'xcrud-actions') . '&nbsp;' . $this->close_tag($item);
        }
        foreach ($this->columns as $field => $fitem)
        {
            if (isset($this->field_type[$field]) && ($this->field_type[$field] == 'password' or $this->field_type[$field] ==
                'hidden'))
                continue;
            $fieldname = $this->columns_names[$field];
            $class = 'xcrud-column';
            $attr = array();
            if ($this->is_sortable)
            {
                $class .= ' xcrud-action';
                if ($this->primary_key == $field)
                {
                    $class .= ' xcrud-primary';
                }
                if ($this->order_column == $field)
                {
                    $class .= ' xcrud-current xcrud-' . $this->order_direct;
                    $attr['data-order'] = $this->order_direct == 'asc' ? 'desc' : 'asc';
                }
                else
                {
                    $attr['data-order'] = $this->order_direct;
                }
                $attr['data-orderby'] = $field;
            }
            if (isset($this->column_width[$field]))
            {
                $attr['style'] = 'width:' . $this->column_width[$field] . ';min-width:' . $this->column_width[$field] . ';max-width:' .
                    $this->column_width[$field] . ';';
            }

            $out .= $this->open_tag($item, $class, $attr);
            if ($this->order_column == $field && $arrows && $this->is_sortable)
            {
                $out .= $arrows[$this->order_direct];
            }
            $out .= $fieldname;
            $out .= $this->get_column_tooltip($field);
            $out .= $this->close_tag($item);
        }
        if (($this->is_edit || $this->is_remove || $this->is_view || $this->is_duplicate || $this->buttons || $this->
            grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'right')
        {
            $out .= $this->open_tag($item, 'xcrud-actions') . '&nbsp;' . $this->close_tag($item);
        }
        $out .= $this->close_tag($row);
        return $out;
    }
    protected function render_grid_body($row_tag = array('tag' => 'tr'), $item = array('tag' => 'td'))
    {
        $out = '';
        $i = 0;
        if ($this->result_list)
        {
            foreach ($this->result_list as $key => $row)
            {
                $j = 0;
                $row_color = false;
                $row_class = '';
                if (isset($this->highlight_row))
                {
                    foreach ($this->highlight_row as $params)
                    {
                        $params['value'] = $this->replace_text_variables($params['value'], $row, true);
                        if ($this->_compare($row[$params['field']], $params['operator'], $params['value']))
                        {
                            if ($params['color'])
                                $row_color = 'background-color:' . $params['color'] . ';';
                            if ($params['class'])
                                $row_class .= ' ' . $params['class'];
                        }
                    }
                }
                $out .= $this->open_tag($row_tag, 'xcrud-row xcrud-row-' . $i);
                if ($this->is_numbers)
                {


                else
                {
                  $out .= $this->open_tag($item, 'xcrud-num', $this->_cell_attrib(false, false, $this->order_column, $row, false, $row_color, $row_class)) . $this->close_tag($item);
                }



                }
                if (($this->is_edit || $this->is_remove || $this->is_view || $this->buttons || $this->is_duplicate || $this->
                    grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'left')
                {
                    $out .= $this->open_tag($item, 'xcrud-actions', $this->_cell_attrib(false, false, false, $row, false, $row_color, $row_class));
                    $out .= $this->_render_list_buttons($row);
                    $out .= $this->close_tag($item);
                }
                foreach ($this->columns as $field => $fitem)
                {
                    $value = $row[$field];
                    if (isset($this->field_type[$field]) && ($this->field_type[$field] == 'password' or $this->field_type[$field] ==
                        'hidden'))
                        continue;
                    $out .= $this->open_tag($item, '', $this->_cell_attrib($field, $value, $this->order_column, $row, false, $row_color, $row_class));
                    $out .= $this->_render_list_item($field, $value, $row['primary_key'], $row);
                    $out .= $this->close_tag($item);
                }
                if (($this->is_edit || $this->is_remove || $this->is_view || $this->buttons || $this->is_duplicate || $this->
                    grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'right')
                {
                    $out .= $this->open_tag($item, 'xcrud-actions' . (Xcrud_config::$fixed_action_buttons ? ' xcrud-fix' : ''), $this->
                        _cell_attrib(false, false, false, $row, false, $row_color, $row_class));
                    $out .= $this->_render_list_buttons($row);
                    $out .= $this->close_tag($item);
                }
                $out .= $this->close_tag($row_tag);
                $i = 1 - $i;
            }
        }
        else
        {
            $j = count($this->columns); // colspan
            if (($this->is_edit || $this->is_remove || $this->is_view || $this->buttons || $this->is_duplicate || $this->
                grid_restrictions) && $this->task != 'print' && ($this->buttons_position == 'right' || $this->buttons_position == 'left'))
            {
                ++$j;
            }
            if ($this->is_numbers)
            {
                ++$j;
            }
            $out .= $this->open_tag($row_tag, 'xcrud-row') . $this->open_tag($item, '', array('colspan' => $j)) . $this->lang('table_empty') .
                $this->close_tag($item) . $this->close_tag($row_tag);
        }
        return $out;
    }
    protected function render_grid_footer($row = array('tag' => 'tr'), $item = array('tag' => 'td'))
    {
        $out = '';
        if ($this->sum && $this->result_list)
        {
            $out .= $this->open_tag($row, 'xcrud-tf');
            if ($this->is_numbers)
            {
                $out .= $this->open_tag($item, 'xcrud-num') . '&Sigma;' . $this->close_tag($item);
            }
            if (($this->is_edit || $this->is_remove || $this->buttons || $this->is_view || $this->is_duplicate || $this->
                grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'left')
            {
                $out .= $this->open_tag($item, 'xcrud-actions') . '&nbsp;' . $this->close_tag($item);
            }
            foreach ($this->columns as $field => $fitem)
            {
                if (isset($this->field_type[$field]) && ($this->field_type[$field] == 'password' or $this->field_type[$field] ==
                    'hidden'))
                    continue;
                $out .= $this->open_tag($item, isset($this->sum[$field]) ? $this->sum[$field]['class'] : '', $this->_cell_attrib($field,
                    isset($this->sum[$field]) ? $this->sum[$field] : null, $this->order_column, $this->sum, true));
                $out .= $this->render_sum_item($field);
                $out .= $this->close_tag($item);
            }
            if (($this->is_edit || $this->is_remove || $this->buttons || $this->is_view || $this->is_duplicate || $this->
                grid_restrictions) && $this->task != 'print' && $this->buttons_position == 'right')
            {
                $out .= $this->open_tag($item, 'xcrud-actions') . '&nbsp;' . $this->close_tag($item);
            }
            $out .= $this->close_tag($row);
        }
        return $out;
    }

    protected function render_limitlist($buttons = false)
    {
        if ($this->is_limitlist)
        {
            return $this->get_limit_list($this->limit, $buttons);
        }
        return '';
    }
    protected function render_pagination($numbers = 10, $offsets = 2)
    {
        if ($this->is_pagination)
        {
            return $this->_pagination($this->result_total, $this->start, $this->limit, $numbers, $offsets);
        }
        return '';
    }
    protected function render_benchmark($tag = array('tag' => 'span'))
    {
        if ($this->benchmark)
        {
            return $this->open_tag($tag, 'xcrud-benchmark') . $this->benchmark_end() . $this->close_tag($tag);
        }
        return '';
    }
    protected function render_control_fields()
    {
        $out = '';
        $tag = array(
            'tag' => 'input',
            'type' => 'hidden',
            'class' => 'xcrud-data');
        $out .= $this->single_tag($tag, '', array('name' => 'key', 'value' => $this->key));
        $out .= $this->single_tag($tag, '', array('name' => 'orderby', 'value' => $this->order_column));
        $out .= $this->single_tag($tag, '', array('name' => 'order', 'value' => $this->order_direct));
        $out .= $this->single_tag($tag, '', array('name' => 'start', 'value' => $this->start));
        $out .= $this->single_tag($tag, '', array('name' => 'limit', 'value' => ($this->limit ? $this->limit : Xcrud_config::$limit)));
        $out .= $this->single_tag($tag, '', array('name' => 'instance', 'value' => $this->instance_name));
        $out .= $this->single_tag($tag, '', array('name' => 'task', 'value' => $this->task));
        if (Xcrud_config::$dynamic_session)
        {
            $out .= $this->single_tag($tag, '', array('name' => 'sess_name', 'value' => session_name()));
        }
        if ($this->primary_val)
        {
            $out .= $this->single_tag($tag, '', array('name' => 'primary', 'value' => $this->primary_val));
        }

        $out .= $this->render_message();
        $out .= implode('', $this->hidden_fields_output);

        return $out;
    }
    protected function render_message()
    {
        $out = '';
        if ($this->message)
        {
            $tag = array(
                'tag' => 'input',
                'type' => 'hidden',
                'class' => 'xcrud-callback-message',
                'name' => $this->message['type'],
                'value' => $this->message['text']);
            $out .= $this->single_tag($tag);
        }
        return $out;
    }
    /** renders action button for details view */
    protected function render_button($name = '', $task = '', $after = '', $class = '', $icon = '', $mode = '', $primary = '')
    {
        $out = '';
        if (isset($this->{'is_' . $after}) && !$this->{'is_' . $after})
        {
            return $out;
        }
        if (isset($this->{'is_' . $task}) && !$this->{'is_' . $task})
        {
            return $out;
        }
        if (!isset($this->hide_button[$name]))
        {
            if ($mode)
            {
                $mode = $this->parse_comma_separated($mode);
                if (!in_array($this->task, $mode))
                {
                    return $out;
                }
            }
            $tag = array(
                'tag' => 'a',
                'href' => 'javascript:;',
                'data-task' => $task);
            if ($after)
            {
                $tag['data-after'] = $after;
            }
            if ($class)
            {
                $tag['class'] = $class;
            }
            if ($primary)
            {
                $tag['data-primary'] = $primary;
            }
            elseif ($this->primary_val)
            {
                $tag['data-primary'] = $this->primary_val;
            }
            $out .= $this->open_tag($tag, 'xcrud-action');
            if ($icon && !$this->is_rtl)
            {
                $out .= $this->open_tag(array('tag' => 'i', 'class' => $icon)) . $this->close_tag('i') . ' ';
            }
            $out .= $this->lang($name);
            if ($icon && $this->is_rtl)
            {
                $out .= ' ' . $this->open_tag(array('tag' => 'i', 'class' => $icon)) . $this->close_tag('i');
            }
            $out .= $this->close_tag($tag);
        }
        return $out;
    }
    protected function add_button($class = '', $icon = '')
    {
        if ($this->is_create && !isset($this->hide_button['add']) && !$this->table_ro)
        {
            return $this->render_button('add', 'create', '', $class, $icon);
        }
    }
    protected function csv_button($class = '', $icon = '')
    {
        if ($this->is_csv && !isset($this->hide_button['csv']))
        {
            return $this->render_button('export_csv', 'csv', '', $class . ' xcrud-in-new-window', $icon);
        }
    }
    protected function print_button($class = '', $icon = '')
    {
        if ($this->is_print && !isset($this->hide_button['print']))
        {
            return $this->render_button('print', 'print', '', $class . ' xcrud-in-new-window', $icon);
        }
    }

    protected function get_image_folder($field)
    {
        if (isset($this->upload_folder[$field]))
            return $this->upload_folder[$field];
        $settings = $this->upload_config[$field];
        if (isset($settings['path']))
        {
            $path = $this->check_folder($settings['path'], 'get_image_folder');
        }
        else
        {
            $path = $this->check_folder(Xcrud_config::$upload_folder_def, 'get_image_folder');
        }
        $this->upload_folder[$field] = $path;
        return $path;
    }
    protected function check_file_folders($field)
    {
        $settings = $this->upload_config[$field];
        $path = $this->get_image_folder($field);
        if (!is_dir($path))
        {
            $this->create_file_folders($path);
        }
        if (isset($settings['thumbs']) && is_array($settings['thumbs']))
        {
            foreach ($settings['thumbs'] as $thumb)
            {
                if (isset($thumb['folder']) && !is_dir($path . '/' . trim($thumb['folder'], '/')))
                {
                    $this->create_file_folders($path . '/' . trim($thumb['folder'], '/'));
                }
            }
        }
    }
    protected function create_file_folders($path)
    {
        $path_array = explode('/', $path);
        array_pop($path_array);
        if (is_dir(implode('/', $path_array)))
        {
            if (!mkdir($path))
                self::error('cannot create directory ' . $path);
        }
        else
        {
            self::error('File path is incorrect!');
        }
    }
    protected function get_thumb_path($imgname, $field, $thumb_array)
    {
        $path = $this->get_image_folder($field);
        if (isset($thumb_array['folder']) && !empty($thumb_array['folder']))
        {
            $path .= '/' . trim($thumb_array['folder'], '/');
        }
        $marker = isset($thumb_array['marker']) ? $thumb_array['marker'] : '';
        return $path . '/' . $this->_thumb_name($imgname, $marker);
    }
    protected function safe_file_name($file, $field)
    {
        $ext = strtolower(strrchr($file['name'], '.'));
        if (isset($this->upload_config[$field]['not_rename']) && $this->upload_config[$field]['not_rename'] == true)
            $filename = $this->_clean_file_name($file['name']);
        else
            $filename = base_convert(str_replace(' ', '', microtime()) . rand(), 10, 36) . $ext;
        return $filename;
    }
    protected function get_ext($filename)
    {
        return strtolower(strrchr($filename, '.') + 1);
    }
    protected function save_file_to_tmp($file, $filename, $field)
    {
        $filename = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $file_path);

        if ($this->after_upload)
        {
            $path = $this->check_file($this->after_upload['path'], 'save_file_to_tmp');
            include_once ($path);
            $callable = $this->after_upload['callable'];
            if (is_callable($callable))
            {
                call_user_func_array($callable, array(
                    $field,
                    $filename,
                    $file_path,
                    $this->upload_config[$field],
                    $this));
            }
        }

        return $filename;
    }
    protected function save_file($file, &$filename, $field)
    {
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $file_path);
        if ($this->after_upload)
        {
            $path = $this->check_file($this->after_upload['path'], 'save_file');
            include_once ($path);
            $callable = $this->after_upload['callable'];
            if (is_callable($callable))
            {
                call_user_func_array($callable, array(
                    $field,
                    &$filename,
                    $file_path,
                    $this->upload_config[$field],
                    $this));
            }
        }
        return $file_path;
    }
    protected function get_filename_noconfict($filename, $field)
    {
        $file_path = $this->get_image_folder($field) . '/' . $filename;
        if (is_file($file_path))
        {
            $filename = substr_replace($filename, '_' . base_convert(time() . rand(), 10, 36), strrpos($filename, '.'), 0);
        }
        return $filename;
    }
    protected function is_resize($field)
    {
        if (isset($this->upload_config[$field]['width']) or isset($this->upload_config[$field]['height'])
            /* or isset($this->upload_config[$field]['field'])*/ or (isset($this->upload_config[$field]['manual_crop']) && $this->
            upload_config[$field]['manual_crop'] == true) /* or (isset($this->upload_config[$field]['thumbs']) && count($this->
            upload_config[$field]['thumbs']))*/)
            return true;
        else
            return false;
    }
    protected function is_manual_crop($field)
    {
        if (isset($this->upload_config[$field]['manual_crop']) && $this->upload_config[$field]['manual_crop'] == true)
            return true;
        else
            return false;
    }
    protected function remove_tmp_image($filename, $field)
    {
        $tmp_filename = substr($filename, 0, strrpos($filename, '.')) . '.tmp';
        if (isset($this->upload_config[$field]['save_original']) && $this->upload_config[$field]['save_original'] == true)
        {
            if (isset($this->upload_config[$field]['original_marker']) && !empty($this->upload_config[$field]['original_marker']))
            {
                $orig_filename = $this->_thumb_name($filename, $this->upload_config[$field]['original_marker']);
            }
            else
            {
                $orig_filename = $this->_thumb_name($filename, '_orig');
            }
            rename($this->get_image_folder($field) . '/' . $tmp_filename, $this->get_image_folder($field) . '/' . $orig_filename);
        }
        else
        {
            $path = $this->get_image_folder($field);
            if (is_file($path . '/' . $tmp_filename))
                unlink($path . '/' . $tmp_filename);
        }

        if ($this->after_resize)
        {
            $path = $this->check_file($this->after_resize['path'], 'after_resize');
            include_once ($path);
            $callable = $this->after_resize['callable'];
            if (is_callable($callable))
            {
                call_user_func_array($callable, array(
                    $field,
                    $filename,
                    $this->get_image_folder($field) . '/' . $filename,
                    $this->upload_config[$field],
                    $this));
            }
        }
    }
    protected function remove_file($filename, $field)
    {
        $settings = $this->upload_config[$field];
        $path = $this->get_image_folder($field);
        if (is_file($path . '/' . $filename))
            unlink($path . '/' . $filename);
        if (isset($settings['thumbs']) && is_array($settings['thumbs']))
        {
            foreach ($settings['thumbs'] as $thumb)
            {
                $thumb_file = $this->get_thumb_path($filename, $field, $thumb);
                if (is_file($thumb_file))
                    unlink($thumb_file);
            }
        }
        if (isset($this->upload_config[$field]['save_original']) && $this->upload_config[$field]['save_original'] == true)
        {
            if (isset($this->upload_config[$field]['original_marker']) && !empty($this->upload_config[$field]['original_marker']))
            {
                $orig_filename = $this->_thumb_name($filename, $this->upload_config[$field]['original_marker']);
            }
            else
            {
                $orig_filename = $this->_thumb_name($filename, '_orig');
            }
            if (is_file($path . '/' . $orig_filename))
                unlink($path . '/' . $orig_filename);
        }
    }


    /** date ranges in unix timestamp */
    protected function get_range($name)
    {
        $range = array();
        $time = time() /* + 3600 * Xcrud_config::$local_time_correction*/;
        $week_day = date('w', $time) /* + Xcrud_config::$date_first_day*/;
        switch ($name)
        {
            default:
            case 'today':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), date('j', $time), date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time));
                break;
            case 'next_year':
                $range['from'] = gmmktime(0, 0, 0, 1, 1, date('Y', $time) + 1);
                $range['to'] = gmmktime(23, 59, 59, 12, 31, date('Y', $time) + 1);
                break;
            case 'next_month':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time) + 1, 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time) + 2, -1, date('Y', $time));
                break;
            case 'this_week_today':
                if ($week_day >= Xcrud_config::$date_first_day)
                {
                    $offset1 = $week_day - Xcrud_config::$date_first_day;
                }
                else
                {
                    $offset1 = 7 - (Xcrud_config::$date_first_day - $week_day);
                }
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), date('j', $time) - $offset1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time));
                break;
            case 'this_week_full':
                if ($week_day >= Xcrud_config::$date_first_day)
                {
                    $offset1 = $week_day - Xcrud_config::$date_first_day;
                }
                else
                {
                    $offset1 = 7 - (Xcrud_config::$date_first_day - $week_day);
                }
                $offset2 = 6 - $week_day + Xcrud_config::$date_first_day;
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), date('j', $time) - $offset1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) + $offset2, date('Y', $time));
                break;
            case 'last_week':
                if ($week_day >= Xcrud_config::$date_first_day)
                {
                    $offset1 = $week_day - Xcrud_config::$date_first_day;
                }
                else
                {
                    $offset1 = 7 - (Xcrud_config::$date_first_day - $week_day);
                }
                $offset2 = 6 - $week_day + Xcrud_config::$date_first_day;
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), date('j', $time) - $offset1 - 7, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) + $offset2 - 7, date('Y', $time));
                break;
            case 'last_2weeks':
                if ($week_day >= Xcrud_config::$date_first_day)
                {
                    $offset1 = $week_day - Xcrud_config::$date_first_day;
                }
                else
                {
                    $offset1 = 7 - (Xcrud_config::$date_first_day - $week_day);
                }
                $offset2 = 6 - $week_day + Xcrud_config::$date_first_day;
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), date('j', $time) - $offset1 - 14, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) + $offset2 - 14, date('Y', $time));
                break;
            case 'this_month':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time), 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time));
                break;
            case 'last_month':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time) - 1, 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) - 1, date('Y', $time));
                break;
            case 'last_3months':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time) - 3, 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) - 1, date('Y', $time));
                break;
            case 'last_6months':
                $range['from'] = gmmktime(0, 0, 0, date('n', $time) - 6, 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time) - 1, date('Y', $time));
                break;
            case 'this_year':
                $range['from'] = gmmktime(0, 0, 0, 1, 1, date('Y', $time));
                $range['to'] = gmmktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time));
                break;
            case 'last_year':
                $range['from'] = gmmktime(0, 0, 0, 1, 1, date('Y', $time) - 1);
                $range['to'] = gmmktime(23, 59, 59, 12, 31, date('Y', $time) - 1);
                break;
        }
        return $range;
    }

    protected function unix2date($time, $utc = false)
    {
        if ($time)
            return $utc ? gmdate($this->date_format['php_d'], $time) : date($this->date_format['php_d'], $time);
        else
            return '';
    }
    protected function unix2datetime($time, $utc = false)
    {
        if ($time)
            return $utc ? gmdate($this->date_format['php_d'] . ' ' . $this->date_format['php_t'], $time) : date(Xcrud_config::$php_date_format .
                ' ' . $this->date_format['php_t'], $time);
        else
            return '';
    }
    protected function unix2time($time, $utc = false)
    {
        if ($time)
            return $utc ? gmdate($this->date_format['php_t'], $time) : date($this->date_format['php_t'], $time);
        else
            return '';
    }
    protected function mysql2date($date)
    {
        if ($date && $date != '0000-00-00')
        {
            $d = explode('-', $date);
            $date = $this->unix2date(mktime((int)date('G'), (int)date('i'), (int)date('s'), (int)$d[1], (int)$d[2], (int)$d[0]));
            return $date;
        }
        return '';
    }
    protected function mysql2datetime($date)
    {
        if ($date && $date != '0000-00-00 00:00:00')
        {
            if (!preg_match('/^\-{0,1}[0-9]+$/u', $date))
            {
                $date = strtotime($date);
            }
            $date = $this->unix2datetime((int)$date);
            return $date;
        }
        return '';
    }
    protected function mysql2time($date)
    {
        if ($date)
        {
            if (strpos($date, ' ') !== false)
            {
                list($tmp, $date) = explode(' ', $date, 2);
            }
            $d = explode(':', $date);
            $date = $this->unix2time(mktime((int)$d[0], (int)$d[1], (int)$d[2]));
            return $date;
        }
        return '';
    }
    protected function render_table_name($mode = 'list', $tag = 'h2', $to_show = false)
    {
        $out = '';
        if ($this->is_title)
        {
            $attr = array();
            if ($to_show && !$this->start_minimized)
                $attr['style'] = 'display:none;';
            if ($to_show)
                $attr['class'] = 'xcrud-main-tab';
            $out .= $this->open_tag($tag, '', $attr);
            switch ($mode)
            {
                case 'create':
                    $out .= $this->is_rtl ? '<small>' . $this->lang('add') . ' - </small>' . $this->table_name : $this->table_name .
                        '<small> - ' . $this->lang('add') . '</small>';
                    break;
                case 'edit':
                    $out .= $this->is_rtl ? '<small>' . $this->lang('edit') . ' - </small>' . $this->table_name : $this->table_name .
                        '<small> - ' . $this->lang('edit') . '</small>';
                    break;
                case 'view':
                    $out .= $this->is_rtl ? '<small>' . $this->lang('view') . ' - </small>' . $this->table_name : $this->table_name .
                        '<small> - ' . $this->lang('view') . '</small>';
                    break;
                default:
                    $out .= $this->is_rtl ? '<small>' . $this->get_table_tooltip() . '</small>' . $this->table_name : $this->table_name .
                        '<small> ' . $this->get_table_tooltip() . '</small>';
                    break;
            }
            if (Xcrud_config::$can_minimize)
            {
                if ($to_show)
                    $out .= '<span class="xcrud-toggle-show xcrud-toggle-down"><i class="' . $this->theme_config('slide_down_icon') .
                        '"></i></span>';
                else
                    $out .= '<span class="xcrud-toggle-show xcrud-toggle-up"><i class="' . $this->theme_config('slide_up_icon') .
                        '"></i></span>';
            }
            $out .= $this->close_tag($tag);
        }
        return $out;
    }
    protected function get_id()
    {
        return 'id="xc_' . base_convert(time() + rand(), 10, 36) . '"';
    }


    public function decrypt($text, $iv) {
        list($encrypted_data, $iv) = explode('::', base64_decode($text), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }


     public function encrypt($payload) {
         if (!Xcrud_config::$alt_encription_key)
            {
                self::error('Please, set <strong>$alt_encription_key</strong> parameter in configuration file');
            }
      $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
      $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $alt_encription_key, 0, $iv);
      return base64_encode($encrypted . '::' . $iv);
    }


    protected function is_edit(&$row)
    {
        if (!isset($this->grid_restrictions['edit']))
        {
            return $this->is_edit;
        }
        else
        {
            $fdata = $this->_parse_field_names($this->grid_restrictions['edit']['field']);
            $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            return $this->_compare($row[$fname], $this->grid_restrictions['edit']['operator'], $this->replace_text_variables($this->
                grid_restrictions['edit']['value'], $row)) ? $this->is_edit : !$this->is_edit;
        }
    }
    protected function is_remove(&$row)
    {
        if (!isset($this->grid_restrictions['remove']))
        {
            return $this->is_remove;
        }
        else
        {
            $fdata = $this->_parse_field_names($this->grid_restrictions['remove']['field']);
            $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            return $this->_compare($row[$fname], $this->grid_restrictions['remove']['operator'], $this->replace_text_variables($this->
                grid_restrictions['remove']['value'], $row)) ? $this->is_remove : !$this->is_remove;
        }
    }
    protected function is_duplicate(&$row)
    {
        if (!isset($this->grid_restrictions['duplicate']))
        {
            return $this->is_duplicate;
        }
        else
        {
            $fdata = $this->_parse_field_names($this->grid_restrictions['duplicate']['field']);
            $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            return $this->_compare($row[$fname], $this->grid_restrictions['duplicate']['operator'], $this->replace_text_variables($this->
                grid_restrictions['duplicate']['value'], $row)) ? $this->is_duplicate : !$this->is_duplicate;
        }
    }
    protected function is_view(&$row)
    {
        if (!isset($this->grid_restrictions['view']))
        {
            return $this->is_view;
        }
        else
        {
            $fdata = $this->_parse_field_names($this->grid_restrictions['view']['field']);
            $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            return $this->_compare($row[$fname], $this->grid_restrictions['view']['operator'], $this->replace_text_variables($this->
                grid_restrictions['view']['value'], $row)) ? $this->is_view : !$this->is_view;
        }
    }

    protected function is_button($name, &$row)
    {
        if (isset($this->grid_restrictions[$name]))
        {
            $fdata = $this->_parse_field_names($this->grid_restrictions[$name]['field']);
            $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
            return $this->_compare($row[$fname], $this->grid_restrictions[$name]['operator'], $this->replace_text_variables($this->
                grid_restrictions[$name]['value'], $row)) ? true : false;
        }
        else
        {
            return true;
        }
    }
    protected function _call_action()
    {
        $name = $this->_post('action');
        if (isset($this->action[$name]))
        {
            $path = $this->check_file($this->action[$name]['path'], 'call_action');
            include_once ($path);
            if (is_callable($this->action[$name]['callable']))
            {
                call_user_func_array($this->action[$name]['callable'], array($this));
            }
        }
        $this->task = $this->after;
        $this->after = null;
        return $this->_run_task();
    }


    public static function import_session($data)
    {
        $_SESSION['lists']['xcrud_session'] = $data;
    }
    public static function export_session()
    {
        return $_SESSION['lists']['xcrud_session'];
    }


    public function fieldname_encode($name = '')
    {
        return str_replace(array(
            '=',
            '/',
            '+'), array(
            '-',
            '_',
            ':'), base64_encode($name));
    }
    public function fieldname_decode($name = '')
    {
        return str_replace('`', '', base64_decode(str_replace(array(
            '-',
            '_',
            ':'), array(
            '=',
            '/',
            '+'), $name)));
    }

    protected function parse_mode($mode)
    {
        $modes = $this->parse_comma_separated($mode);
        if ($modes)
        {
            return (array_combine($modes, array_fill(0, count($modes), 1)));
        }
        else
        {
            return array(
                'list' => 1,
                'create' => 1,
                'edit' => 1,
                'view' => 1);
        }
    }

    protected function condition_backup($method, $field = null)
    {
        if (!isset($this->condition_backup[$method]))
        {
            if (property_exists($this, $method))
            {
                $this->condition_backup[$method] = $this->{$method};
            }
            else
            {
                $this->condition_backup[$method] = false;
            }
        }
    }
    protected function condition_restore()
    {
        if ($this->condition_backup)
        {
            foreach ($this->condition_backup as $bak_key => $back_val)
            {
                $this->{$bak_key} = $back_val;
            }
            $this->condition_backup = array();
        }
    }

    public function load_core_class($name)
    {
        $path = XCRUD_PATH . '/core/' . $name . '.php';
        if (isset(self::$classes[$name]))
        {
            return self::$classes[$name];
        }
        if (is_file($path))
        {
            require_once ($path);
            $class = 'Xcrud_' . $name;
            if (class_exists($class))
            {
                self::$classes[$name] = new $class;
                return self::$classes[$name];
            }
            else
            {
                self::error('Class "' . $class . '" not exist!');
            }
        }
        else
        {
            self::error('File "' . $name . '.php" not exist!');
        }
    }
    protected function cast_number_format($number, $field, $edit = false)
    {
        $out = '';
        $loc = localeconv();
        $loc_point = $loc['decimal_point'];
        $number = preg_replace('/^(.*)[\.\,' . preg_quote($this->field_attr[$field]['point'], '/') . ']+([^\.\,' . preg_quote($this->
            field_attr[$field]['point'], '/') . ']*)$/ui', '$1' . $loc_point . '$2', $number);

        if ($edit)
        {
            $point = ($this->field_attr[$field]['point'] == '.' || $this->field_attr[$field]['point'] == ',') ? $this->field_attr[$field]['point'] :
                $loc_point;
            $out .= number_format($number ? $number : 0, $this->field_attr[$field]['decimals'], $point, '');
        }
        else
        {
            $out .= $this->html_safe($this->field_attr[$field]['prefix']);
            $out .= number_format($number ? $number : 0, $this->field_attr[$field]['decimals'], $this->field_attr[$field]['point'],
                $this->html_safe($this->field_attr[$field]['separator']));
            $out .= $this->html_safe($this->field_attr[$field]['suffix']);
        }
        return $out;
    }

}

class Xcrud_postdata
{
    private $xcrud = null;
    private $postdata = array();
    public function __construct($postdata, $xcrud)
    {
        $this->xcrud = $xcrud;
        $this->postdata = $postdata;
        unset($postdata);
    }
    public function set($name, $value)
    {
        $fdata = $this->xcrud->_parse_field_names($name, 'Xcrud_postdata');
        foreach ($fdata as $key => $fitem)
        {
            $this->postdata[$key] = $value;
        }
        $this->xcrud->unlock_field($name);
        return $this;
    }
    public function del($name)
    {
        $fdata = $this->xcrud->_parse_field_names($name, 'Xcrud_postdata');
        foreach ($fdata as $key => $fitem)
        {
            unset($this->postdata[$key]);
        }
        return $this;
    }
    public function get($name)
    {
        $fdata = $this->xcrud->_parse_field_names($name, 'Xcrud_postdata');
        $fname = key($fdata) /*$fdata[0]['table'] . '.' . $fdata[0]['field']*/;
        $value = (isset($this->postdata[$fname]) ? $this->postdata[$fname] : false);
        return /*new Xcrud_postdata_item*/ ($value);
    }
    public function to_array()
    {
        return $this->postdata;
    }

}
