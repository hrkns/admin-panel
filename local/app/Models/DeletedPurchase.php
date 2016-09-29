<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedPurchase extends Model
{
	protected $table = 'deleted_purchase';
	public $timestamps = false;
}