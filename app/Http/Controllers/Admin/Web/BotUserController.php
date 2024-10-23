<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BotUserController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $query = BotUser::query()->orderBy('id', 'asc');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $botUsers = $query->get();
        return view('admin.users.bot-users', compact('botUsers', 'dateFrom', 'dateTo'));
    }

    public function exportStatistics(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $query = BotUser::query()->orderBy('id', 'asc');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        try {
            $botUsers = $query->get();
            $count = 1;

            foreach ($botUsers as $user) {
                $statistics[] = [
                    'id' => $count++,
                    'chat_id' => $user->chat_id ?? '-',
                    'first_name' => $user->first_name ?? '-',
                    'second_name' => $user->second_name ?? '-',
                    'uname' => $user->uname ?? '-',
                    'phone' => $user->phone ?? '-',
                    'step' => $user->step ?? '-',
                    'lang' => $user->lang ?? '-',
                    'isactive' => $user->isactive ?? '-',
                    'country_id' => $user->country_id ?? '-',
                    'city_id' => $user->city_id ?? '-',
                    'created_at' => $user->created_at ?? '-',
                    'last_activity' => $user->last_activity ?? '-',
                ];
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = [
                'Id',
                'Chat id',
                'Имя',
                'Фамилия',
                'Имя пользователя',
                'Телефон',
                'Шаг',
                'Язык',
                'Активный',
                'Страна',
                'Город',
                'Первое посешение',
                'Последнее посещение',
            ];

            $columnLetters = range('A', 'M');
            foreach ($columnLetters as $index => $letter) {
                $sheet->setCellValue($letter . '1', $headers[$index]);
                $sheet->getStyle($letter . '1')->getFont()->setBold(true);
            }

            $row = 2;
            foreach ($statistics as $data) {
                $sheet->setCellValue('A' . $row, $data['id']);
                $sheet->setCellValue('B' . $row, $data['chat_id']);
                $sheet->setCellValue('C' . $row, $data['first_name']);
                $sheet->setCellValue('D' . $row, $data['second_name']);
                $sheet->setCellValue('E' . $row, $data['uname']);
                $sheet->setCellValue('F' . $row, $data['phone']);
                $sheet->setCellValue('G' . $row, $data['step']);
                $sheet->setCellValue('H' . $row, $data['lang']);
                $sheet->setCellValue('I' . $row, $data['isactive']);
                $sheet->setCellValue('J' . $row, $data['country_id']);
                $sheet->setCellValue('K' . $row, $data['city_id']);
                $sheet->setCellValue('L' . $row, $data['created_at']);
                $sheet->setCellValue('M' . $row, $data['last_activity']);
                $row++;
            }

            // Auto resize columns
            foreach ($columnLetters as $letter) {
                $sheet->getColumnDimension($letter)->setAutoSize(true);
            }

            $filename = 'пользователи_бота_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            $writer = new Xlsx($spreadsheet);
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Возникла ошибка при экспорте статистики. Попробуйте снова.');
        }
    }

    public function showJourney(BotUser $user): View
    {
        $journeys = $user->journey;
        return view('admin.users.journey', compact('journeys'));
    }
}
