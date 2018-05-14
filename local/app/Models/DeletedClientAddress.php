<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedClientAddress extends Model
{
	protected $table = 'deleted_client_address';
	public $timestamps = false;
}