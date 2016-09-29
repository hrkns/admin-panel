<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedEvent extends Model
{
	protected $table = 'deleted_event';
	public $timestamps = false;
}