<?php

class Xcrud_config
{
    // default connection
    public static $dbname = 'database'; // Your database name
    public static $dbuser = 'root'; // Your database username
    public static $dbpass = 'password'; // // Your database password
    public static $dbhost = 'localhost'; // Your database host, 'localhost' is default.
    
    
    // theme and language
    public static $theme = 'bootstrap'; // can be 'default', 'bootstrap', 'minimal' or your custom. Theme of xCRUD visual presentation. For using bootstrap you need to load it on your page.
    public static $language = 'it'; // sets default localization
    public static $is_rtl = false; // enables right-to-left (RTL) mode
    
    
    // database advanced
    public static $dbencoding = 'utf8'; // Your database encoding, default is 'utf8'. Do not change, if not sure.
    public static $db_time_zone = ''; // database time zone, if you want use system default - leave empty.
    public static $mbencoding = 'utf-8'; // Your mb_string encoding, default is 'utf-8'. Do not change, if not sure.
    public static $dbprefix = '';

    
    // session
    public static $sess_name = 'PHPSESSID'; // If your script is already using the session, specify the session name for it. By default, the name of the session in PHP equal 'PHPSESSID'.
    public static $sess_expire = 30; // Specifies the lifetime of the session, as well as the existence of a key safety (for example, the maximum edit-saving timeout).
    public static $dynamic_session = false; // this option is used for compatibility with with frameworks and cms that using dynamic session name.
    
    
    // alternative session (reqires memcache(d) and mcrypt)
    public static $alt_session = false; // use this only if you have troubles with native php sessions
    public static $alt_encription_key = 'super enc key'; // needs to protect alt. session data, required if alt. session is enabled
    public static $alt_lifetime = 30; // in minutes, maximum time between requests when instance's data can be valid
    public static $mc_host = 'localhost'; // Memcache(d) host
    public static $mc_port = 11211; // Memcache(d) port
    
    
    // scripts
    public static $load_bootstrap = false; // turn on, if you want to load bootstrap via xCRUD
    public static $load_googlemap = true; // loads google map api for 'POINT' type. Turn off, if your site already uses it.
    public static $load_jquery = true; // loads jQuery, turn it off if you already have jQuery on your page. jQuery version must be at least 1.7. If your jQuery loads in the bottom of page, you must activate $manual_load and use  Xcrud::load_css() & Xcrud::load_js() on your page.
    public static $load_jquery_ui = true; // jQueryUI, turn it on if you already have jQueryUI on your page (datepicker and slider widgets are mandatory).
    public static $load_jcrop = true; // disable, if your page already uses jCrop
    public static $jquery_no_conflict = true; // Includes jQuery.noConflict(). Use according to jQuery documentation.
    public static $manual_load = true; // Allows you to disable xcruds css and js output, but you can use Xcrud::load_css() & Xcrud::load_js() in your code manually.

    
    // editor
    public static $editor_url = 'editors/tinymce/tinymce.min.js'; // URL path to editor script, if you want to use the visual editor.
    //public static $editor_url = 'editors/ckeditor/ckeditor.js';
    public static $editor_init_url = ''; //  URL path to your custom initialization file for editor.
    public static $force_editor = false; // Forced initialization of editor, even if the path is not specified. Check this if you're already using editor on your page.
    public static $auto_editor_insertion = false; // inserts visual editor on textarea fields.
    
    
    // grid settings
    public static $show_primary_ai_field = false; // Show primary auto-increment field in create/edit view.
    public static $show_primary_ai_column = false; // Show primary auto-increment column in list view.
    public static $can_minimize = false; // allows 'minimize' arrow in grid
    public static $start_minimized = false; // Start all xCRUD instances minimized.
    public static $remove_confirm = true; // Show confirmation dialog on remove action.
    public static $column_cut = 50; // Sets the maximum number of characters in the column.
    public static $limit = 10; // default limit of rows per page
    public static $limit_list = array('25', '50', '100', 'all'); // default limits list
    public static $clickable_list_links = true; // make all links, emails clikable in list view
    public static $clickable_filenames = true; // makes filenames clikable in list view
    public static $fixed_action_buttons = true; // it allows to fix the action buttons on the right side of the table. Appears when you hover on row.
    public static $images_in_grid = true; // shows images in list view
    public static $images_in_grid_height = 55; // maximal height of thumbnails in list view
    public static $button_labels = false; // displays button labels in grid
    public static $strip_tags = true; // remove all tags from data in grid view. This is not affected to user patterns or other custom.
    public static $safe_output = false; // encodes special characters to html-entities in grid view
    
    
    // print
    public static $print_all_fields = false; // print all fields and rows of table or only visible.
    public static $print_full_texts = false; // print grid without cutting
    
    
    // csv export
    public static $csv_delimiter = ';'; // default delimiter in CSV file.
    public static $csv_enclosure = '"'; // default enclosure in CSV file.
    public static $csv_all_fields = true; // export all fields and rows of table or only visible.

    
    // editing
    public static $make_checkbox = true; // display TINYINT(1),BIT(1),BOOL(1),BOOLEAN(1) fields like checkboxes
    public static $lists_null_opt = true; // display null(empty) option in all dropdowns and multiselects
    public static $enum_as_radio = false; // shows ENUM field as radiobox, dropdown by default
    public static $set_as_checkboxes = false; // shows SET field as checkboxes, multiselect by default
    public static $upload_folder_def = '../uploads'; // Default uploads folder on your site, relative to xCRUD folder or absolute path required. Folder is must exist.
    public static $not_null_is_required = false; // makes not null fields required
    
    // features
    public static $enable_printout = true; // show print button
    public static $enable_search = true; // show searck block
    public static $enable_pagination = true; // show pagination
    public static $enable_csv_export = true; // show csv export button
    public static $enable_table_title = true; // show table title and toggle button
    public static $enable_numbers = true; // show row numbers in grid
    public static $enable_limitlist = true; // show row numbers in grid
    public static $enable_sorting = true; // alows to sort by column
	public static $enable_deleteall = false; // modifica abilita disabilita seleziona tutti usare la funzione unset_delete_all(); per non mostrare il select all
    public static $benchmark = false; // Displays information about the performance in the lower right corner.
    public static $nested_readonly_on_view = true; // turn of editing nested tables when viewing parent (can edit only when editing parent)
    public static $default_tab = false; // Sets name of tab for fields which not assigned with any tab. This tab will be created automatically. Tab will not be created when is FALSE.
    public static $nested_in_tab = true; // Nested will be displayed in tab if tabs are active
    
       
    // alert settings
    public static $email_from = 'mailer@example.com'; // email from address
    public static $email_from_name = 'xCRUD Data Management System'; // email from name
    public static $email_enable_html = true; // enables html in email letters

    
    // remote request options (call_page() methods)
    public static $use_browser_info = false; // allow to use your browser cookie, referer, user agent for http request to some file or url. BE CAREFUL: DON'T USE IT FOR REQUESTS TO EXTERNAL SITES!!!

    
    // date
    public static $date_first_day = 1; // 0 - Sunday, 1 - Monday etc. Uses in datepicker and search ranges
    public static $date_format = 'dd.mm.yy'; // jqueryui date format
    public static $time_format = 'HH:mm:ss'; // jqueryui time format
    public static $php_date_format = 'd.m.Y'; // php date format
    public static $php_time_format = 'H:i:s'; // php time format
    
    
    // search
    public static $search_all = true; // enables -all- option for search
    public static $available_date_ranges = array( // available date ranges, can be translated in language file
        'next_year',
        'next_month',
        'today',
        'this_week_today',
        'this_week_full',
        'last_week',
        'last_2weeks',
        'this_month',
        'last_month',
        'last_3months',
        'last_6months',
        'this_year',
        'last_year');
    public static $search_pattern = array('%','%'); // uses for LIKE operator in SQL request
    public static $search_opened = false; // make search always opened
    
    
    // map
    public static $default_point = '35.6894875,139.69170639999993';
    public static $default_text = 'your_position';
    public static $default_zoom = 8;
    public static $default_width = 500;
    public static $default_height = 300;
    public static $default_coord = true;
    public static $default_search = true;
    public static $default_search_text = 'search_here';
    
    
    // xcrud folder url
    public static $scripts_url = ''; // URL to the xCRUD folder, not real path, without a trailing slash, can be relative, e.g. 'some_folder/xcrud' or absolute, e.g. 'http://www.your_site.com/some_folder/xcrud'. If empty - will be detected automatically
    public static $urls2abs = true; // makes relative urls to absolute. Turn off if you have some troubles with relative urls.
    
    
    // system integration options. NO ANY TRAILING SLASHES!
    // urls (relative to $scripts_url or xcrud's folder, if $scripts_url is not defined)
    public static $plugins_uri = 'plugins'; // scripts and libraries
    public static $themes_uri = 'themes'; // css, images
    public static $lang_uri = 'languages'; // js files
    public static $ajax_uri = 'xcrud_ajax.php'; // main ajax file or url
    // paths (relative to xcrud's folder)
    public static $themes_path = 'themes'; // php and ini files
    public static $lang_path = 'languages'; // ini files
    // external session
    public static $external_session = false; // use only when you use integration with externall session
    // loading events
    public static $before_construct = false; // callable param, runs before instance creation
    public static $after_render = false; // callable param, runs after instance was rendered
    
    
    // system
    public static $demo_mode = false; // disables any changing data in database
    public static $performance_mode = false; // experimental, disables {field_tags} features
    public static $autoclean_timeout = 3; // in seconds. Do not change, if not sure. Xcrud clears old instances in session when you reload browser tab or open new tab with xcrud. In this case Xcrud can't work in two tabs in the same time. You can increase timeout on your risk.
    
    
    // anti XSS
    public static $auto_xss_filtering = false; // enable all xcrud's POST and GET data filtering
    public static $xss_disalowed_attibutes = array('on\w*', /*'style',*/ 'xmlns', 'formaction'); // Remove bad attributes such as style, onclick and xmlns
    public static $xss_naughty_html = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|input|isindex|layer|link|meta|object|plaintext|script|textarea|title|video|xml|xss'; // If a tag containing any of the words in the list below is found, the tag gets converted to entities.
    public static $xss_naughty_scripts = 'alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink'; // imilar to above, only instead of looking for tags it looks for PHP and JavaScript commands that are disallowed.  Rather than removing the code, it simply converts the parenthesis to entities rendering the code un-executable.
    

}
