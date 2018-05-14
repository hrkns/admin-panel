<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedMasterAdministrativeDivisionInstance;
use App\Models\UpdatedMasterAdministrativeDivisionInstance;
use App\Models\DeletedMasterAdministrativeDivisionInstance;
use Request;
use App\Models\MasterAdministrativeDivisionInstanceParent;
use App\Models\MasterAdministrativeDivisionInstanceStatus;
use App\Models\MasterAdministrativeDivisionInstanceType;
class MasterAdministrativeDivisionInstance extends Model
{
	protected $table = 'ap_master_administrative_division_instance';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedMasterAdministrativeDivisionInstance;
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
		$complement = new UpdatedMasterAdministrativeDivisionInstance;
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
		$list = $this->read_Parent;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Type;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedMasterAdministrativeDivisionInstance;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//parent
	public function create_Parent($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new MasterAdministrativeDivisionInstanceParent;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_master_administrative_division_instance = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new MasterAdministrativeDivisionInstanceParent;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_master_administrative_division_instance = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Parent($args = array()){
		return $this->hasMany('App\Models\MasterAdministrativeDivisionInstanceParent', 'id_master_administrative_division_instance');
	}
	public function update_Parent($args = array()){
	}
	public function delete_Parent($args = array()){
		$list = $this->read_Parent;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//status
	public function create_Status($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new MasterAdministrativeDivisionInstanceStatus;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_item = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new MasterAdministrativeDivisionInstanceStatus;
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
		return $this->hasMany('App\Models\MasterAdministrativeDivisionInstanceStatus', 'id_item');
	}
	public function update_Status($args = array()){
	}
	public function delete_Status($args = array()){
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//type
	public function create_Type($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new MasterAdministrativeDivisionInstanceType;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_master_administrative_division_instance = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new MasterAdministrativeDivisionInstanceType;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_master_administrative_division_instance = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Type($args = array()){
		return $this->hasMany('App\Models\MasterAdministrativeDivisionInstanceType', 'id_master_administrative_division_instance');
	}
	public function update_Type($args = array()){
	}
	public function delete_Type($args = array()){
		$list = $this->read_Type;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}