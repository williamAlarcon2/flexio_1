<?php
namespace Flexio\Modulo\SubContratos\Services;
use Illuminate\Http\Request;
use Flexio\Library\Jqgrid\JqgridAbstract;
use Flexio\Modulo\SubContratos\Models\Adenda;
use Flexio\Library\Util\FormRequest;


class SubContratoAdendaJqgrid extends JqgridAbstract{

  protected $filters;
  protected $anticipo;
  protected $request;


  function __construct(){
    $this->filters = new SubContratoAdendaQueryFilters;
    $this->scoped = new Adenda;
    $this->request = Request::capture();
  }

  function listar($clause = []){

    list($page, $limit, $sidx, $sord) = $this->inicializar();

    $clause = array_merge($clause, $this->camposBuscar());


    $count = $this->registros($clause)->count();

    list($total_pages, $page, $start) = $this->paginacion($count, $limit, $page);

    $anticipos = $this->registros($clause,$sidx, $sord, $limit, $start)->get();

    $response = $this->armarJqgrid($anticipos,$page, $total_pages, $count);

    return $response;
  }

  function registros($clause = array(),$sidx=null, $sord=null, $limit=null, $start=null){
    $builder = $this->scoped->newQuery();
    $registros = $this->filters->apply($builder, $clause);

    if(!is_null($sidx) && !is_null($sord)) $registros->orderBy($sidx, $sord);
	  if(!is_null($limit) && !is_null($start)) $registros->skip($start)->take($limit);

    return $registros;
  }


  function armarJqgrid($registos, $page, $total_pages, $count){

    $response = new \stdClass();
    $response->page     = $page;
    $response->total    = $total_pages;
    $response->records   = $count;


   if($registos->count() > 0){
      foreach($registos as $i => $row){

        $hidden_options = "";
        $link_option = '<button class="viewOptions btn btn-success btn-sm" type="button" data-id="'. $row->uuid_adenda .'"><i class="fa fa-cog"></i> <span class="hidden-xs hidden-sm hidden-md">Opciones</span></button>';
        $hidden_options = '<a href="'. base_url('subcontratos/editar_adenda/'. $row->uuid_adenda) .'" data-id="'. $row->uuid_adenda .'" class="btn btn-block btn-outline btn-success">Ver Adenda</a>';
        $response->rows[$i]["id"] = $row->uuid_adenda;
        $response->rows[$i]["cell"] = array(
            $row->uuid_adenda,
            '<a style="color:blue;" class="link" href="'. base_url('subcontratos/editar_adenda/'. $row->uuid_adenda) .'">'.$row->codigo.'</a>',
            $row->fecha,
            "$".number_format($row->monto_adenda, 2, '.', ','),
            "$".number_format($row->monto_acumulado, 2, '.', ','),
            $link_option,
            $hidden_options
            );

      }
   }

   return $response;
  }

  function camposBuscar(){
      $campos = FormRequest::data_formulario($this->request->input('campo'));
      if(is_null($campos)){
          return [];
      }
      return $campos;
  }

}
