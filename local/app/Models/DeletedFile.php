<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedFile extends Model
{
	protected $table = 'deleted_file';
	public $timestamps = false;
}