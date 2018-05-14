<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedUser;
use App\Models\UpdatedUser;
use App\Models\DeletedUser;
use Request;
use App\Models\UserMedia;
use App\Models\UserPreferences;
use App\Models\UserRole;
use App\Models\UserSession;
use App\Models\UserStatus;
use App\Models\UserSignupConfirmation;
use App\Models\UserAccountRecovering;
use App\Models\UserSectionAmountTimesVisited;
class User extends Model
{
	protected $table = 'ap_user';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedUser;
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
		$complement = new UpdatedUser;
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
		$list = $this->read_Media;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Preferences;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Role;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Session;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_SignupConfirmation;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_AccountRecovering;
		foreach($list as $item){
			$item->__delete__();
		}
		$list = $this->read_SectionAmountTimesVisited;
		foreach($list as $item){
			$item->__delete__();
		}
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedUser;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//media
	public function create_Media($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserMedia;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserMedia;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Media($args = array()){
		return $this->hasMany('App\Models\UserMedia', 'id_user');
	}
	public function update_Media($args = array()){
	}
	public function delete_Media($args = array()){
		$list = $this->read_Media;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//preferences
	public function create_Preferences($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserPreferences;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserPreferences;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Preferences($args = array()){
		return $this->hasMany('App\Models\UserPreferences', 'id_user');
	}
	public function update_Preferences($args = array()){
	}
	public function delete_Preferences($args = array()){
		$list = $this->read_Preferences;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//role
	public function create_Role($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserRole;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserRole;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Role($args = array()){
		return $this->hasMany('App\Models\UserRole', 'id_user');
	}
	public function update_Role($args = array()){
	}
	public function delete_Role($args = array()){
		$list = $this->read_Role;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//session
	public function create_Session($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserSession;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserSession;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Session($args = array()){
		return $this->hasMany('App\Models\UserSession', 'id_user');
	}
	public function update_Session($args = array()){
	}
	public function delete_Session($args = array()){
		$list = $this->read_Session;
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
				$obj = new UserStatus;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_item = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserStatus;
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
		return $this->hasMany('App\Models\UserStatus', 'id_item');
	}
	public function update_Status($args = array()){
	}
	public function delete_Status($args = array()){
		$list = $this->read_Status;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//signup_confirmation
	public function create_SignupConfirmation($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserSignupConfirmation;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserSignupConfirmation;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_SignupConfirmation($args = array()){
		return $this->hasMany('App\Models\UserSignupConfirmation', 'id_user');
	}
	public function update_SignupConfirmation($args = array()){
	}
	public function delete_SignupConfirmation($args = array()){
		$list = $this->read_SignupConfirmation;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//account_recovering
	public function create_AccountRecovering($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserAccountRecovering;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserAccountRecovering;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_AccountRecovering($args = array()){
		return $this->hasMany('App\Models\UserAccountRecovering', 'id_user');
	}
	public function update_AccountRecovering($args = array()){
	}
	public function delete_AccountRecovering($args = array()){
		$list = $this->read_AccountRecovering;
		foreach($list as $item){
			$item->__delete__();
		}
	}

	//section_amount_times_visited
	public function create_SectionAmountTimesVisited($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new UserSectionAmountTimesVisited;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_user = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new UserSectionAmountTimesVisited;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_user = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_SectionAmountTimesVisited($args = array()){
		return $this->hasMany('App\Models\UserSectionAmountTimesVisited', 'id_user');
	}
	public function update_SectionAmountTimesVisited($args = array()){
	}
	public function delete_SectionAmountTimesVisited($args = array()){
		$list = $this->read_SectionAmountTimesVisited;
		foreach($list as $item){
			$item->__delete__();
		}
	}
}