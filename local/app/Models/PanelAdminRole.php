<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedPanelAdminRole;
use App\Models\UpdatedPanelAdminRole;
use App\Models\DeletedPanelAdminRole;
use Request;
use App\Models\PanelAdminRoleStatus;
use App\Models\PanelAdminRoleSection;
class PanelAdminRole extends Model
{
	protected $table = 'ap_panel_admin_role';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedPanelAdminRole;
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
		$complement = new UpdatedPanelAdminRole;
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
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Section;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedPanelAdminRole;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//status
	public function create_Status($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new PanelAdminRoleStatus;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_item = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new PanelAdminRoleStatus;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_item = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Status($args = array()){
		return $this->hasMany('App\Models\PanelAdminRoleStatus', 'id_item');
	}
	public function update_Status($args = array()){
	}
	public function delete_Status($args = array()){
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//section
	public function create_Section($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new PanelAdminRoleSection;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_panel_admin_role = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new PanelAdminRoleSection;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_panel_admin_role = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Section($args = array()){
		return $this->hasMany('App\Models\PanelAdminRoleSection', 'id_panel_admin_role');
	}
	public function update_Section($args = array()){
	}
	public function delete_Section($args = array()){
		$list = $this->read_Section;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}