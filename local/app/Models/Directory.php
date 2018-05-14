<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedDirectory;
use App\Models\UpdatedDirectory;
use App\Models\DeletedDirectory;
use Request;
use App\Models\DirectoryRolePermises;
use App\Models\DirectoryUserPermises;
use App\Models\DirectoryDownload;
class Directory extends Model
{
	protected $table = 'ap_directory';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedDirectory;
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
		$complement = new UpdatedDirectory;
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
		$list = $this->read_RolePermises;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_UserPermises;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Download;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedDirectory;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//role_permises
	public function create_RolePermises($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new DirectoryRolePermises;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_directory = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new DirectoryRolePermises;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_directory = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_RolePermises($args = array()){
		return $this->hasMany('App\Models\DirectoryRolePermises', 'id_directory');
	}
	public function update_RolePermises($args = array()){
	}
	public function delete_RolePermises($args = array()){
		$list = $this->read_RolePermises;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//user_permises
	public function create_UserPermises($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new DirectoryUserPermises;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_directory = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new DirectoryUserPermises;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_directory = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_UserPermises($args = array()){
		return $this->hasMany('App\Models\DirectoryUserPermises', 'id_directory');
	}
	public function update_UserPermises($args = array()){
	}
	public function delete_UserPermises($args = array()){
		$list = $this->read_UserPermises;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//download
	public function create_Download($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new DirectoryDownload;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_directory = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new DirectoryDownload;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_directory = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Download($args = array()){
		return $this->hasMany('App\Models\DirectoryDownload', 'id_directory');
	}
	public function update_Download($args = array()){
	}
	public function delete_Download($args = array()){
		$list = $this->read_Download;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}