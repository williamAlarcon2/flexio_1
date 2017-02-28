<?php
namespace Flexio\Modulo\ConfiguracionContabilidad\Repository;
use Flexio\Modulo\ConfiguracionContabilidad\Models\CuentaRemesaEntrante as CuentaRemesaEntrante;
//cargar el modelo codeigniter transaccion

class CuentaRemesaEntranteRepository{

  public function find($id)
  {
    return CuentaRemesaEntrante::find($id);
  }

  public function create($create)
  {
    return CuentaRemesaEntrante::create($create);
  }

  public function update($update)
  {
      return CuentaRemesaEntrante::update($update);
  }

  public function delete($condicion){
    return CuentaRemesaEntrante::where(function($query) use($condicion){
      $query->where('empresa_id','=',$condicion['empresa_id']);
      $query->where('cuenta_id','=',$condicion['cuenta_id']);
    })->delete();
  }

  public function getAll($empresa=[]){
    if(empty($empresa))return $empresa;
    return CuentaRemesaEntrante::where($empresa)->get();
  }

  public function tieneCuenta($empresa=[]){
    if(empty($empresa))return false;
    if(CuentaRemesaEntrante::where($empresa)->get()->count() > 0){
        return  true;
    }
    return false;
  }

  public function tienes_transacciones($condicion = []){
    $cuenta_pagar = CuentaRemesaEntrante::where($condicion)->get()->last();
    if(!is_null($cuenta_pagar)){
      return $cuenta_pagar->tienes_transacciones();
    }
    return false;
  }

}
