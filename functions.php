<?php
function publish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'1\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}
function unpublish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'0\' WHERE id = ' . (int)$xcrud->get('primary');
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
    $xcrud->set_exception($random_field, 'This is a test error', 'error');
}

function test_column_callback($value, $fieldname, $primary, $row, $xcrud)
{
    return $value . ' - nice!';
}

function after_upload_example($field, $file_name, $file_path, $params, $xcrud)
{
    $ext = trim(strtolower(strrchr($file_name, '.')), '.');
    if ($ext != 'pdf' && $field == 'uploads.simple_upload')
    {
        unlink($file_path);
        $xcrud->set_exception('simple_upload', 'This is not PDF', 'error');
    }
}

function movetop($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != 0)
            {
                array_splice($result, $key - 1, 0, array($item));
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}
function movebottom($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != $count - 1)
            {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, array($item));
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}

function show_description($value, $fieldname, $primary_key, $row, $xcrud)
{
    $result = '';
    if ($value == '1')
    {
        $result = '<i class="fa fa-check" />' . 'OK';
    }
    elseif ($value == '2')
    {
        $result = '<i class="fa fa-circle-o" />' . 'Pending';
    }
    return $result;
}

function custom_field($value, $fieldname, $primary_key, $row, $xcrud)
{
    return '<input type="text" readonly class="xcrud-input" name="' . $xcrud->fieldname_encode($fieldname) . '" value="' . $value .
        '" />';
}
function unset_val($postdata)
{
    $postdata->del('Paid');
}

function format_phone($new_phone)
{
    $new_phone = preg_replace("/[^0-9]/", "", $new_phone);

    if (strlen($new_phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $new_phone);
    elseif (strlen($new_phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $new_phone);
    else
        return $new_phone;
}

function before_list_example($list, $xcrud)
{
    var_dump($list);
}

function after_update_test($pd, $pm, $xc)
{
    $xc->search = 0;
}

function after_upload_test($field, &$filename, $file_path, $upload_config, $this)
{
    $filename = 'bla-bla-bla';
}

function esempio_insert($postdata, $xcrud){
$db = Xcrud_db::get_instance();
$recupero_id_insert=$db->insert_id();//ottengo ID
funzionesuid($recupero_id_insert);
}


function esempio_update($postdata,$primary,$xcrud){
$db = Xcrud_db::get_instance();
$prendovariabile=(int)$xcrud->get('primary');
funzionesuid($prendovariabile);
}

function esempio_delete($primary, $xcrud){
$db = Xcrud_db::get_instance();
$prendoidprimario=$db->escape($primary);
funzionesuid($prendoidprimario);
}

function crediti_insert($postdata, $xcrud){
$db = Xcrud_db::get_instance();
$recupero_id_insert=$db->insert_id();//ottengo ID
aggiornamento_crediti();
}


function crediti_update($postdata,$primary,$xcrud){
$db = Xcrud_db::get_instance();
$prendovariabile=(int)$xcrud->get('primary');
aggiornamento_crediti();
}

function crediti_remove($primary, $xcrud){
$db = Xcrud_db::get_instance();
$prendoidprimario=$db->escape($primary);
aggiornamento_crediti();
}

function partite_insert($postdata, $xcrud){
$db = Xcrud_db::get_instance();
$recupero_id_insert=$db->insert_id();//ottengo ID
aggiornamento_partite($recupero_id_insert);
}


function partite_update($postdata,$primary,$xcrud){
$db = Xcrud_db::get_instance();
$prendovariabile=(int)$xcrud->get('primary');
aggiornamento_partite($prendovariabile);
}

function partite_remove($primary, $xcrud){
$db = Xcrud_db::get_instance();
$prendoidprimario=$db->escape($primary);
aggiornamento_partite($prendoidprimario);
}

function aggiornamento_crediti(){
	$db = Xcrud_db::get_instance();
//LAVORO CON ID PARTITA PER DOMANDE RISPOSTE

$db->query("SELECT * FROM game_costo_crediti WHERE disponibilita='si'");
$partita_raw=$db->result();


$contenutosingoloc=json_encode($partita_raw);	
$myfilep = fopen("/var/www/vhosts/everestquiz.it/httpdocs/impostazioni/crediti.json", "w") or die("Unable to open file!");
fwrite($myfilep, $contenutosingoloc);
fclose($myfilep);
}
//PARTITE: indipendentemente da ID RICREO JSON
function aggiornamento_partite($idpartita){
$db = Xcrud_db::get_instance();
//LAVORO CON ID PARTITA PER DOMANDE RISPOSTE
$mioidpartita=$idpartita;
$db->query("SELECT game_partite.id,game_partite.datapartita,game_partite.titolo,game_partite.streaming,game_partite.status FROM game_partite WHERE id='$mioidpartita'");
$singolapartita=$db->row();

$db->query("SELECT domande1.id,domande1.domanda,domande1.risposta_1,domande1.risposta_2,domande1.risposta_valida,domande1.spiegazione FROM game_partite INNER JOIN game_domande as domande1 ON game_partite.idomanda1=domande1.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita1=$db->row();

$db->query("SELECT domande2.id,domande2.domanda,domande2.risposta_1,domande2.risposta_2,domande2.risposta_3,domande2.risposta_valida,domande2.spiegazione FROM game_partite INNER JOIN game_domande as domande2 ON game_partite.idomanda2=domande2.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita2=$db->row();

$db->query("SELECT domande3.id,domande3.domanda,domande3.risposta_1,domande3.risposta_2,domande3.risposta_3,domande3.risposta_4,domande3.risposta_valida,domande3.spiegazione FROM game_partite INNER JOIN game_domande as domande3 ON game_partite.idomanda3=domande3.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita3=$db->row();

$db->query("SELECT domande4.id ,domande4.domanda,domande4.risposta_1,domande4.risposta_2,domande4.risposta_3,domande4.risposta_4,domande4.risposta_5,domande4.risposta_valida,domande4.spiegazione FROM game_partite INNER JOIN game_domande as domande4 ON game_partite.idomanda4=domande4.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita4=$db->row();

$db->query("SELECT domande5.id,domande5.domanda,domande5.risposta_1,domande5.risposta_2,domande5.risposta_3,domande5.risposta_4,domande5.risposta_5,domande5.risposta_6,domande5.risposta_valida,domande5.spiegazione FROM game_partite INNER JOIN game_domande as domande5 ON game_partite.idomanda5=domande5.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita5=$db->row();

$db->query("SELECT domande6.id,domande6.domanda,domande6.risposta_1,domande6.risposta_2,domande6.risposta_3,domande6.risposta_4,domande6.risposta_5,domande6.risposta_6,domande6.risposta_7,domande6.risposta_valida,domande6.spiegazione FROM game_partite INNER JOIN game_domande as domande6 ON game_partite.idomanda6=domande6.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita6=$db->row();

$db->query("SELECT domande7.id,domande7.domanda,domande7.risposta_1,domande7.risposta_2,domande7.risposta_3,domande7.risposta_4,domande7.risposta_5,domande7.risposta_6,domande7.risposta_7,domande7.risposta_8,domande7.risposta_valida,domande7.spiegazione FROM game_partite INNER JOIN game_domande as domande7 ON game_partite.idomanda7=domande7.id  WHERE game_partite.id='$mioidpartita'");
$singolapartita7=$db->row();


$contenutop = array(
'generale'=> $singolapartita,
'livello_1'=> $singolapartita1,
'livello_2'=> $singolapartita2,
'livello_3'=> $singolapartita3,
'livello_4'=> $singolapartita4,
'livello_5'=> $singolapartita5,
'livello_6'=> $singolapartita6,
'livello_7'=> $singolapartita7
);

$contenutosingolop=json_encode($contenutop);
$myfilep = fopen("/var/www/vhosts/everestquiz.it/httpdocs/domande/".$mioidpartita.".json", "w") or die("Unable to open file!");
fwrite($myfilep, $contenutosingolop);
fclose($myfilep);


//da $singolapartita a $singolapartita1 a $singolapartita7



//$db->query("SELECT game_partite.id,game_partite.datapartita,game_partite.titolo,game_partite.status,domande1.domanda,domande1.risposta_1,domande1.risposta_2,domande1.risposta_3,domande1.risposta_4,domande1.risposta_5,domande1.risposta_6,domande1.risposta_7,domande1.risposta_8,domande1.risposta_valida,domande1.spiegazione,domande2.domanda,domande2.risposta_1,domande2.risposta_2,domande2.risposta_3,domande2.risposta_4,domande2.risposta_5,domande2.risposta_6,domande2.risposta_7,domande2.risposta_8,domande2.risposta_valida,domande2.spiegazione,domande3.domanda,domande3.risposta_1,domande3.risposta_2,domande3.risposta_3,domande3.risposta_4,domande3.risposta_5,domande3.risposta_6,domande3.risposta_7,domande3.risposta_8,domande3.risposta_valida,domande3.spiegazione,domande4.domanda,domande4.risposta_1,domande4.risposta_2,domande4.risposta_3,domande4.risposta_4,domande4.risposta_5,domande4.risposta_6,domande4.risposta_7,domande4.risposta_8,domande4.risposta_valida,domande4.spiegazione,domande5.domanda,domande5.risposta_1,domande5.risposta_2,domande5.risposta_3,domande5.risposta_4,domande5.risposta_5,domande5.risposta_6,domande5.risposta_7,domande5.risposta_8,domande5.risposta_valida,domande5.spiegazione,domande6.domanda,domande6.risposta_1,domande6.risposta_2,domande6.risposta_3,domande6.risposta_4,domande6.risposta_5,domande6.risposta_6,domande6.risposta_7,domande6.risposta_8,domande6.risposta_valida,domande6.spiegazione,domande7.domanda,domande7.risposta_1,domande7.risposta_2,domande7.risposta_3,domande7.risposta_4,domande7.risposta_5,domande7.risposta_6,domande7.risposta_7,domande7.risposta_8,domande7.risposta_valida,domande7.spiegazione FROM game_partite INNER JOIN game_domande as domande1 ON game_partite.idomanda1=domande1.id INNER JOIN game_domande as domande2 ON game_partite.idomanda2=domande2.id INNER JOIN game_domande as domande3 ON game_partite.idomanda3=domande3.id INNER JOIN game_domande as domande4 ON game_partite.idomanda4=domande4.id INNER JOIN game_domande as domande5 ON game_partite.idomanda5=domande5.id INNER JOIN game_domande as domande6 ON game_partite.idomanda6=domande6.id INNER JOIN game_domande as domande7 ON game_partite.idomanda7=domande7.id WHERE game_partite.id='$mioidpartita'");

//LAVORO SU TUTTE LE PARTITE PER ELENCO
$oggi=time();
$stanotte=strtotime('tomorrow');
$oggi=date('Y-m-d H:i:s', time());
$date = new DateTime($oggi);
$date->modify('+20 day');
$stanotte = $date->format('Y-m-d H:i:s');
//$stanotte=date('Y-m-d H:i:s', strtotime('tomorrow'));
//$stanotte= date('Y-m-d H:i:s',  strtotime('+20 days'));
$db->query("SELECT id,datapartita,DATE_FORMAT(datapartita, '%d/%m/%Y %H:%i') as datapartitait,titolo,streaming,attesalivello1,attesalivello2,attesalivello3,attesalivello4,attesalivello5,attesalivello6,attesalivello7,videopersonalizzato FROM game_partite WHERE status='attiva' and datapartita>='$oggi' and datapartita<='$stanotte' order by datapartita");

$risultati=$db->result();

if (empty($risultati)) {
$contenuto=array('error'=>'true','error_code'=>'800','error_description'=>'Nessuna partita da mostrare','partite'=>$risultati);
$contenuto=json_encode($contenuto);
} else {
$contenuto=array('error'=>'false','partite'=>$risultati);
$contenuto=json_encode($contenuto);
}
$myfile = fopen("/var/www/vhosts/everestquiz.it/httpdocs/partite/data.json", "w") or die("Unable to open file!");
fwrite($myfile, $contenuto);
fclose($myfile);
}


function solo_mp4($field_name, $file_name, $file_full_path, $upload_config, $xcrud){
$extensions = array(".mp4");//$extensions = array(".jpg", ".gif", ".pdf", ".docx", ".mp4");
$ext = strrchr($file_name,'.');
if (!in_array($ext,$extensions)) {
echo "<SCRIPT LANGUAGE='JavaScript'>
<!--
window.alert('Formato File ERRATO, ricaricarlo')
// -->
</SCRIPT>";
}
}