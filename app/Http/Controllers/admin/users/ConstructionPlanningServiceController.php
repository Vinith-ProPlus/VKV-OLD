<?php
namespace App\Http\Controllers\web\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\general;
use SSP;
use logs;
use App\enums\activeMenuNames;
use App\helper\helper;

class ConstructionPlanningServiceController extends Controller{
	private $general;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $Settings;
    private $Menus;
	private $generalDB;
	private $FileTypes;

    public function __construct(){
		$this->ActiveMenuName=activeMenuNames::ConstructionServicePlan->value;
		$this->PageTitle="Construction Service Plan";
        $this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->Settings=$this->general->getSettings();
			$this->generalDB=Helper::getGeneralDB();
			$this->FileTypes=Helper::getFileTypes(["category"=>["Images"]]);
			return $next($request);
		});
    }
    public function view(Request $req){
        if($this->general->isCrudAllow($this->CRUD,"view")){
            $FormData=$this->general->UserInfo;
            $FormData['menus']=$this->Menus;
            $FormData['crud']=$this->CRUD;
			$FormData['ActiveMenuName']=$this->ActiveMenuName;
			$FormData['PageTitle']=$this->PageTitle;
			$FormData['SETTINGS']=$this->Settings;
            return view('app.users.construction-service-plan.view',$FormData);
        }
        return view('errors.403');
    }
	public function TableView(Request $request){
		if($this->general->isCrudAllow($this->CRUD,"view")){
			$where="1 = 1";
			if ($request->FromDate && $request->ToDate) {
				$fromDate = date('Y-m-d', strtotime($request->FromDate));
				$toDate = date('Y-m-d', strtotime($request->ToDate));
				$where .= " AND CPS.CreatedOn BETWEEN '$fromDate' AND '$toDate'";
			}

			$columns = [
				['db' => 'CPS.CPServiceID', 'dt' => '0' ],
				['db' => 'CPS.Name', 'dt' => '1' ],
				['db' => 'CPS.CreatedOn', 'dt' => '2' ],
				['db' => 'CSC.ConServCatName', 'dt' => '3' ],
				['db' => 'CS.ConServName', 'dt' => '4' ],
				['db' => 'CPS.MobileNumber', 'dt' => '5' ],
				['db' => 'CPS.Email', 'dt' => '6' ],
				['db' => 'CPS.Message', 'dt' => '7' ],
				['db' => 'S.StateName', 'dt' => '8' ],
				['db' => 'D.DistrictName', 'dt' => '9' ],
			];

			$columns1 = [
				['db' => 'Name', 'dt' => '0' ],
				['db' => 'CreatedOn', 'dt' => '1' ,'formatter'=>function($d){
                    return date($this->Settings['date-format'],strtotime($d));
                }],
                ['db' => 'ConServCatName', 'dt' => '2'],
                ['db' => 'ConServName', 'dt' => '3'],
                ['db' => 'MobileNumber', 'dt' => '4'],
                ['db' => 'Email', 'dt' => '5'],
                ['db' => 'DistrictName', 'dt' => '6'],
                ['db' => 'StateName', 'dt' => '7'],
                ['db' => 'CPServiceID', 'dt' => '8' ,'formatter' =>function($d,$row){
                    $html='';
					if($this->general->isCrudAllow($this->CRUD,"View")){
						$html='<button type="button" data-id="'.$d.'" class="btn btn-outline-warning btn-sm m-2 btnView" data-message="'.$row['Message'].'" title="Click to view Message"> <i class="fa fa-eye" aria-hidden="true"></i> </button>';
					}
					return $html;
                }],
			];
			$data=[];
			$data['POSTDATA']=$request;
			$data['TABLE']='tbl_construction_plan_services AS CPS LEFT JOIN tbl_construction_service_category AS CSC ON CPS.CSCID = CSC.ConServCatID LEFT JOIN tbl_construction_services AS CS ON CPS.CSID = CS.ConServID LEFT JOIN '.$this->generalDB.'tbl_states AS S ON S.StateID = CPS.StateID LEFT JOIN '.$this->generalDB.'tbl_districts AS D ON D.DistrictID = CPS.DistrictID';
			$data['PRIMARYKEY']='CPS.CPServiceID';
			$data['COLUMNS']=$columns;
			$data['COLUMNS1']=$columns1;
			$data['GROUPBY']=null;
			$data['WHERERESULT']=null;
			$data['WHEREALL']=$where;
			return SSP::SSP( $data);
		}

        return response(['status'=>false,'message'=>"Access Denied"], 403);
    }

}

