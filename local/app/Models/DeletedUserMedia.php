<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedUserMedia extends Model
{
	protected $table = 'deleted_user_media';
	public $timestamps = false;
}