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
class userRoleController extends Controller{
	private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $Settings;
    private $Menus;
    public function __construct(){
		$this->ActiveMenuName=activeMenuNames::UserRoles->value;
		$this->PageTitle="User Roles";
        $this->middleware('auth');
    
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->Settings=$this->general->getSettings();
			return $next($request);
		});
    }
	public function index(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			return view('app.users.user-roles.view',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/users-and-permissions/user-roles/create');
		}else{
			return view('errors.403');
		}
	}
	
	public function Create(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"Add")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['isEdit']=false;
			return view('app.users.user-roles.role',$FormData);
		}elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/users-and-permissions/user-roles');
		}else{
			return view('errors.403');
		}
	}
	
	public function Edit(Request $req,$RoleID=null){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$FormData=$this->general->UserInfo;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['menus']=$this->Menus;
			$FormData['crud']=$this->CRUD;
			$FormData['EditData']=DB::Table('tbl_user_roles')->where('RoleID',$RoleID)->Where("isShow",1)->Where("DFlag",0)->get();
			if(count($FormData['EditData'])>0){
				return view('app.users.user-roles.role',$FormData);
			}else{
				return view('errors.400');
			}
		}else{
			return view('errors.403');
		}
	}
	public function getMenuData(request $req){
		return $this->general->getMenus(array("Level"=>"L001"));
	}
	public function RoleData(Request $req,$RoleID=null){
		$data=DB::Table('tbl_user_roles')->where('RoleID',$RoleID)->Where("DFlag",0)->get();
		if(count($data)>0){
			if(($data[0]->CRUD!="")&&($data[0]->CRUD!=null)){
				$data[0]->CRUD=unserialize($data[0]->CRUD);
			}
		}
		return $data;
    }
	public function Save(Request $req){
		$OldData=$NewData=array();$RoleID="";
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$rules=array(
				'RoleName' =>'required|min:3|max:100|unique:tbl_user_roles',
			);
			$message=array(
				'RoleName.required'=>'Role Name is required',
				'RoleName.min'=>'Role Name must be at least 3 characters',
				'RoleName.max'=>'Role Name may not be greater than 100 characters',
				'RoleName.unique'=>'The Role Name has already been taken.'
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('status'=>false,'message'=>"User Role Create Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try{
				$RoleID=DocNum::getDocNum(docTypes::UserRoles->value);
				$UserRights=json_decode($req->CRUD,true);
				$data=array(
					'RoleID'=>$RoleID,
					'RoleName'=>$req->RoleName,
					'CRUD'=>serialize($UserRights),
					'CreatedBy'=>$this->UserID,
					'Createdon'=>date("Y-m-d H:i:s"),
					'ActiveStatus'=>1
				);
				$status=DB::Table('tbl_user_roles')->insert($data);
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				DocNum::updateDocNum(docTypes::UserRoles->value);
				$NewData=DB::Table('tbl_user_roles')->where('RoleID',$RoleID)->get();
				$logData=array("Description"=>"New User Role Created ","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::ADD->value,"ReferID"=>$RoleID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				return array('status'=>true,'message'=>"User Role Create Successfully","RoleID"=>$RoleID);
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"User Role Create Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	
	public function Update(Request $req,$RoleID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$rules=array(
				'RoleName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>"tbl_user_roles","WHERE"=>" RoleName='".$req->RoleName."' and RoleID<>'".$RoleID."'"),"This Role Name Already exists.")],
				
				);
			$message=array(
				'RoleName.required'=>'Role Name is required',
				'RoleName.min'=>'Role Name must be at least 3 characters',
				'RoleName.max'=>'Role Name may not be greater than 100 characters',
			);
			$validator = Validator::make($req->all(), $rules,$message);
			
			if ($validator->fails()) {
				return array('success'=>false,'message'=>"User Role Update Failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::Table('tbl_user_roles')->where('RoleID',$RoleID)->get();
				$UserRights=json_decode($req->CRUD,true);
				$sql="Update tbl_user_roles set RoleName='".$req->RoleName."',CRUD='".serialize($UserRights)."',UpdatedOn='".date("Y-m-d H:i:s")."',UpdatedBy='".$this->UserID."' Where RoleID='".$RoleID."'";
				
				$status=DB::statement($sql);
			}catch(Exception $e) {
				
			}
			if($status==true){
				DB::commit();
				$NewData=DB::Table('tbl_user_roles')->where('RoleID',$req->RoleID)->get();
				$logData=array("Description"=>"User Role Changed ","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::UPDATE->value,"ReferID"=>$RoleID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				return array('status'=>true,'message'=>"User Role Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"User Role Update Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$columns = array(
				array( 'db' => 'RoleID', 'dt' => '0' ),
				array( 'db' => 'RoleName', 'dt' => '1' ),
				array( 
						'db' => 'RoleID', 
						'dt' => '2',
						'formatter' => function( $d, $row ) {
							$html='';
							if($this->general->isCrudAllow($this->CRUD,"edit")==true){
								$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success '.$this->general->UserInfo['Theme']['button-size'].'  btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
							}
							return $html;
						} 
				)
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_user_roles';
			$data['PRIMARYKEY']='RoleID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" isShow='1' ";
			return SSP::SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
}
