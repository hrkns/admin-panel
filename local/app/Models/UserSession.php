<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedUserSession;
use App\Models\UpdatedUserSession;
use App\Models\DeletedUserSession;
use Request;
use App\Models\UserSessionActivity;
class UserSession extends Model
{
	protected $table = 'ap_user_session';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedUserSession;
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
		$complement = new UpdatedUserSession;
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
		$list = $this->read_Activity;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedUserSession;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//activity
	public function create_Activity($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserSessionActivity;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user_session = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserSessionActivity;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user_session = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Activity($args = array()){
		return $this->hasMany('App\Models\UserSessionActivity', 'id_user_session');
	}
	public function update_Activity($args = array()){
	}
	public function delete_Activity($args = array()){
		$list = $this->read_Activity;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}