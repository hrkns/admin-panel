<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedMasterCurrency;
use App\Models\UpdatedMasterCurrency;
use App\Models\DeletedMasterCurrency;
use Request;
use App\Models\MasterCurrencyExchange;
use App\Models\MasterCurrencyStatus;
class MasterCurrency extends Model
{
	protected $table = 'ap_master_currency';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedMasterCurrency;
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
		$complement = new UpdatedMasterCurrency;
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
		$list = $this->read_Exchange;
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
		$complement = new DeletedMasterCurrency;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}

	//exchange
	public function create_Exchange($args){
		if(gettype($args) == "object"){
			$args->__create__();
			return $args;
		}else{
			if(isAssoc($args)){
				$obj = new MasterCurrencyExchange;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_master_currency = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new MasterCurrencyExchange;
					foreach ($item as $field => $value){
						$obj->$field = $value;
					}
					$obj->id_master_currency = $this->id;
					$obj->__create__();
					array_push($ret, $obj);
				}
				return $ret;
			}
		}
	}
	public function read_Exchange($args = array()){
		return $this->hasMany('App\Models\MasterCurrencyExchange', 'id_master_currency');
	}
	public function update_Exchange($args = array()){
	}
	public function delete_Exchange($args = array()){
		$list = $this->read_Exchange;
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
				$obj = new MasterCurrencyStatus;
				foreach ($args as $field => $value){
					$obj->$field = $value;
				}
				$obj->id_item = $this->id;
				return $obj->__create__();
			}else{
				$ret = array();
				foreach ($args as $key => $item) {
					$obj = new MasterCurrencyStatus;
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
		return $this->hasMany('App\Models\MasterCurrencyStatus', 'id_item');
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