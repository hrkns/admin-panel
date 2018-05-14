<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedPanelAdminRoleSection;
use App\Models\UpdatedPanelAdminRoleSection;
use App\Models\DeletedPanelAdminRoleSection;
use Request;
use App\Models\PanelAdminRoleSectionAction;
class PanelAdminRoleSection extends Model
{
	protected $table = 'ap_panel_admin_role_section';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedPanelAdminRoleSection;
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
		$complement = new UpdatedPanelAdminRoleSection;
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
		$list = $this->read_Action;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedPanelAdminRoleSection;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//action
	public function create_Action($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new PanelAdminRoleSectionAction;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_panel_admin_role_section = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new PanelAdminRoleSectionAction;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_panel_admin_role_section = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Action($args = array()){
		return $this->hasMany('App\Models\PanelAdminRoleSectionAction', 'id_panel_admin_role_section');
	}
	public function update_Action($args = array()){
	}
	public function delete_Action($args = array()){
		$list = $this->read_Action;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}