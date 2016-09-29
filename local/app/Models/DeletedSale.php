<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeletedSale extends Model
{
	protected $table = 'deleted_sale';
	public $timestamps = false;
}