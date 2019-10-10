<?php
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
   public $fillable = ['item_number','transaction_id','currency_code','payment_status'];
}
