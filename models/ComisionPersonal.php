<?php

namespace Model;

use Model\ActiveRecord;

class ComisionPersonal extends ActiveRecord {
    
    public static $tabla = 'macs_comision_personal';
    public static $idTabla = 'comision_personal_id';
    public static $columnasDB = 
    [
        'comision_id',
        'usuario_id',
        'comision_personal_fecha_asignacion',
        'comision_personal_usuario_asigno',
        'comision_personal_observaciones',
        'comision_personal_situacion'
    ];
    
    public $comision_personal_id;
    public $comision_id;
    public $usuario_id;
    public $comision_personal_fecha_asignacion;
    public $comision_personal_usuario_asigno;
    public $comision_personal_observaciones;
    public $comision_personal_situacion;
    
    public function __construct($comision_personal = [])
    {
        $this->comision_personal_id = $comision_personal['comision_personal_id'] ?? null;
        $this->comision_id = $comision_personal['comision_id'] ?? 0;
        $this->usuario_id = $comision_personal['usuario_id'] ?? 0;
        $this->comision_personal_fecha_asignacion = $comision_personal['comision_personal_fecha_asignacion'] ?? '';
        $this->comision_personal_usuario_asigno = $comision_personal['comision_personal_usuario_asigno'] ?? 0;
        $this->comision_personal_observaciones = $comision_personal['comision_personal_observaciones'] ?? '';
        $this->comision_personal_situacion = $comision_personal['comision_personal_situacion'] ?? 1;
    }

    public static function EliminarComisionPersonal($id){
        $sql = "UPDATE macs_comision_personal SET comision_personal_situacion = 0 WHERE comision_personal_id = $id";
        return self::SQL($sql);
    }

}