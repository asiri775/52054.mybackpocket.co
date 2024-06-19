<?php
namespace App\Http\Controllers\Reports;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\Budget;



use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBudgetReportTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $budgets = Budget::where('created_by', Auth::user()->id);
        switch (request()->date_option) {
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $budgets = $budgets->whereBetween('budget_date', [$start, $end]);
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $budgets = $budgets->whereBetween('budget_date', [$start, $end]);
                break;
            default:
                break;
        }
        if (request()->from) {
            $budgets = $budgets->whereDate('budget_date', '>=', Carbon::createFromDate(request()->from));
        }
        if (request()->to) {
            $budgets = $budgets->whereDate('budget_date', '<=', Carbon::createFromDate(request()->to));
        }

        if (request()->search_user) {
            $user = $request->search_user;
            $budgets = $budgets->whereHas('user', function ($query) use ($user) {
                $query->where('name', 'like', "%{$user}%");
            });
        }

        if ($request->category_id != '') {
            $budgets->where('category_id', "{$request->category_id}");
        } else {
            $budgets = $budgets->get();
        }
        return DataTables::of($budgets)
            ->addColumn('checkboxes', function ($budget) {
                $action = '<input type="checkbox" name="pdr_checkbox[]" class="pdr_checkbox" value="' . $budget->id . '" />';
                return $action;
            })
            ->addColumn('budget_date', function ($budget) {
                return date('m/d/Y', strtotime($budget->budget_date));
            })
            ->addColumn('category_name', function ($budget) {
                if ($budget->category_id != null) {
                    $catname = $budget->category->name;
                } else {
                    $catname = "No Category";
                }
                return $catname;
            })
            ->addColumn('category_id', function ($budget) {
                if ($budget->category_id != null) {
                    $catId = $budget->category->id;
                } else {
                    $catId = "No vendor";
                }
                return $catId;
            })

            ->addColumn('user_name', function ($budget) {
                return $budget->user->name;
            })

            ->addColumn('current_value', function ($budget) {
                $amount = $budget->BudgetAmount($budget->id);
                return $amount;
            })

            ->addColumn('variance', function ($budget) {
                $var = $budget->BudgetAmount($budget->id) - $budget->target_budget_value;
                if ($var > 0) {
                    $variance = '<span style="color:  red; text-align:center;">$' . ($var) . '</span>';
                } else {
                    $variance = '<span style="color:  #00238C; text-align:center;">$' . abs($var) . '</span>';
                }
                return $variance;
            })

            ->rawColumns(['checkboxes', 'variance'])
            ->make(true);
    }
}
