<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Mmb  {
	
	protected $table = 'tbl_expenses';
	protected $primaryKey = 'expense_id';

	public function __construct() {
		parent::__construct();
		
	}

	public function getTable()
    {
        return $this->table;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

	public static function querySelect(  ){
        $table = (new Expense())->getTable();

        return "  SELECT $table.* FROM $table  ";
	}	

	public static function queryWhere(  ){
	    $table = (new Expense())->getTable();
	    $id = (new Expense())->getPrimaryKey();
		return "  WHERE $table.$id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public function insertRow($data,$id){
			   $category_expense_total = (int) DB::table('tbl_categories')->where('category_id', $data["category_id"])->get()[0]->category_total_expense;
			   $new_category_expense_total = $category_expense_total + $data['amount']; 

			   $last_expense_refrence = DB::table('tbl_expenses')->get();
			   if(count($last_expense_refrence)){
					$last_expense_refrence= $last_expense_refrence[count($last_expense_refrence) - 1]->expense_reference;
			   }else{
				$last_expense_refrence = false;
			   }
			   if(!$last_expense_refrence){
				$data['expense_reference']= 1000;
			   }else{
				$data['expense_reference'] = $last_expense_refrence+1;
			   }
			   $data['status']=1;
			   $account_balance = (int) DB::table('tbl_accounts')->where('account_id', $data["account_id"])->get()[0]->balance;
			   $new_account_balance = $account_balance - $data['amount'];
			   
			   \DB::table('tbl_categories')->where('category_id', $data["category_id"])->update(array('category_total_expense' => $new_category_expense_total));
			   \DB::table('tbl_accounts')->where('account_id', $data["account_id"])->update(array('balance' => $new_account_balance));
               return parent::insertRow($data,$id);
       
    }
	

}
