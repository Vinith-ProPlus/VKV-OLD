<?php

namespace App\Http\Controllers\Admin\ProjectReports;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Admin\ManageProjects\ProjectStage;
use App\Models\Admin\ManageProjects\ProjectTask;
use App\Http\Controllers\Controller;
 

class ProjectReportsController extends Controller
{
    private $projects; 

    public function __construct()
    {
        $this->projects = Project::withoutTrashed(); 
    }
    
    public function index(){
        $projects = $this->projects->get();
        return view('admin.project_reports.index', compact('projects'));
    }

    public function create(Request $request){
        $project = $this->projects->where('id',$request->input('project'))->first();
        $stages = $project->stages; 
        $contracts = $project->contracts;
        $amenities = $project->amenities; 
        return view('report', compact('project','stages','contracts','amenities'));
    }

    public function getProjectTasks(Request $request){
        $projectsTasks = ProjectTask::withoutTrashed(); 
        
        if($request->input('stage_id')){
            $projectsTasks->where('stage_id', $request->input('stage_id'));
        }

        return $projectsTasks->get();
    }
}
