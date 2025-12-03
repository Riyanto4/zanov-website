<?php

namespace App\Helpers;

class ChartHelper
{
    public static function getStatusColor($status)
    {
        $colors = [
            'PENDING' => '#FFB020',
            'PAID' => '#14B8A6',
            'CANCELED' => '#FF5630',
            'REFUNDED' => '#6554C0'
        ];

        return $colors[$status] ?? '#94A3B8';
    }
}
