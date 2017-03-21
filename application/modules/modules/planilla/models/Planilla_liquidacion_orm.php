 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Planilla_liquidacion_orm extends Model
{
	protected $table = 'pln_planilla_liquidacion';
	protected $fillable = [ 'planilla_id','liquidacion_id','estado_ingreso_horas'];
	protected $guarded = ['id'];
	public $timestamps = false;
   
}