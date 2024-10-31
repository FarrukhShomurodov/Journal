<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\Currency;
use App\Models\DiseaseType;
use App\Models\Entertainment;
use App\Models\Establishment;
use App\Models\Hotel;
use App\Models\Promotion;
use App\Models\Specialization;
use App\Models\UsefulInformation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ViewedStatisticsController extends Controller
{
    public function index(): View
    {
        $countries = Country::query()->withCount('users')->paginate(10, 'name');
        $cities = City::query()->withCount('users')->paginate(10, 'name');
        $currencies = Currency::query()->withCount('views')->paginate(10, 'name');
        $categories = Category::query()->withCount('views')->paginate(10, 'name');
        $establishments = Establishment::query()->withCount('views')->paginate(10, 'name');
        $entertainments = Entertainment::query()->withCount('views')->paginate(10, 'name');
        $hotels = Hotel::query()->withCount('views')->paginate(10, 'name');
        $usefulInformations = UsefulInformation::query()->withCount('views')->paginate(10, 'name');
        $promotions = Promotion::query()->withCount('views')->paginate(10, 'name');
        $clinics = Clinic::query()->withCount('views')->paginate(10, 'name');
        $diseaseTypes = DiseaseType::query()->withCount('views')->paginate(10, 'name');
        $specializations = Specialization::query()->withCount('views')->paginate(10, 'name');

        return view(
            'admin.viewedStatistics.index',
            compact(
                'clinics',
                'categories',
                'cities',
                'countries',
                'currencies',
                'diseaseTypes',
                'entertainments',
                'establishments',
                'hotels',
                'promotions',
                'specializations',
                'usefulInformations',
            )
        );
    }

    public function exportStatistics(Request $request)
    {
        $countries = Country::query()->withCount('users')->get();
        $cities = City::query()->withCount('users')->get();
        $currencies = Currency::query()->withCount('views')->get();
        $categories = Category::query()->withCount('views')->get();
        $establishments = Establishment::query()->withCount('views')->get();
        $entertainments = Entertainment::query()->withCount('views')->get();
        $hotels = Hotel::query()->withCount('views')->get();
        $usefulInformations = UsefulInformation::query()->withCount('views')->get();
        $promotions = Promotion::query()->withCount('views')->get();
        $clinics = Clinic::query()->withCount('views')->get();
        $diseaseTypes = DiseaseType::query()->withCount('views')->get();
        $specializations = Specialization::query()->withCount('views')->get();

        $statistics = [
            'Страны' => $countries,
            'Города' => $cities,
            'Валюты' => $currencies,
            'Категории' => $categories,
            'Заведения' => $establishments,
            'Развлечения' => $entertainments,
            'Отели' => $hotels,
            'Полезная информация' => $usefulInformations,
            'Акции' => $promotions,
            'Клиники' => $clinics,
            'Типы заболеваний' => $diseaseTypes,
            'Специализации' => $specializations,
        ];

        if (empty($statistics)) {
            return redirect()->back()->withErrors('Возникла ошибка при экспорте статистики. Попробуйте снова.');
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Метрика', 'Название', 'Количество'];
            $columnLetters = ['A', 'B', 'C'];

            foreach ($headers as $index => $header) {
                $sheet->setCellValue($columnLetters[$index] . '1', $header);
                $sheet->getStyle($columnLetters[$index] . '1')->getFont()->setBold(true);
                $sheet->getStyle($columnLetters[$index] . '1')->getAlignment()->setHorizontal('center');
            }

            $row = 2;

            foreach ($statistics as $metric => $items) {
                foreach ($items as $item) {
                    $sheet->setCellValue('A' . $row, $metric);
                    $sheet->setCellValue(
                        'B' . $row,
                        $metric == 'Валюты' ? json_decode($item->name)->ru : $item->name['ru']
                    );
                    $sheet->setCellValue('C' . $row, $item->views_count ?? $item->users_count ?? 0);
                    $row++;
                }
            }

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
}
