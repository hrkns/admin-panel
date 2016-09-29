<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedThreadMessage extends Model
{
	protected $table = 'deleted_thread_message';
	public $timestamps = false;
}