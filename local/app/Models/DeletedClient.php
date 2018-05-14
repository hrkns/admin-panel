<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedClient extends Model
{
	protected $table = 'deleted_client';
	public $timestamps = false;
}