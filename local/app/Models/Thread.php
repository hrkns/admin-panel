<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedThread;
use App\Models\UpdatedThread;
use App\Models\DeletedThread;
use Request;
use App\Models\ThreadAdmin;
use App\Models\ThreadJoinRequest;
use App\Models\ThreadMessage;
use App\Models\ThreadSpeaker;
class Thread extends Model
{
	protected $table = 'ap_thread';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedThread;
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
		$complement = new UpdatedThread;
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
		$list = $this->read_Admin;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_JoinRequest;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Message;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Speaker;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedThread;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//admin
	public function create_Admin($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ThreadAdmin;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_thread = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ThreadAdmin;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_thread = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Admin($args = array()){
		return $this->hasMany('App\Models\ThreadAdmin', 'id_thread');
	}
	public function update_Admin($args = array()){
	}
	public function delete_Admin($args = array()){
		$list = $this->read_Admin;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//join_request
	public function create_JoinRequest($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ThreadJoinRequest;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_thread = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ThreadJoinRequest;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_thread = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_JoinRequest($args = array()){
		return $this->hasMany('App\Models\ThreadJoinRequest', 'id_thread');
	}
	public function update_JoinRequest($args = array()){
	}
	public function delete_JoinRequest($args = array()){
		$list = $this->read_JoinRequest;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//message
	public function create_Message($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ThreadMessage;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_thread = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ThreadMessage;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_thread = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Message($args = array()){
		return $this->hasMany('App\Models\ThreadMessage', 'id_thread');
	}
	public function update_Message($args = array()){
	}
	public function delete_Message($args = array()){
		$list = $this->read_Message;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//speaker
	public function create_Speaker($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new ThreadSpeaker;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_thread = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new ThreadSpeaker;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_thread = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Speaker($args = array()){
		return $this->hasMany('App\Models\ThreadSpeaker', 'id_thread');
	}
	public function update_Speaker($args = array()){
	}
	public function delete_Speaker($args = array()){
		$list = $this->read_Speaker;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}