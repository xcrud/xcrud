<?php
/**
 * @package    xCrud Reload v1.0
 *
 * @copyright  (C) 2024 Open Source Matters, Inc. <https://www.xcrud.me>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
#[AllowDynamicProperties]
class Xcrud_db
{
    private static array $_instance = [];
    private \mysqli|bool|null $connect = null;
    public $result;
    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    private $dbencoding;
    private $magic_quotes;

    public static function get_instance($params = false)
    {
        if (is_array($params)) {
            [$dbuser, $dbpass, $dbname, $dbhost, $dbencoding] = $params;
            $instance_name = sha1(
                $dbuser . $dbpass . $dbname . $dbhost . $dbencoding
            );
        } else {
            $instance_name = "db_instance_default";
        }
        if (
            !isset(self::$_instance[$instance_name]) or
            null === self::$_instance[$instance_name]
        ) {
            if (!is_array($params)) {
                $dbuser = Xcrud_config::$dbuser;
                $dbpass = Xcrud_config::$dbpass;
                $dbname = Xcrud_config::$dbname;
                $dbhost = Xcrud_config::$dbhost;
                $dbencoding = Xcrud_config::$dbencoding;
            }
            self::$_instance[$instance_name] = new self(
                $dbuser,
                $dbpass,
                $dbname,
                $dbhost,
                $dbencoding
            );
        }
        return self::$_instance[$instance_name];
    }
    private function __construct(
        $dbuser,
        $dbpass,
        $dbname,
        $dbhost,
        $dbencoding
    ) {
        if (str_contains((string) $dbhost, ":")) {
            [$host, $port] = explode(":", (string) $dbhost, 2);
            preg_match('/^([0-9]*)([^0-9]*.*)$/', $port, $socks);
            $this->connect = mysqli_connect(
                $host,
                $dbuser,
                $dbpass,
                $dbname,
                $socks[1] ? $socks[1] : null,
                $socks[2] ? $socks[2] : null
            );
        } else {
            $this->connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        }
        if (!$this->connect) {
            $this->error("Connection error. Can not connect to database");
        }
        $this->connect->set_charset($dbencoding);
        if ($this->connect->error) {
            $this->error($this->connect->error);
        }
        if (Xcrud_config::$db_time_zone) {
            $this->connect->query(
                'SET time_zone = \'' . Xcrud_config::$db_time_zone . '\''
            );
        }
    }
    public function query($query = "")
    {
        $this->result = $this->connect->query($query, MYSQLI_USE_RESULT);
        //echo '<pre>' . $query . '</pre>';
        if ($this->connect->error) {
            $this->error($this->connect->error . "<pre>" . $query . "</pre>");
        }
        return $this->connect->affected_rows;
    }
    public function insert_id()
    {
        return $this->connect->insert_id;
    }
    public function result()
    {
        $out = [];
        if ($this->result) {
            while ($obj = $this->result->fetch_assoc()) {
                $out[] = $obj;
            }
            $this->result->free();
        }
        return $out;
    }
    public function row()
    {
        $obj = $this->result->fetch_assoc();
        $this->result->free();
        return $obj;
    }
    public function escape(
        $val,
        $not_qu = false,
        $type = false,
        $null = false,
        $bit = false
    ) {
        if ($type) {
            switch ($type) {
                case "bool":
                    if ($bit) {
                        return (int) $val ? 'b\'1\'' : 'b\'0\'';
                    }
                    return (int) $val ? 1 : ($null ? "NULL" : 0);
                    break;
                case "int":
                    $val = preg_replace("/[^0-9\-]/", "", (string) $val);
                    if ($val === "") {
                        if ($null) {
                            return "NULL";
                        } else {
                            $val = 0;
                        }
                    }
                    if ($bit) {
                        return 'b\'' . $val . '\'';
                    }
                    return $val;
                    break;
                case "float":
                    if ($val === "") {
                        if ($null) {
                            return "NULL";
                        } else {
                            $val = 0;
                        }
                    }
                    return '\'' .
                        $this->connect->real_escape_string($val) .
                        '\'';
                    break;
                default:
                    if (trim((string) $val) == "") {
                        if ($null) {
                            return "NULL";
                        } else {
                            return '\'\'';
                        }
                    } else {
                        if ($type == "point") {
                            $val = preg_replace(
                                "[^0-9\.\,\-]",
                                "",
                                (string) $val
                            );
                        }
                        //return '\'' . ($this->magic_quotes ? (string )$val : $this->connect->real_escape_string((string )$val)) . '\'';
                    }
                    break;
            }
        }
        if ($not_qu) {
            return  $this->connect->real_escape_string((string) $val));
        }
        return '\'' .$this->connect->real_escape_string((string) $val)) .'\'';
    }
    public function escape_like($val, $pattern = ["%", "%"])
    {
        if (is_int($val)) {
            return '\'' . $pattern[0] . (int) $val . $pattern[1] . '\'';
        }
        if ($val == "") {
            return '\'\'';
        } else {
            return '\'' . $pattern[0] . $this->connect->real_escape_string((string) $val)) . $pattern[1] . '\'';
        }
    }

    public  function error($text = 'Error!', $type = 'error'): never
{


    $backgroundColors = [
        'error' => '#FFF3F3',
        'success' => '#E8F5E9',
        'warning' => '#FFFDE7',
        'info' => '#E3F2FD',
        'dark' => '#ECEFF1',
        'notice' => '#E0F7FA',
    ];

    $textColors = [
        'error' => '#D32F2F',
        'success' => '#2E7D32',
        'warning' => '#F9A825',
        'info' => '#1565C0',
        'dark' => '#263238',
        'notice' => '#00ACC1',
    ];

    $backgroundColor = $backgroundColors[$type] ?? $backgroundColors['error'];
    $textColor = $textColors[$type] ?? $textColors['error'];

    if (php_sapi_name() === 'cli') {
        // Output per CLI
        exit(strtoupper($type) . ": " . $text . "\n");
    } else {
        // Output per Web
        exit('<div style="position:relative; padding:20px; margin:20px 0; color:' . $textColor . '; background-color:' . $backgroundColor . '; border:1px solid ' . $textColor . '; border-radius:8px; font-family:\'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; box-shadow:0 4px 10px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center; line-height:1.5;">' .
            '<span style="position:absolute; top:0; left:10px; font-size:10px; color:' . $textColor . '; text-transform:uppercase;">' . $type . '</span>' .
            htmlspecialchars($text) .
         '<span style="font-size:12px; color:#' . $textColor . '; opacity:0.6;"><strong style="color:#000">xCrudReload</strong> v <strong>1.0</strong> </span></div>');
    }
}
}
