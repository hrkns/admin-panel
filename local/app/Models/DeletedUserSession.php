<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedUserSession extends Model
{
	protected $table = 'deleted_user_session';
	public $timestamps = false;
}