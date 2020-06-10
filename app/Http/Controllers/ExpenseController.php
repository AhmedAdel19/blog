<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect ;
use App\Models\Expense;
// use App\Models\Paymenttypes;

class ExpenseController extends Controller
{
    protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'expense';
	static $per_page	= '100000';

	public function __construct()
	{
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Expense();
		$this->info = $this->model->makeInfo( $this->module);
	//	dd($this->info);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'expense',
			'return'	=> self::returnUrl()
			
		);

		\App::setLocale(CNF_LANG);
		if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
		    $lang = (\Session::get('lang') != "" ? \Session::get('lang') : CNF_LANG);
		    \App::setLocale($lang);
		}
	}

	public function index( Request $request )
	{

		if($this->access['is_view'] ==0) return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->model->getPrimaryKey() );
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = '';	
		if(!is_null($request->input('search')))
		{
			$search = 	$this->buildSearch('maps');
			$filter = $search['param'];
			$this->data['search_map'] = $search['maps'];
		} 

		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query 
		$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath($this->module);
		
        $this->data['rowData']		= $results['rows'];
        // $paymentMethod = new Paymenttypes();
        // $paymentMethodRow = $paymentMethod->getRow($this->data['rowData']['account_name']);
        // $this->data['rowData']['account_name']		= $paymentMethodRow['payment_type'];
        // Build Pagination 
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();	
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['grid']);
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
        // Render into template
        // dd($this->module.'.index');
		return view($this->module.'.index',$this->data);
	}	

	public function edit(Request $request, $id = null)
	{
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')
                ->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				

		$row = $this->model->find($id);

		if($row)
		{
			$this->data['row'] =  $row;
		} else {
            $this->data['row'] = $this->model->getColumnTable( $this->model->getTable());
        }
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		$this->data['id'] = $id;
		return view($this->module.'.form',$this->data);
		// return Redirect::to($this->module.'/'.$id.'/edit/');
	}

	function update(Request $request, $id = null)
	{
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')
                ->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				

		$row = $this->model->find($id);

		if($row)
		{
			$this->data['row'] =  $row;
		} else {
            $this->data['row'] = $this->model->getColumnTable( $this->model->getTable());
        }
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		$this->data['id'] = $id;
		// return Redirect::to($this->module.'/'.$id.'/edit/');

		return view($this->module.'.form',$this->data);
	}

	function getDetails(Request $request, $id = null)
	{
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')
                ->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				

		$row = $this->model->find($id);

		if($row)
		{
			$this->data['row'] =  $row;
		} else {
            $this->data['row'] = $this->model->getColumnTable( $this->model->getTable());
        }
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		$this->data['id'] = $id;
		return view($this->module.'.details',$this->data);
	}

	public function show( Request $request, $id = null)
	{
		if($this->access['is_detail'] ==0) 
		return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
			$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
			$this->data['id'] = $id;
			$this->data['access']		= $this->access;
			$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
			$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['grid']);
			$this->data['prevnext'] = $this->model->prevNext($id);
            
             if(!is_null($request->input('pdf')))
			{
                define("DOMPDF_ENABLE_HTML5PARSER", true);
                $html = view($this->module.'.pdf', $this->data)->render();
				return \PDF::load($html)->filename($this->data["pageModule"].'-'.$id.'.pdf')->show();
			}

			return view($this->module.'.view',$this->data);
		} else {
			return Redirect::to($this->module)->with('messagetext',\Lang::get('core.norecord'))->with('msgstatus','error');
		}
	}

	function postCopy( Request $request)
	{
	    foreach(\DB::select("SHOW COLUMNS FROM ".$this->model->getTable()) as $column)
        {
			if( $column->Field != $this->model->getPrimaryKey())
				$columns[] = $column->Field;
        }
		
		if(count($request->input('ids')) >=1)
		{
			$toCopy = implode(",",$request->input('ids'));
			$table = $this->model->getTable();
			$sql = "INSERT INTO $table (".implode(",", $columns).") ";
			$model_id = $this->model->getPrimaryKey();
			$sql .= " SELECT ".implode(",", $columns)." FROM $table WHERE $model_id IN (".$toCopy.")";
			\DB::insert($sql);
			return Redirect::to($this->module)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
		}
		else {
		
			return Redirect::to($this->module)->with('messagetext',\Lang::get('core.note_selectrow'))->with('msgstatus','error');
		}
	}		

	function postSave( Request $request)
	{
	    $rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost($this->model->getTable());
			$id = $this->model->insertRow($data , $request->input($this->model->getPrimaryKey()));

			if($id == false){
                return Redirect::to($this->module.'/update/'.
                    $request->input($this->model->getPrimaryKey()))
                    ->with('messagetext',"Paid amount mustn't exceed the due amount")->with('msgstatus','error')
                    ->withErrors($validator)->withInput();
            }

			if(!is_null($request->input('apply')))
			{

				$return = $this->module.'/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = $this->module.'?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input($this->model->getPrimaryKey()) =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {
			return Redirect::to($this->module.'/update/'. $request->input($this->model->getPrimaryKey()))
                ->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
                ->withErrors($validator)->withInput();
		}	
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
            \DB::table($this->model->getTable())->whereIn($this->model->getPrimaryKey(), $request->input('ids'))->delete();

			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfully");
			// redirect
			return Redirect::to($this->module)
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to($this->module)
        		->with('messagetext',\Lang::get('core.note_noitemdeleted'))->with('msgstatus','error');				
		}

	}	

	public static function display( )
	{
		$mode  = isset($_GET['view']) ? 'view' : 'default' ;
		$model  = new Expense();
		$info = $model::makeInfo(self::data["pageModule"]);

		$data = array(
			'pageTitle'	=> 	$info['title'],
			'pageNote'	=>  $info['note']
		);

		if($mode == 'view')
		{
			$id = $_GET['view'];
			$row = $model::getRow($id);
			if($row)
			{
				$data['row'] =  $row;
				$data['fields'] 		=  \SiteHelpers::fieldLang($info['config']['grid']);
				$data['id'] = $id;
				return view('purchase.public.view',$data);
			}

		} else {

			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			$params = array(
				'page'		=> $page ,
				'limit'		=>  (isset($_GET['rows']) ? filter_var($_GET['rows'],FILTER_VALIDATE_INT) : 10 ) ,
				'sort'		=> 'purchase_id' ,
				'order'		=> 'asc',
				'params'	=> '',
				'global'	=> 1
			);

			$result = $model::getRows( $params );
			$data['tableGrid'] 	= $info['config']['grid'];
			$data['rowData'] 	= $result['rows'];

			$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
			$pagination = new Paginator($result['rows'], $result['total'], $params['limit']);
			$pagination->setPath('');
			$data['i']			= ($page * $params['limit'])- $params['limit'];
			$data['pagination'] = $pagination;
			return view('purchase.public.index',$data);
		}


	}



	function postSavepublic( Request $request)
	{
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost($this->model->getTable());
			 $this->model->insertRow($data , $request->input($this->model->getPrimaryKey()));
			return  Redirect::back()->with('messagetext','<p class="alert alert-success">'.\Lang::get('core.note_success').'</p>')->with('msgstatus','success');
		} else {

			return  Redirect::back()->with('messagetext','<p class="alert alert-danger">'.\Lang::get('core.note_error').'</p>')->with('msgstatus','error')
			->withErrors($validator)->withInput();

		}	
	
	}
}
