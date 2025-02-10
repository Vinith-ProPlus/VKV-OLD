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

class PlanningServiceController extends Controller{
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
		$this->ActiveMenuName=activeMenuNames::PlanningServices->value;
		$this->PageTitle="Planning Services";
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
            return view('app.users.planning-services.view',$FormData);
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
				$where .= " AND PS.CreatedOn BETWEEN '$fromDate' AND '$toDate'";
			}
 
			$columns = array(
				array( 'db' => 'PS.PServiceID', 'dt' => '0' ),
				array( 'db' => 'PS.Name', 'dt' => '1' ),
				array( 'db' => 'PS.CreatedOn', 'dt' => '2' ),
				array( 'db' => 'SP.ServiceName', 'dt' => '3' ),
				array( 'db' => 'PS.MobileNumber', 'dt' => '4' ),
				array( 'db' => 'PS.Email', 'dt' => '5' ),
				array( 'db' => 'PS.Message', 'dt' => '6' ),
				array( 'db' => 'S.StateName', 'dt' => '7' ),
				array( 'db' => 'D.DistrictName', 'dt' => '8' ),
			);

			$columns1 = array(
				array( 'db' => 'Name', 'dt' => '0' ),
				array( 'db' => 'CreatedOn', 'dt' => '1' ,'formatter'=>function($d){
                    return date($this->Settings['date-format'],strtotime($d));
                }),
				array( 'db' => 'ServiceName', 'dt' => '2' ),
				array( 'db' => 'MobileNumber', 'dt' => '3' ),
				array( 'db' => 'Email', 'dt' => '4'),
				array( 'db' => 'DistrictName', 'dt' => '5' ),
				array( 'db' => 'StateName', 'dt' => '6' ),
				array( 'db' => 'PServiceID', 'dt' => '7' ,'formatter' =>function($d,$row){
                    $html='';
					if($this->general->isCrudAllow($this->CRUD,"View")==true){
						$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-warning btn-sm m-2 btnView" data-message="'.$row['Message'].'" title="Click to view Message"> <i class="fa fa-eye" aria-hidden="true"></i> </button>';
					} 
					return $html;
                }),
			);
			$data=array();
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_planning_services AS PS LEFT JOIN tbl_service_provided AS SP ON PS.ServiceID = SP.ServiceID LEFT JOIN '.$this->generalDB.'tbl_states AS S ON S.StateID = PS.StateID LEFT JOIN '.$this->generalDB.'tbl_districts AS D ON D.DistrictID = PS.DistrictID';
			$data['PRIMARYKEY']='PS.PServiceID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=$where;
			return SSP::SSP( $data);
		}else{
			return response(array('status'=>false,'message'=>"Access Denied"), 403);
		}
	}

}

