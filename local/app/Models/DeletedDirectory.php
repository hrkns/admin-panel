<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedDirectory extends Model
{
	protected $table = 'deleted_directory';
	public $timestamps = false;
}