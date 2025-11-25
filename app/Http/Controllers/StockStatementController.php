<?php

namespace App\Http\Controllers;

use App\Models\StockStatement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockStatementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockStatement::with(['product'])
            ->orderBy('created_at', 'desc');

        // Filter by product
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $stockStatements = $query->paginate(20);
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.stock-statement.index', compact('stockStatements', 'products'));
    }
}
