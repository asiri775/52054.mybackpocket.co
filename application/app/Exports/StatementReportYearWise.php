<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Transaction;
use Generator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatementReportYearWise implements FromGenerator, WithTitle
{
    use Exportable;
    public $fromYear;
    public $toYear;

    public function __construct($fromYear, $toYear)
    {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
    }

    public function title(): string
    {
        return 'Trx Cat Yr by Yr';
    }

    public function generator(): Generator
    {
        $sheet1Data = [];
        $categories = [];

        for ($i = $this->fromYear; $i <= $this->toYear; $i++) {
            $sheet1Data[$i] = ['total' => 0, 'categories' => []];
        }

        $accountCategories = Category::where('type', 'accounting')->where('role', 'main')->get();
        foreach ($accountCategories as $account) {
            $categories[] = [
                'id' => $account->id,
                'name' => "#".$account->id." - ".$account->name
            ];
            foreach (Category::where('role', 'sub')->where('mainid', $account->id)->orderBy('name', 'ASC')->get() as $subCategory) {
                $categories[] = [
                    'id' => $subCategory->id,
                    'name' => "#".$subCategory->id." - ".$subCategory->name
                ];
                foreach (Category::where('role', 'child')->where('mainid', $subCategory->id)->orderBy('name', 'ASC')->get() as $childCategory) {
                    $categories[] = [
                        'id' => $childCategory->id,
                        'name' => "#".$childCategory->id." - ".$childCategory->name
                    ];
                }
            }
        }

        foreach ($sheet1Data as $year => $values) {
            $totalS = 0;
            $startYear = $year . '-01-01 00:00:00';
            $endYear = $year . '-12-31 23:59:59';
            foreach ($categories as $category) {

                $totalBal = 0;

                $transactions = Transaction::where('category_id', $category['id'])
                    ->where('transaction_date', '>=', $startYear)
                    ->where('transaction_date', '<=', $endYear)
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
                            $totalBal += $amount;
                        } else {
                            $totalBal -= $amount;
                        }
                    }
                }

                // echo $year.'   ==>       '.$totalS . "   ==>   ".$totalBal."    ==>  ".$category['name']."  </br>";

                $totalS += $totalBal;
                $sheet1Data[$year]['categories'][$category['name']] = $totalBal;
            }

            $sheet1Data[$year]['total'] = $totalS;

            // echo '</br></br></br>'.$year.'   ==>       '.$totalS . "</br></br></br></br></br>";
        } 

        // die;

        $finalData = [
            'years' => [],
            'categories' => [],
            'totals' => [],
            'emptyRows' => []
        ];

        foreach ($sheet1Data as $year => $values) {
            $finalData['years'][$year] = $year;
            $finalData['emptyRows'][$year] = '';
            $total = $values['total'];
            foreach ($values['categories'] as $category => $catVal) {
                $finalData['categories'][$category][$year] = (string) $catVal;
            }
            $finalData['totals'][$year] = $total;
        }

        yield ['Transactions By Category', '', ...$finalData['emptyRows']];
        yield ['Year  by Year Comparison', '', ...$finalData['emptyRows']];

        yield ['', '', ...$finalData['emptyRows']];
        yield ['', '', ...$finalData['emptyRows']];

        yield ['CATEGORY', '', ...$finalData['years']];

        foreach ($finalData['categories'] as $category => $values) {
            yield [$category, '', ...$values];
        }

        yield ['', '', ...$finalData['emptyRows']];

        yield ['Totals', '', ...$finalData['totals']];

    }


}
