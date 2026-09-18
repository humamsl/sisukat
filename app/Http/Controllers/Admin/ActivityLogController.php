<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $logs = $this->filteredQuery()
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'users' => User::query()->orderBy('name')->get(['id', 'name', 'role']),
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $logs = $this->filteredQuery()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Log Aktivitas');

        $sheet->fromArray(['Waktu', 'User', 'Role', 'Aksi', 'Deskripsi', 'IP Address'], null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($logs as $log) {
            $sheet->fromArray([
                $log->created_at->translatedFormat('d M Y H:i'),
                $log->user->name ?? 'Sistem',
                $log->user->role ?? '-',
                ucfirst($log->action),
                $log->description,
                $log->ip_address,
            ], null, "A{$row}");
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'log-aktivitas-'.now()->format('Y-m-d_His').'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportWord(): BinaryFileResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $logs = $this->filteredQuery()->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $section->addText('Laporan Log Aktivitas SISUKAT', ['bold' => true, 'size' => 16]);
        $section->addText('Dicetak: '.now()->translatedFormat('d M Y H:i'), ['size' => 9, 'color' => '666666']);
        $section->addTextBreak(1);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
        $headerStyle = ['bgColor' => 'F3F4F6'];
        $headerFont = ['bold' => true, 'size' => 9];
        $cellFont = ['size' => 9];

        $table->addRow();
        foreach (['Waktu', 'User', 'Role', 'Aksi', 'Deskripsi', 'IP Address'] as $header) {
            $table->addCell(2200, $headerStyle)->addText($header, $headerFont);
        }

        foreach ($logs as $log) {
            $table->addRow();
            $table->addCell(2200)->addText($log->created_at->translatedFormat('d M Y H:i'), $cellFont);
            $table->addCell(2200)->addText($log->user->name ?? 'Sistem', $cellFont);
            $table->addCell(1500)->addText($log->user->role ?? '-', $cellFont);
            $table->addCell(1500)->addText(ucfirst($log->action), $cellFont);
            $table->addCell(4500)->addText($log->description ?? '-', $cellFont);
            $table->addCell(1800)->addText($log->ip_address ?? '-', $cellFont);
        }

        $filename = 'log-aktivitas-'.now()->format('Y-m-d_His').'.docx';
        $tempPath = tempnam(sys_get_temp_dir(), 'sisukat_log_');

        $phpWord->save($tempPath, 'Word2007');

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function filteredQuery(): Builder
    {
        $userFilter = request('user_filter');

        return ActivityLog::query()
            ->with('user')
            ->when(request('action'), fn ($q) => $q->where('action', request('action')))
            ->when($userFilter === 'exclude_admin', fn ($q) => $q->whereHas(
                'user', fn ($q2) => $q2->where('role', User::ROLE_USER)
            ))
            ->when($userFilter && $userFilter !== 'exclude_admin', fn ($q) => $q->where('user_id', $userFilter))
            ->latest('id');
    }
}
