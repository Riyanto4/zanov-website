<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Rating;
use Carbon\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DashboardController extends Controller
{
    protected $chart;
    
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        
        // 1. Total Penjualan Hari Ini
        $todaySales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'PAID')
            ->count();
            
        $yesterdaySales = Transaction::whereDate('created_at', $yesterday)
            ->where('payment_status', 'PAID')
            ->count();
            
        $salesPercentageChange = $yesterdaySales > 0 
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 2)
            : ($todaySales > 0 ? 100 : 0);
            
        // 2. Pendapatan Bulanan
        $currentMonthRevenue = Transaction::whereBetween('created_at', [$startOfMonth, now()])
            ->where('payment_status', 'PAID')
            ->sum('total_amount');
            
        $lastMonthRevenue = Transaction::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->where('payment_status', 'PAID')
            ->sum('total_amount');
            
        $revenuePercentageChange = $lastMonthRevenue > 0
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : ($currentMonthRevenue > 0 ? 100 : 0);
            
        // 3. Produk Terlaris (Top 5 untuk list bar)
        $bestSellingProducts = TransactionItem::select('products.id', 'products.name', 'products.photo')
            ->selectRaw('SUM(transaction_items.quantity) as total_quantity')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereHas('transaction', function($query) {
                $query->where('payment_status', 'PAID');
            })
            ->groupBy('products.id', 'products.name', 'products.photo')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        $bestSellingProduct = $bestSellingProducts->first();
            
        // 4. Stok Hampir Habis
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->where('is_active', 1)
            ->count();
            
        // 5. Data untuk Grafik Tren Penjualan (7 hari terakhir)
        $salesTrendLabels = [];
        $salesTrendData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesCount = Transaction::whereDate('created_at', $date)
                ->where('payment_status', 'PAID')
                ->count();
                
            $salesTrendLabels[] = $date->format('d M');
            $salesTrendData[] = $salesCount;
        }
        
        $salesTrendChart = $this->chart->lineChart()
            ->setTitle('Tren Penjualan 7 Hari Terakhir')
            ->setSubtitle('Jumlah transaksi per hari')
            ->addData('Penjualan', $salesTrendData)
            ->setXAxis($salesTrendLabels)
            ->setColors(['#f97316'])
            ->setHeight(300);
            
        // 6. Data untuk Grafik Penjualan per Status
        $statusData = Transaction::select('payment_status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('payment_status')
            ->get();
            
        $statusLabels = $statusData->pluck('payment_status')->toArray();
        $statusCounts = $statusData->pluck('count')->toArray();
        
        $statusChart = $this->chart->donutChart()
            ->setTitle('Distribusi Status Transaksi')
            ->setSubtitle('Total semua transaksi')
            ->addData($statusCounts)
            ->setLabels($statusLabels)
            ->setColors(['#f97316', '#22c55e', '#ef4444', '#6b7280'])
            ->setHeight(300);
            
        // 7. Data untuk Chart Rating Terbaik (Top 5)
        $bestRatedProducts = Rating::select('product_id', 'products.name', 'products.photo')
            ->selectRaw('AVG(rating) as average_rating')
            ->selectRaw('COUNT(*) as total_ratings')
            ->join('products', 'ratings.product_id', '=', 'products.id')
            ->where('products.is_active', 1)
            ->groupBy('product_id', 'products.name', 'products.photo')
            ->havingRaw('COUNT(*) >= 1') // Minimal 3 rating untuk validitas
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->limit(5)
            ->get();
            
        $bestRatingLabels = $bestRatedProducts->pluck('name')->toArray();
        $bestRatingData = $bestRatedProducts->pluck('average_rating')->map(function($rating) {
            return round($rating, 1);
        })->toArray();
        
        $bestRatingChart = $this->chart->barChart()
            ->setTitle('Top 5 Produk dengan Rating Tertinggi')
            ->setSubtitle('Rata-rata rating (min. 3 ulasan)')
            ->addData('Rating', $bestRatingData)
            ->setXAxis($bestRatingLabels)
            ->setColors(['#22c55e'])
            ->setHeight(300);
            
        // 8. Data untuk Chart Rating Terburuk (Bottom 5)
        $worstRatedProducts = Rating::select('product_id', 'products.name', 'products.photo')
            ->selectRaw('AVG(rating) as average_rating')
            ->selectRaw('COUNT(*) as total_ratings')
            ->join('products', 'ratings.product_id', '=', 'products.id')
            ->where('products.is_active', 1)
            ->groupBy('product_id', 'products.name', 'products.photo')
            ->havingRaw('COUNT(*) >= 1') // Minimal 3 rating untuk validitas
            ->orderBy('average_rating', 'asc')
            ->orderBy('total_ratings', 'desc')
            ->limit(5)
            ->get();
            
        $worstRatingLabels = $worstRatedProducts->pluck('name')->toArray();
        $worstRatingData = $worstRatedProducts->pluck('average_rating')->map(function($rating) {
            return round($rating, 1);
        })->toArray();
        
        $worstRatingChart = $this->chart->barChart()
            ->setTitle('5 Produk dengan Rating Terendah')
            ->setSubtitle('Rata-rata rating (min. 3 ulasan)')
            ->addData('Rating', $worstRatingData)
            ->setXAxis($worstRatingLabels)
            ->setColors(['#ef4444'])
            ->setHeight(300);
            
        // 9. Data untuk Grafik Pendapatan Bulanan (6 bulan terakhir)
        $revenueLabels = [];
        $revenueData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $revenue = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('payment_status', 'PAID')
                ->sum('total_amount');
                
            $revenueLabels[] = $month->format('M Y');
            $revenueData[] = $revenue;
        }
        
        $revenueChart = $this->chart->areaChart()
            ->setTitle('Pendapatan 6 Bulan Terakhir')
            ->setSubtitle('Dalam Rupiah')
            ->addData('Pendapatan', $revenueData)
            ->setXAxis($revenueLabels)
            ->setColors(['#f97316'])
            ->setHeight(300);
            
        return view('admin.dashboard.index', compact(
            'todaySales',
            'salesPercentageChange',
            'currentMonthRevenue',
            'revenuePercentageChange',
            'bestSellingProduct',
            'bestSellingProducts',
            'lowStockProducts',
            'salesTrendChart',
            'statusChart',
            'bestRatingChart',
            'worstRatingChart',
            'revenueChart'
        ));
    }
}