<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Transaction;
use Generator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatementReportDateWise implements FromGenerator, WithTitle
{
    use Exportable;
    public $categoryId;
    public $from;
    public $to;

    public function __construct($categoryId, $from, $to)
    {
        $this->categoryId = $categoryId;
        $this->from = $from;
        $this->to = $to;
    }

    public function title(): string
    {
        return 'Trx Cat Date Wise';
    }

    public function generator(): Generator
    {
        $sheet1Data = [];
        $categories = [];

        $category = Category::where('id', $this->categoryId)->first();
        if ($category != null) {

            $startDate = date('Y-m-d', strtotime($this->from)) . " 00:00:01";
            $endDate = date('Y-m-d', strtotime($this->to)) . " 23:59:59";

            yield ['Category Transaction By Date'];
            yield [''];
            yield ['CATEGORY:', $category->name];
            yield ['Time:', Helper::displayDate($startDate), Helper::displayDate($endDate)];

            yield [''];
            yield ['DATE', 'PARTICULARS', 'AMOUNT'];

            $totals = 0;

            $transactions = Transaction::where('category_id', $category->id)
                ->where('transaction_date', '>=', $startDate)->where('transaction_date', '<=', $endDate)
                ->orderBy('transaction_date', 'DESC')
                ->get();

            if ($transactions != null) {
                foreach ($transactions as $transaction) {
                    //debit subtract, credit plus

                    $amount = (string) $transaction->total;
                    $isDebit = $transaction->is_debit;

                    if (substr($amount, 0, 1) === '-') {
                        $isDebit = 1;
                        $amount = substr($amount, 1);
                    }

                    if ($isDebit) {
                        $totals += $amount;
                    } else {
                        $totals -= $amount;
                        $amount = '-' . $amount;
                    }

                    yield [Helper::displayDate($transaction->transaction_date), $transaction->particular, $amount];
                }
            }

            yield ['', 'Totals', $totals];

        }

    }


}
