<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedUserSessionActivity extends Model
{
	protected $table = 'deleted_user_session_activity';
	public $timestamps = false;
}