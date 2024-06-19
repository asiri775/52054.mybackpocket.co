<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\TransactionByCategory;
use Illuminate\Support\Facades\DB;

class StatementReportExport implements WithMultipleSheets
{
    public $fromYear;
    public $toYear;
    public $categoryId;
    public $from;
    public $to;

    public function __construct($fromYear, $toYear, $categoryId, $from, $to)
    {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
        $this->categoryId = $categoryId;
        $this->from = $from;
        $this->to = $to;
    }

    public function sheets(): array
    {
        return [
            new StatementReportYearWise($this->fromYear, $this->toYear),
            new StatementReportDateWise($this->categoryId, $this->from, $this->to),
        ];
    }


}
