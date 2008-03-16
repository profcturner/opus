<?php

/**
* This object manipulates artefacts held in the artefact repository.
* @package OPUS
*/
require_once("dto/DTO_Artefact.class.php");
/**
* This object manipulates artefacts held in the artefact repository.
* This was lifted from the PDSystem source code, at some stage a common copy will
* probably be maintained.
*
* @author Gordon Crawford <g.crawford@ulster.ac.uk>
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/
class Artefact extends DTO_Artefact
{
  var $user_id = 0;
  var $type = "";
  var $file_name = "";
  var $file_size = 0;
  var $file_type = "";
  var $description = "";
  var $group = "";
  var $hash = "";
  var $thumb = "";

  static $_field_defs = array(
    'type'=>array('type'=>'text', 'size'=>50, 'title'=>'Type', 'header'=>true),
    'file_name'=>array('type'=>'text', 'size'=>50, 'title'=>'File Name', 'header'=>true),
    'file_size'=>array('type'=>'text', 'size'=>50, 'title'=>'File Size', 'header'=>true),
    'file_type'=>array('type'=>'text', 'size'=>50, 'title'=>'File Type', 'header'=>true),
    'description'=>array('type'=>'textarea', 'rowsize'=>4, 'colsize'=>50, 'maxsize'=>300, 'title'=>'File Description', 'header'=>true),
    'group'=>array('type'=>'text', 'size'=>50, 'title'=>'Group', 'header'=>true),
    'hash'=>array('type'=>'text', 'size'=>50, 'title'=>'Hash', 'header'=>true)
    );

  function __construct() 
  {
    parent::__construct();
  }

  function get_field_defs()
  {
    return self::$_field_defs;
  }

  function load_by_id($id, $parse = False) 
  {
    $artefact = new Artefact;
    $artefact->id = $id;
    $artefact->_load_by_id($parse);
    return $artefact;
  }

  function load_by_hash($hash, $parse = False) 
  {
    $artefact = new Artefact;
    $artefact->hash = $hash;
    $artefact->_load_by_field('hash');
    return $artefact;
  }

  function insert($fields) 
  {
    require_once('model/User.class.php');

    $artefact = new Artefact;

    $user_id = $fields['user_id'];
    $hash = md5($user_id.time());
    $upload_dir = User::upload_path($user_id);

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0700, True);
    }

    if (User::not_over_quota($user_id))
    {
      if ($fields['file']['error'] == UPLOAD_ERR_OK)
      {
        move_uploaded_file($fields['file']['tmp_name'], $upload_dir.$hash);

        $artefact->user_id = $user_id;
        $artefact->type = $fields['type'];
        $artefact->file_name = $fields['file']['name'];
        $artefact->file_size = $fields['file']['size']; 
        $artefact->file_type = $fields['file']['type'];
        $artefact->description = $fields['description'];
        $artefact->group = $fields['group'];
        $artefact->hash = $hash;
        $artefact->data = $upload_dir.$hash;
        $artefact->thumb = $artefact->get_thumbnail();

        return $artefact->_insert();
      }
      else // error in file upload
      {
        return "upload_error";
      }
    }
    else // over quota!
    {
      return "over_quota";
    }
    
  }

  function insert_url($fields)
  {
    $artefact = new Artefact;

    $user_id = $fields['user_id'];
    
    $artefact->user_id = $user_id;
    $artefact->type = $fields['type'];
    $artefact->file_name = $fields['url'];
    $artefact->file_size = '0';
    $artefact->file_type = 'url';
    $artefact->description = $fields['description'];
    $artefact->group = $fields['group'];
    $artefact->hash = '';
    $artefact->data = '';
    $artefact->thumb = '';
    
    return $artefact->_insert();
  }
  
  function calc_diskspace_usage($user_id)
  {
    $diskusage = 0;
    $artefacts = Artefact::get_all("WHERE user_id=$user_id");
    foreach ($artefacts as $artefact)
    {
      $diskusage = $diskusage + $artefact->file_size;
    }
    return $diskusage;
  }

  function update($fields) 
  {
    $artefact = Artefact::load_by_id($fields[id]);
    $artefact->_update($fields);
  }

  function exists($id) 
  {
    $artefact = new Artefact;
    $artefact->id = $id;
    return $artefact->_exists();
  }

  function count($where="") 
  {
    $artefact = new Artefact;
    return $artefact->_count($where);
  }

  function get_all($where_clause="", $order_by="ORDER BY id", $page = 0, $parse = False)
  {
    global $config;
    $artefact = new Artefact;

    if ($page > 0) {
      $start = ($page-1)*$config['opus']['rows_per_page'];
      $limit = $config['opus']['rows_per_page'];
      $artefacts = $artefact->_get_all($where_clause, $order_by, $start, $limit, $parse);
    } else {
      $artefacts = $artefact->_get_all($where_clause, $order_by, 0, 1000, $parse);
    }
    return $artefacts;
  }

  function get_all_by_user_id($user_id)
  {
    return Artefact::get_all("WHERE user_id=$user_id", "ORDER BY group, file_name", 0, true);
  }

  function get_id_and_field($fieldname, $where_clause="") 
  {
    $artefact = new Artefact;
    return  $artefact->_get_id_and_field($fieldname, $where_clause);
  }


  function remove($id=0) 
  {
    $artefact = new Artefact;
    $artefact->_remove_where("WHERE id=$id");
  }

  function get_fields($include_id = false) 
  {
    $artefact = new Artefact;
    return  $artefact->_get_fieldnames($include_id); 
  }
  function request_field_values($include_id = false) 
  {
    $fieldnames = Artefact::get_fields($include_id);
    $nvp_array = array();

    foreach ($fieldnames as $fn)
    {
      $nvp_array = array_merge($nvp_array, array("$fn" => WA::request("$fn")));
    }
    return $nvp_array;
  }

  function get_thumbnail()
  {
    $upload_dir = User::upload_path($this->user_id);
    $system=explode(".",$this->file_name);
    $hash = $this->hash;

    if (preg_match("/image/",$this->file_type)) {
      // create thumbnail
      $dest = imagecreate(100, 100);

      if (preg_match("/jpeg/",$this->file_type)){
      $src_img=imagecreatefromjpeg($upload_dir.$hash);
      }
      if (preg_match("/png/",$this->file_type)){
        $src_img=imagecreatefrompng($upload_dir.$hash);
      } 
      if (preg_match("/gif/",$this->file_type)){
        $src_img=imagecreatefromgif($upload_dir.$hash);
      }

      $original_x=imageSX($src_img);
      $original_y=imageSY($src_img);
      if ($original_x > $original_y) {
        $thumb_w=100;
        $thumb_h=$original_y*(100/$original_x);
      }
      if ($original_x < $original_y) {
        $thumb_w=$original_x*(100/$original_y);
        $thumb_h=100;
      }
      if ($original_x == $original_y) {
        $thumb_w=100;
        $thumb_h=100;
      }

      $upload_dir = $upload_dir;
      $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
      imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
      if (preg_match("/png/",$this->file_type)){
        imagepng($dst_img,$upload_dir."tn_".$hash);
      } elseif (preg_match("/jpeg/",$this->file_type)) {
        imagejpeg($dst_img,$upload_dir."tn_".$hash);
        } else {
        imagegif($dst_img,$upload_dir."tn_".$hash);
      }
      imagedestroy($dst_img);
      imagedestroy($src_img);
    }
    return $upload_dir."tn_".$hash;
  }
}
?>
