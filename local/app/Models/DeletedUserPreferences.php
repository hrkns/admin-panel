<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedUserPreferences extends Model
{
	protected $table = 'deleted_user_preferences';
	public $timestamps = false;
}