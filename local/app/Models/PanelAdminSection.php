<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedPanelAdminSection;
use App\Models\UpdatedPanelAdminSection;
use App\Models\DeletedPanelAdminSection;
use Request;
use App\Models\PanelAdminSectionTerm;
class PanelAdminSection extends Model
{
	protected $table = 'ap_panel_admin_section';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedPanelAdminSection;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->created_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		return $this;
		/******/
	}
	public function __update__(){
		$info = self::where("id", "=", $this->id)->get();
		$info = count($info)>0?$info[0]->toJson():"";
		$this->save();
		//insert updated_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new UpdatedPanelAdminSection;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->updated_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}
	public function __delete__(){
		$info = self::where("id", "=", $this->id)->get();
		$info = count($info)>0?$info[0]->toJson():"";
		$list = $this->read_Term;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedPanelAdminSection;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//term
	public function create_Term($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new PanelAdminSectionTerm;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_panel_admin_section = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new PanelAdminSectionTerm;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_panel_admin_section = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Term($args = array()){
		return $this->hasMany('App\Models\PanelAdminSectionTerm', 'id_panel_admin_section');
	}
	public function update_Term($args = array()){
	}
	public function delete_Term($args = array()){
		$list = $this->read_Term;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}