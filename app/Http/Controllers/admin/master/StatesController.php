<?php

namespace App\Http\Controllers\admin\master;

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
use Hash;
use cruds;
use ValidUnique;
use ValidDB;
use logs;
use Helper;
use activeMenuNames;
use docTypes;
class StatesController extends Controller{
	private $general;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $Settings;
    private $Menus;
	private $generalDB;
    public function __construct(){
		$this->ActiveMenuName='states';
		$this->PageTitle="States";
        /* $this->middleware('auth');
		$this->generalDB=Helper::getGeneralDB();
		$this->middleware(function ($req, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->Settings=$this->general->getSettings();
			return $next($req);
		}); */
    }
	public function view(Request $req){

		$FormData=[];
		$FormData['ActiveMenuName']=$this->ActiveMenuName;
		$FormData['PageTitle']=$this->PageTitle;
		return view('admin.master.states.view',$FormData);
		/* if($this->general->isCrudAllow($this->CRUD,"view")==true){
		}elseif($this->general->isCrudAllow($this->CRUD,"Add")==true){
			return Redirect::to('/admin/master/general/states/create');
		}else{
			return view('errors.403');
		} */
	}

    public function TrashView(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"restore")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
            return view('app.master.general.states.trash',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
			return Redirect::to('/admin/master/general/states/');
        }else{
            return view('errors.403');
        }
    }
    public function create(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"add")==true){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['OtherCruds']=$OtherCruds;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=false;
            return view('app.master.general.states.create',$FormData);
        }elseif($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/admin/master/general/states/');
        }else{
            return view('errors.403');
        }
    }
    public function edit(Request $req,$StateID){
        if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OtherCruds=array(
				"Country"=>$this->general->getCrudOperations(activeMenuNames::Country->value),
			);
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['OtherCruds']=$OtherCruds;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['isEdit']=true;
			$FormData['StateID']=$StateID;
			$FormData['EditData']=DB::Table($this->generalDB.'tbl_states')->where('DFlag',0)->Where('StateID',$StateID)->get();
			if(count($FormData['EditData'])>0){
				return view('app.master.general.states.create',$FormData);
			}else{
				return view('errors.403');
			}
        }else if($this->general->isCrudAllow($this->CRUD,"view")==true){
            return Redirect::to('/admin/master/general/states/');
        }else{
            return view('errors.403');
        }
    }
	public function getCountry(request $req){
		return DB::Table($this->generalDB.'tbl_countries')->where('ActiveStatus','Active')->where('DFlag',0)->get();
	}
    public function save(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"add")==true){
			$OldData=array();$NewData=array();$StateID="";
			$ValidDB=array();
			$ValidDB['Country']['TABLE']=$this->generalDB."tbl_countries";
			$ValidDB['Country']['ErrMsg']="Country name  does not exist";
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"CountryID","CONDITION"=>"=","VALUE"=>$req->CountryID);
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

			$rules=array(
				'CountryID' =>['required',$ValidDB['Country']],
				'StateName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>$this->generalDB."tbl_states","WHERE"=>" StateName='".$req->StateName."' and CountryID='".$req->CountryID."' "),"This State Name is already taken.")],
				'StateCode' =>['required',new ValidUnique(array("TABLE"=>$this->generalDB."tbl_states","WHERE"=>" StateCode='".$req->StateCode."' and CountryID='".$req->CountryID."' "),"This State Code is already taken.")],
			);
			$message=array();
			$validator = Validator::make($req->all(), $rules,$message);

			if ($validator->fails()) {
				return array('status'=>false,'message'=>"State Create Failed",'errors'=>$validator->errors());
			}
			DB::beginTransaction();
			$status=false;
			try {
				$StateID=DocNum::getDocNum(docTypes::States->value,"",Helper::getCurrentFY());
				$data=array(
					"StateID"=>$StateID,
					"StateName"=>$req->StateName,
					"StateCode"=>$req->StateCode,
					"CountryID"=>$req->CountryID,
					"ActiveStatus"=>$req->ActiveStatus,
					"CreatedBy"=>$this->UserID,
					"CreatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table($this->generalDB.'tbl_states')->insert($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				DocNum::updateDocNum(docTypes::States->value);
				$NewData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$logData=array("Description"=>"New State Created","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::ADD->value,"ReferID"=>$StateID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"State Created Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"State Create Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}
    public function update(Request $req,$StateID){
		if($this->general->isCrudAllow($this->CRUD,"edit")==true){
			$OldData=array();$NewData=array();
			$ValidDB=array();
			$ValidDB['Country']['TABLE']=$this->generalDB."tbl_countries";
			$ValidDB['Country']['ErrMsg']="Country name  does not exist";
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"CountryID","CONDITION"=>"=","VALUE"=>$req->CountryID);
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"ActiveStatus","CONDITION"=>"=","VALUE"=>1);
			$ValidDB['Country']['WHERE'][]=array("COLUMN"=>"DFlag","CONDITION"=>"=","VALUE"=>0);

			$rules=array(
				'CountryID' =>['required',$ValidDB['Country']],
				'StateName' =>['required','min:3','max:100',new ValidUnique(array("TABLE"=>$this->generalDB."tbl_states","WHERE"=>" StateName='".$req->StateName."' and CountryID='".$req->CountryID."' and StateID <> '".$StateID."' "),"This State Name is already taken.")],
				'StateCode' =>['required',new ValidUnique(array("TABLE"=>$this->generalDB."tbl_states","WHERE"=>" StateCode='".$req->StateCode."' and CountryID='".$req->CountryID."' and StateID <> '".$StateID."' "),"This State Code is already taken.")],
			);
			$message=array();
			$validator = Validator::make($req->all(), $rules,$message);

			if ($validator->fails()) {
				return array('status'=>false,'message'=>"State Update Failed",'errors'=>$validator->errors());
			}
			DB::beginTransaction();
			$status=false;
			try {
				$OldData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$data=array(
					"StateName"=>$req->StateName,
					"StateCode"=>$req->StateCode,
					"CountryID"=>$req->CountryID,
					"ActiveStatus"=>$req->ActiveStatus,
					"UpdatedBy"=>$this->UserID,
					"UpdatedOn"=>date("Y-m-d H:i:s")
				);
				$status=DB::Table($this->generalDB.'tbl_states')->where('StateID',$StateID)->update($data);
			}catch(Exception $e) {
				$status=false;
			}

			if($status==true){
				$NewData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$logData=array("Description"=>"State Updated ","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::UPDATE->value,"ReferID"=>$StateID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				DB::commit();
				return array('status'=>true,'message'=>"State Updated Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"State Update Failed");
			}
		}else{
			return array('status'=>false,'message'=>'Access denined');
		}
	}

	public function Delete(Request $req,$StateID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"delete")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$status=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->update(array("DFlag"=>1,"DeletedBy"=>$this->UserID,"DeletedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {

			}
			if($status==true){
				DB::commit();
				$logData=array("Description"=>"State has been Deleted ","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::DELETE->value,"ReferID"=>$StateID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				return array('status'=>true,'message'=>"State Deleted Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"State Delete Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function Restore(Request $req,$StateID){
		$OldData=$NewData=array();
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$status=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->update(array("DFlag"=>0,"UpdatedBy"=>$this->UserID,"UpdatedOn"=>date("Y-m-d H:i:s")));
			}catch(Exception $e) {

			}
			if($status==true){
				DB::commit();
				$NewData=DB::table($this->generalDB.'tbl_states')->where('StateID',$StateID)->get();
				$logData=array("Description"=>"State has been Restored ","ModuleName"=>$this->ActiveMenuName,"Action"=>cruds::RESTORE->value,"ReferID"=>$StateID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
				logs::Store($logData);
				return array('status'=>true,'message'=>"State Restored Successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"State Restore Failed");
			}
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TableView(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"view")==true){
			$columns = array(
				array( 'db' => 'S.StateID', 'dt' => '0' ),
				array( 'db' => 'S.StateName', 'dt' => '1' ),
				array( 'db' => 'C.CountryName', 'dt' => '2' ),
				array( 'db' => 'S.ActiveStatus', 'dt' => '3'),
			);
			$columns1 = array(
				array( 'db' => 'StateID', 'dt' => '0' ),
				array( 'db' => 'StateName', 'dt' => '1' ),
				array( 'db' => 'CountryName', 'dt' => '2' ),
				array( 'db' => 'ActiveStatus', 'dt' => '3',
					'formatter' => function( $d, $row ) {
						if($d=="Active"){
							return "<span class='badge badge-success m-1'>Active</span>";
						}else{
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array( 'db' => 'StateID', 'dt' => '4',
					'formatter' => function( $d, $row ) {
						$html='';
						if($this->general->isCrudAllow($this->CRUD,"edit")==true){
							$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-success '.$this->general->UserInfo['Theme']['button-size'].' m-5 mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
						}
						if($this->general->isCrudAllow($this->CRUD,"delete")==true){
							$html.='<button type="button" data-id="'.$d.'" class="btn  btn-outline-danger '.$this->general->UserInfo['Theme']['button-size'].' m-5 btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
						}
						return $html;
					}
				)
			);
			$Where = " S.DFlag=0 and S.CountryID = '$req->CountryID'";
			if($req->ActiveStatus != ""){
				$Where.=" and S.ActiveStatus = '$req->ActiveStatus'";
			}
			$data=array();
			$data['POSTDATA']=$req;
			$data['TABLE']=$this->generalDB.'tbl_states as S LEFT JOIN '.$this->generalDB.'tbl_countries as C ON C.CountryID = S.CountryID';
			$data['PRIMARYKEY']='StateID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=$Where;
			return SSP::SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $req){
		if($this->general->isCrudAllow($this->CRUD,"restore")==true){
			$columns = array(
				array( 'db' => 'S.StateID', 'dt' => '0' ),
				array( 'db' => 'S.StateName', 'dt' => '1' ),
				array( 'db' => 'C.CountryName', 'dt' => '2' ),
				array( 'db' => 'S.ActiveStatus', 'dt' => '3'),
			);
			$columns1 = array(
				array( 'db' => 'StateID', 'dt' => '0' ),
				array( 'db' => 'StateName', 'dt' => '1' ),
				array( 'db' => 'CountryName', 'dt' => '2' ),
				array( 'db' => 'ActiveStatus', 'dt' => '3',
					'formatter' => function( $d, $row ) {
						if($d=="Active"){
							return "<span class='badge badge-success m-1'>Active</span>";
						}else{
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array( 'db' => 'StateID', 'dt' => '4',
					'formatter' => function( $d, $row ) {
						$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-success '.$this->general->UserInfo['Theme']['button-size'].'  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);
			$data=array();
			$data['POSTDATA']=$req;
			$data['TABLE']=$this->generalDB.'tbl_states as S LEFT JOIN '.$this->generalDB.'tbl_countries as C ON C.CountryID = S.CountryID';
			$data['PRIMARYKEY']='StateID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=" S.DFlag=1 ";
			return SSP::SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}
}
