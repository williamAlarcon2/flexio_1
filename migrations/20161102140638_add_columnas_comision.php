<?php

use \Flexio\Migration\Migration;

class AddColumnasComision extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
     public function change()
     {
        $tabla = $this->table('com_comisiones');
        $tabla->addColumn('pagadas_colaboradores', 'integer',array('limit' => 11, 'after'=>'fecha_programada_pago'))
        ->addColumn('total_colaboradores','integer',array('limit' => 11, 'after'=>'fecha_programada_pago'))
        ->update();
     }
}
