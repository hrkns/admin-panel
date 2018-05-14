<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedThread extends Model
{
	protected $table = 'deleted_thread';
	public $timestamps = false;
}