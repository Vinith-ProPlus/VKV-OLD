<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStock;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Yajra\DataTables\Facades\DataTables;

class ProjectStockController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::all();

        if ($request->ajax()) {
            $query = ProjectStock::with(['project', 'product', 'category', 'updatedBy']);

            if ($request->has('project_id') && !empty($request->project_id)) {
                $query->where('project_id', $request->project_id);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('quantity', function($row) {
                    return number_format($row->quantity, 2);
                })
                ->editColumn('last_updated', function($row) {
                    return $row->updated_at->format('d-m-Y H:i') . ' by ' .
                        ($row->updatedBy ? $row->updatedBy->name : 'System');
                })
                ->make(true);
        }

        return view('admin.project_stocks.index', compact('projects'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'adjustment_type' => 'required|in:add,subtract,set',
            'reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $stock = ProjectStock::where('project_id', $request->project_id)
                ->where('product_id', $request->product_id)
                ->first();

            $product = Product::findOrFail($request->product_id);

            if (!$stock && $request->adjustment_type === 'subtract') {
                throw new RuntimeException("Cannot subtract from non-existent stock.");
            }

            if (!$stock) {
                // Create new stock if it doesn't exist
                $stock = new ProjectStock([
                    'project_id' => $request->project_id,
                    'product_id' => $request->product_id,
                    'category_id' => $product->category_id,
                    'quantity' => 0,
                ]);
            }

            // Apply adjustment
            switch ($request->adjustment_type) {
                case 'add':
                    $stock->quantity += $request->quantity;
                    break;
                case 'subtract':
                    if ($stock->quantity < $request->quantity) {
                        throw new RuntimeException("Cannot subtract more than available stock.");
                    }
                    $stock->quantity -= $request->quantity;
                    break;
                case 'set':
                    $stock->quantity = $request->quantity;
                    break;
            }

            $stock->last_updated_by = Auth::id();
            $stock->last_transaction_type = 'Manual Adjustment: ' . $request->reason;
            $stock->save();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
