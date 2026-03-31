<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportsOverviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            // البطاقات العلوية
            'analytics_cards' => [
                'total_sales' => new ReportCardResource([
                    'title' => 'إجمالي المبيعات',
                    'value' => $this['total_sales']['current'],
                    'growth' => $this['total_sales']['growth'],
                    'is_currency' => true
                ]),
                'net_income' => new ReportCardResource([
                    'title' => 'صافي الدخل',
                    'value' => $this['net_income']['current'],
                    'growth' => $this['net_income']['growth'],
                    'is_currency' => true
                ]),
                'customers' => new ReportCardResource([
                    'title' => 'عملاء جدد',
                    'value' => $this['customers']['current'],
                    'growth' => $this['customers']['growth'],
                    'is_currency' => false
                ]),
            ],

            // بيانات الرسم البياني (نمو الإيرادات)
            'revenue_chart' => PerformanceChartResource::collection($this['chart_data']),


        ];
    }
}
