<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin or manager
        if (!in_array($request->user()->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Daily Sales (last 30 days for charts)
        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Total Revenue
        $totalRevenue = Order::sum('total_price');

        // Total Orders
        $totalOrders = Order::count();

        // Total Products
        $totalProducts = Product::count();

        // Low Stock Products (quantity < 10)
        $lowStockCount = Product::where('quantity', '<', 10)->count();

        // Top Selling Products (Top 5) - Using OrderItems
        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * price) as total_revenue')
        )
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product:id,name,sku')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Unknown',
                    'sku' => $item->product->sku ?? 'N/A',
                    'quantity_sold' => $item->total_quantity,
                    'revenue' => number_format($item->total_revenue, 2),
                ];
            });

        // Monthly revenue summary (last 12 months)
        $monthlyRevenue = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(total_price) as total'),
            DB::raw('COUNT(*) as order_count')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Recent Orders (last 10)
        $recentOrders = Order::with(['user:id,name,email', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->name ?? 'Guest',
                    'email' => $order->user->email ?? 'N/A',
                    'items_count' => $order->items->count(),
                    'total' => number_format($order->total_price, 2),
                    'status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d H:i'),
                ];
            });

        // Current month vs previous month comparison
        $currentMonthRevenue = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $previousMonthRevenue = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_price');

        $revenueGrowth = $previousMonthRevenue > 0
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
            : 0;

        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'daily_sales' => $dailySales,
            'monthly_revenue' => $monthlyRevenue,
            'total_revenue' => number_format($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'total_products' => $totalProducts,
            'low_stock_count' => $lowStockCount,
            'top_products' => $topProducts,
            'recent_orders' => $recentOrders,
            'orders_by_status' => $ordersByStatus,
            'current_month_revenue' => number_format($currentMonthRevenue, 2),
            'previous_month_revenue' => number_format($previousMonthRevenue, 2),
            'revenue_growth' => number_format($revenueGrowth, 2),
        ]);
    }
}
