<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedUser extends Model
{
	protected $table = 'deleted_user';
	public $timestamps = false;
}