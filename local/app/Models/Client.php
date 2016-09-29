<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedClient;
use App\Models\UpdatedClient;
use App\Models\DeletedClient;
use Request;
use App\Models\ClientAddress;
use App\Models\ClientDocumentation;
use App\Models\ClientMedia;
use App\Models\ClientStatus;
class Client extends Model
{
	protected $table = 'ap_client';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedClient;
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
		$complement = new UpdatedClient;
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
		$list = $this->read_Address;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Documentation;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Media;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedClient;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//address
	public function create_Address($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ClientAddress;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_client = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ClientAddress;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_client = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Address($args = array()){
		return $this->hasMany('App\Models\ClientAddress', 'id_client');
	}
	public function update_Address($args = array()){
	}
	public function delete_Address($args = array()){
		$list = $this->read_Address;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//documentation
	public function create_Documentation($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ClientDocumentation;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_client = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ClientDocumentation;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_client = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Documentation($args = array()){
		return $this->hasMany('App\Models\ClientDocumentation', 'id_client');
	}
	public function update_Documentation($args = array()){
	}
	public function delete_Documentation($args = array()){
		$list = $this->read_Documentation;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//media
	public function create_Media($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ClientMedia;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_client = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ClientMedia;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_client = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Media($args = array()){
		return $this->hasMany('App\Models\ClientMedia', 'id_client');
	}
	public function update_Media($args = array()){
	}
	public function delete_Media($args = array()){
		$list = $this->read_Media;
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
				$obj = new ClientStatus;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_item = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ClientStatus;
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
		return $this->hasMany('App\Models\ClientStatus', 'id_item');
	}
	public function update_Status($args = array()){
	}
	public function delete_Status($args = array()){
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}