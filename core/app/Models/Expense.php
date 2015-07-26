<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {

	protected $fillable = ['name', 'vendor','category','amount', 'notes', 'expense_date'];

}
