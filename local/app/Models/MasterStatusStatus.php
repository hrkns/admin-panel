<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatedMasterStatusStatus;
use App\Models\UpdatedMasterStatusStatus;
use App\Models\DeletedMasterStatusStatus;
use Request;
class MasterStatusStatus extends Model
{
	protected $table = 'ap_master_status_status';
	public $timestamps = false;
	public function __create__(){
		$this->save();
		$info = $this->toJson();
		//insert created_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new CreatedMasterStatusStatus;
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
		$complement = new UpdatedMasterStatusStatus;
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
		$this->delete();
		//insert deleted_{tablename_without_prefix} row[?]
		/******/
		/*your code*/
		$complement = new DeletedMasterStatusStatus;
		$complement->id_item = $this->id;
		$complement->info = $info;
		$complement->hash_operation = HASH_OPERATION;
		$complement->deleted_by = Request::session()->has("iduser")?Request::session()->get("iduser"):null;
		$complement->save();
		/******/
	}
}