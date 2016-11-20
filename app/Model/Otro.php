<?php

// app/Model/Otro.php

class Otro extends AppModel {

  public $name = 'Otro';
  public $useTable = 'otros';
  public $belongsTo = array('TipoOtro' => array('foreignKey' => 'tipo_otro_id'));
}
