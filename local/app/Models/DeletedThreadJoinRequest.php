<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedThreadJoinRequest extends Model
{
	protected $table = 'deleted_thread_join_request';
	public $timestamps = false;
}