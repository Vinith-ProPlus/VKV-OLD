<?php
namespace App\Http\Controllers\web\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DocNum;
use general;
use SSP;
use DB;
use Auth;
use cruds;
use ValidUnique;
use logs;
use activeMenuNames;
use docTypes;
use Helper;
use Illuminate\Support\Facades\Hash;

class UnregisteredUsersController extends Controller{
	private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $logs;
	private $Settings;
    private $Menus;
	private $generalDB;
	private $FileTypes;

    public function __construct(){
		$this->ActiveMenuName=activeMenuNames::UnregisteredUsers->value;
		$this->PageTitle="Unregistered Users";
        $this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->Settings=$this->general->getSettings();
			$this->generalDB=Helper::getGeneralDB();
			$this->FileTypes=Helper::getFileTypes(array("category"=>array("Images")));
			return $next($request);
		});
    }
    public function view(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"view")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['SETTINGS']=$this->Settings;
            return view('app.users.unregistered-users.view',$FormData);
        }else{
            return view('errors.403');
        }
    }
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$where="1 = 1";
			if ($request->FromDate && $request->ToDate) {
				$fromDate = date('Y-m-d', strtotime($request->FromDate));
				$toDate = date('Y-m-d', strtotime($request->ToDate));
				$where .= " AND CreatedOn BETWEEN '$fromDate' AND '$toDate'";
			}
			if($request->LoginType){
				$where .=" and LoginType = '".$request->LoginType."'";
			}

			$columns = array(
				array( 'db' => 'MobileNumber', 'dt' => '0' ),
				array( 'db' => 'LoginType', 'dt' => '1' )
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_unregistered_users';
			$data['PRIMARYKEY']='SNo';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=$where;
			return SSP::SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	
}

