<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Repositories\StatisticsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DashboardController extends Controller
{

    protected StatisticsRepository $statisticsRepository;

    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }


    public function index(Request $request): View
    {
        $dateFromRR = $request->input('date_from_rr', now()->firstOfMonth()->format('Y-m-d'));
        $dateToRR = $request->input('date_to_rr', now()->format('Y-m-d'));

        $dateFromFrequency = $request->input('date_from_session_frequency', now()->firstOfMonth()->format('Y-m-d'));
        $dateToFrequency = $request->input('date_to_session_frequency', now()->format('Y-m-d'));

        $dateFromChurn = $request->input('date_from_session_churn', now()->firstOfMonth()->format('Y-m-d'));
        $dateToChurn = $request->input('date_to_session_churn', now()->format('Y-m-d'));

        $statistics = $this->statisticsRepository->dashboardStatistics(
            $dateFromRR,
            $dateToRR,
            $dateFromFrequency,
            $dateToFrequency,
            $dateFromChurn,
            $dateToChurn
        );
        $chartLabels = $this->statisticsRepository->activeUsers()->pluck('date');
        $chartData = $this->statisticsRepository->activeUsers()->pluck('count');

        return view(
            'admin.dashboard',
            compact(
                'statistics',
                'dateFromRR',
                'dateToRR',
                'dateFromFrequency',
                'dateToFrequency',
                'dateFromChurn',
                'dateToChurn',
                'chartLabels',
                'chartData'
            )
        );
    }

    public function exportStatistics(Request $request)
    {
        $dateFromRR = $request->input('date_from', now()->firstOfMonth()->format('Y-m-d'));
        $dateToRR = $request->input('date_to', now()->format('Y-m-d'));

        $dateFromFrequency = $request->input('date_from_session_frequency', now()->firstOfMonth()->format('Y-m-d'));
        $dateToFrequency = $request->input('date_to_session_frequency', now()->format('Y-m-d'));

        $dateFromChurn = $request->input('date_from_session_churn', now()->firstOfMonth()->format('Y-m-d'));
        $dateToChurn = $request->input('date_to_session_churn', now()->format('Y-m-d'));

        try {
            $statistics = $this->statisticsRepository->dashboardStatistics(
                $dateFromRR,
                $dateToRR,
                $dateFromFrequency,
                $dateToFrequency,
                $dateFromChurn,
                $dateToChurn
            );

            if (empty($statistics)) {
                return response()->json(['message' => 'Нет данных для выбранного диапазона дат.'], 404);
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Метрика', 'Значение'];
            $columnLetters = ['A', 'B'];

            foreach ($headers as $index => $header) {
                $sheet->setCellValue($columnLetters[$index] . '1', $header);
                $sheet->getStyle($columnLetters[$index] . '1')->getFont()->setBold(true);
                $sheet->getStyle($columnLetters[$index] . '1')->getAlignment()->setHorizontal('center');
            }

            $row = 2;
            foreach ($statistics as $metric => $value) {
                if ($metric === 'user_journey_completion_rate') {
                    $sheet->setCellValue('A' . $row, 'Завершение пути пользователя');
                    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                    $row++;

                    foreach ($value as $journeyKey => $journeyValue) {
                        $translatedMetric = $this->translateMetricToRussian($journeyKey);
                        $sheet->setCellValue('A' . $row, $translatedMetric);
                        $sheet->setCellValue('B' . $row, $journeyValue . '%');
                        $row++;
                    }
                } else {
                    $translatedMetric = $this->translateMetricToRussian($metric);
                    $sheet->setCellValue('A' . $row, $translatedMetric);
                    $sheet->setCellValue('B' . $row, is_array($value) ? json_encode($value) : $value);
                    $row++;
                }
            }

            // Auto resize columns
            foreach ($columnLetters as $letter) {
                $sheet->getColumnDimension($letter)->setAutoSize(true);
            }


            $filename = 'статистика_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            $writer = new Xlsx($spreadsheet);
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Возникла ошибка при экспорте статистики. Попробуйте снова.');
        }
    }

    private function translateMetricToRussian($metric): string
    {
        $translations = [
            'dau' => 'Ежедневные активные пользователи (DAU)',
            'wau' => 'Еженедельные активные пользователи (WAU)',
            'mau' => 'Ежемесячные активные пользователи (MAU)',
            'average_session_length' => 'Средняя длительность сессии',
            'drop_off_point' => 'Ключевая точка оттока',
            'user_counts_by_menu_section' => 'Пользователи по разделам меню',
            'most_frequent_сountry' => 'Самая часто выбираемая страна',
            'most_frequent_city' => 'Самый часто выбираемый город',
            'user_count' => 'Количество пользователей',
            'retention_rate' => 'Удержание пользователей',
            'get_average_session_frequency' => 'Средняя частота сессий',
            'calculate_churn_rate' => 'Уровень оттока',
            'user_journey_completion_rate' => 'Процент пользователей, которые прошли весь путь от начала до конца (по пунктам)',

            // journey details
            'application' => 'Завершение подачи заявки',
            'promotionViewCount' => 'Просмотр акций',
            'useFullInformationViewCount' => 'Просмотр полезной информации',
            'hotelViewCount' => 'Просмотр отелей',
            'establishmentViewCount' => 'Просмотр заведений',
            'currencyViewCount' => 'Просмотр валютных данных',
            'entertainmentViewCount' => 'Просмотр развлечений'

        ];

        return $translations[$metric] ?? ucfirst(str_replace('_', ' ', $metric));
    }

}
