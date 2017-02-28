<?php
namespace Flexio\Modulo\ConfiguracionContabilidad\Repository;
use Flexio\Modulo\ConfiguracionContabilidad\Models\CuentaAseguradoraPagar as CuentaAseguradoraPagar;
//cargar el modelo codeigniter transaccion

class CuentaAseguradoraPagarRepository{

  public function find($id)
  {
    return CuentaAseguradoraPagar::find($id);
  }

  public function create($create)
  {
    return CuentaAseguradoraPagar::create($create);
  }

  public function update($update)
  {
      return CuentaAseguradoraPagar::update($update);
  }

  public function delete($condicion){
    return CuentaAseguradoraPagar::where(function($query) use($condicion){
      $query->where('empresa_id','=',$condicion['empresa_id']);
      $query->where('cuenta_id','=',$condicion['cuenta_id']);
    })->delete();
  }

  public function getAll($empresa=[]){
    if(empty($empresa))return $empresa;
    return CuentaAseguradoraPagar::where($empresa)->get();
  }

  public function tieneCuenta($empresa=[]){
    if(empty($empresa))return false;
    if(CuentaAseguradoraPagar::where($empresa)->get()->count() > 0){
        return  true;
    }
    return false;
  }

  public function tienes_transacciones($condicion = []){
    $cuenta_pagar = CuentaAseguradoraPagar::where($condicion)->get()->last();
    if(!is_null($cuenta_pagar)){
      return $cuenta_pagar->tienes_transacciones();
    }
    return false;
  }

}