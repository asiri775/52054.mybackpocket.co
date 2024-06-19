<div style="width:100%;">
    <div style="font-family:sans-serif; box-sizing: border-box;">
        <div
            style="width:100%; box-sizing: border-box; text-align:right; padding-bottom:15px; padding-top:20px; padding-right:10px; border-bottom:3px #808080 solid;">
            <img src="https://users.backpocket.ca/admin/logo.png" width="190px" height="48px" style="">
        </div> 
        <div class="ctitle" align="center" style="font-size:30px;">
            <h5><strong>Envelope Details Report</strong></h5>
        </div>
        <table>
            <tbody>
                <tr>
                    <td><strong>Envelope ID # </strong> &nbsp;&nbsp;&nbsp;</td>
                    <td>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $envelope->id }}</td>
                </tr>
                <tr>
                    <td><strong>Envelope Name</strong>   &nbsp;&nbsp;&nbsp;</td>
                    <td>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $envelope->name }}</td>
                </tr>
                <tr>
                    <td><strong>Envelope Date </strong>  &nbsp;&nbsp;&nbsp;</td>
                    <td width="200px">:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $envelope->envelope_date }}</td>
                </tr>
                <tr>
                    <td width="200px"><strong>Envelope Category</strong>  &nbsp;&nbsp;</td>
                    <td>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $categoryName }}</td>
                </tr>
            </tbody>
        </table>
        <div class="row" style="font-family:sans-serif; font-size:14px; margin-top:10px;">
            <table width="100%"  cellpadding="3" cellspacing="0" align="left" style="margin-top:30px; border:2px solid #000;">
                <tr>
                    <th  width="150" style="font-size:17px;text-align:center; background-color:#000000; color:#ffffff; border:1px solid #000;">
                        Transaction No
                    </th>
                    <th width="60"
                        style="text-align:center;   border:1px solid #000; color:#ffffff; background-color:#000000;">Method
                    </th>
                    <th width="60"
                        style="text-align:center;   border:1px solid #000; color:#ffffff; background-color:#000000;">Vendor
                    </th>
                    <th width="60" style="text-align:right;  border:1px solid #000; color:#ffffff; background-color:#000000;">
                        Amount
                    </th>
                </tr>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td style="text-align:left; border:1px solid #000;">{{ $transaction->transaction_no }}</td>
                        <td style="text-align:left; border:1px solid #000;">{{ $transaction->payment_method }}</td>
                        <td style="text-align:left; border:1px solid #000;">{{ $transaction->vendor->name }}</td>
                        <td style=" border:1px solid #000; vertical-align:middle;"><span
                                style="float:left;"></span><span style="float:right;">$ {{ $transaction->total }}</span>
                        </td>
                    </tr>
                @endforeach
                <tr>  
                    <td colspan="3" width="150"
                        style="font-size:18px;text-align:right; background-color:#ffffff; color:#000; border:1px solid #000;">
                        <b>Total</b> </td>
                    <td width="60"
                        style="font-size:22px;text-align:right; background-color:#ffffff; color:#000; border:1px solid #000;">
                        <b>$ {{$grandTotal}}</b></td>
                </tr>
                <tr>
                    <td colspan="3" width="150"
                        style="font-size:18px;text-align:right; background-color:#ffffff; color:#000; border:1px solid #000;">
                        <b>No. of Items</b> </td>
                    <td  width="60"
                        style="font-size:22px;text-align:right; background-color:#ffffff; color:#000; border:1px solid #000;">
                        <b>{{ $count }}</b></td>
                </tr>


            </table>
        </div>


    </div>
</div>
