@extends('layouts.main')

@section('content')
<!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zanov-dark/60">Total Penjualan Hari Ini</p>
                                <p class="mt-1 text-2xl font-semibold text-zanov-orange">142</p>
                            </div>
                            <div class="p-3 rounded-full bg-zanov-orange/10">
                                <i data-feather="shopping-cart" class="text-zanov-orange"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-zanov-dark/60">+12% dari kemarin</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zanov-dark/60">Pendapatan Bulanan</p>
                                <p class="mt-1 text-2xl font-semibold text-zanov-orange">Rp 342.5jt</p>
                            </div>
                            <div class="p-3 rounded-full bg-zanov-orange/10">
                                <i data-feather="dollar-sign" class="text-zanov-orange"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-zanov-dark/60">+8% dari bulan lalu</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zanov-dark/60">Produk Terlaris</p>
                                <p class="mt-1 text-2xl font-semibold text-zanov-orange">Z-5000</p>
                            </div>
                            <div class="p-3 rounded-full bg-zanov-orange/10">
                                <i data-feather="award" class="text-zanov-orange"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-zanov-dark/60">Terjual 89 unit</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zanov-dark/60">Stok Hampir Habis</p>
                                <p class="mt-1 text-2xl font-semibold text-zanov-orange">5</p>
                            </div>
                            <div class="p-3 rounded-full bg-zanov-orange/10">
                                <i data-feather="alert-circle" class="text-zanov-orange"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-zanov-dark/60">Produk perlu restock</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-zanov-dark">Tren Penjualan Harian</h2>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs rounded-full bg-zanov-orange text-white">7 Hari</button>
                                <button class="px-3 py-1 text-xs rounded-full bg-zanov-gray text-zanov-dark">30 Hari</button>
                            </div>
                        </div>
                        <div class="h-64 bg-zanov-gray/50 rounded flex items-center justify-center">
                            <p class="text-zanov-dark/40">Line Chart Area</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-zanov-dark">Penjualan per Produk</h2>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs rounded-full bg-zanov-orange text-white">Bulan Ini</button>
                                <button class="px-3 py-1 text-xs rounded-full bg-zanov-gray text-zanov-dark">Tahun Ini</button>
                            </div>
                        </div>
                        <div class="h-64 bg-zanov-gray/50 rounded flex items-center justify-center">
                            <p class="text-zanov-dark/40">Bar Chart Area</p>
                        </div>
                    </div>
                </div>
@endsection