<form action="{{ route('transactions.save') }}" method="post" class="form-control">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ $id }}">

    <div class="row">

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="vendor_id" class="col-md-12">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control vendorSearch col-md-12">
                        <?php
                        foreach($vendors as $vendor){
                            ?>
                        <option value="<?= $vendor->id ?>" selected><?= $vendor->name ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('vendor_id'))
                            <span class="help-block" style="color: red">{!! $errors->first('vendor_id') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="transaction_no" class="col-md-12">Transaction No</label>
                    <input type="text" id="transaction_no" name="transaction_no" class="form-control col-md-12"
                        value="{{ old('transaction_no', $model->transaction_no) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('transaction_no'))
                            <span class="help-block" style="color: red">{!! $errors->first('transaction_no') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="order_no" class="col-md-12">Order No</label>
                    <input type="text" id="order_no" name="order_no" class="form-control col-md-12"
                        value="{{ old('order_no', $model->order_no) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('order_no'))
                            <span class="help-block" style="color: red">{!! $errors->first('order_no') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <div class="row">

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="transaction_date" class="col-md-12">Transaction Date</label>
                    <input type="date" id="transaction_date" name="transaction_date" class="form-control col-md-12"
                        value="{{ old('transaction_date', $model->transaction_date) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('transaction_date'))
                            <span class="help-block" style="color: red">{!! $errors->first('transaction_date') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="transaction_time" class="col-md-12">Transaction Time</label>
                    <input type="time" id="transaction_time" name="transaction_time" class="form-control col-md-12"
                        value="{{ old('transaction_time', $model->transaction_time) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('transaction_time'))
                            <span class="help-block" style="color: red">{!! $errors->first('transaction_time') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="payment_method" class="col-md-12">Payment Method</label>
                    <input type="text" id="payment_method" name="payment_method" class="form-control col-md-12"
                        value="{{ old('payment_method', $model->payment_method) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('payment_method'))
                            <span class="help-block" style="color: red">{!! $errors->first('payment_method') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="payment_ref" class="col-md-12">Payment Reference</label>
                    <input type="text" id="payment_ref" name="payment_ref" class="form-control col-md-12"
                        value="{{ old('payment_ref', $model->payment_ref) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('payment_ref'))
                            <span class="help-block" style="color: red">{!! $errors->first('payment_ref') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <div class="row">
        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="auth_id" class="col-md-12">Auth ID</label>
                    <input type="text" id="auth_id" name="auth_id" class="form-control col-md-12"
                        value="{{ old('auth_id', $model->auth_id) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('auth_id'))
                            <span class="help-block" style="color: red">{!! $errors->first('auth_id') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="terminal_no" class="col-md-12">Terminal No</label>
                    <input type="text" id="terminal_no" name="terminal_no" class="form-control col-md-12"
                        value="{{ old('terminal_no', $model->terminal_no) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('terminal_no'))
                            <span class="help-block" style="color: red">{!! $errors->first('terminal_no') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="operator_id" class="col-md-12">Operator ID</label>
                    <input type="text" id="operator_id" name="operator_id" class="form-control col-md-12"
                        value="{{ old('operator_id', $model->operator_id) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('operator_id'))
                            <span class="help-block" style="color: red">{!! $errors->first('operator_id') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="register_no" class="col-md-12">Register No</label>
                    <input type="text" id="register_no" name="register_no" class="form-control col-md-12"
                        value="{{ old('register_no', $model->register_no) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('register_no'))
                            <span class="help-block" style="color: red">{!! $errors->first('register_no') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="bar_qr_code" class="col-md-12">Baq QR Code</label>
                    <input type="text" id="bar_qr_code" name="bar_qr_code" class="form-control col-md-12"
                        value="{{ old('bar_qr_code', $model->bar_qr_code) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('bar_qr_code'))
                            <span class="help-block" style="color: red">{!! $errors->first('bar_qr_code') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="employee_no" class="col-md-12">Employee No</label>
                    <input type="text" id="employee_no" name="employee_no" class="form-control col-md-12"
                        value="{{ old('employee_no', $model->employee_no) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('employee_no'))
                            <span class="help-block" style="color: red">{!! $errors->first('employee_no') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <h5>Purchases</h5>

            <div id="purchases-box">

                <?php
                $lastPurchases = json_decode(json_encode(old('purchases', $purchases)), true);
                // dd($lastPurchases);
                if ($lastPurchases != null) {
                    foreach ($lastPurchases as $lastPurchase) {
                        ?>

                <div class="row purchase-row">

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12">Product</label>
                                <select data-name="product_id" class="form-control purchase-items purchaseProduct col-md-12">
                                    <?php
                                    if(array_key_exists('product_id', $lastPurchase)){
                        $product = \App\Models\Product::where('id', $lastPurchase['product_id'])->first();
                        if($product!=null){
                            ?>
                                    <option value="<?= $product->id ?>" selected><?= $product->name ?></option>
                                    <?php
                        }
                    }
                        ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12">Quantity</label>
                                <input value="{{ $lastPurchase['quantity'] }}" type="text"
                                    data-name="quantity" class="form-control purchase-items purchase-quantity col-md-12">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12">Price</label>
                                <input value="{{ $lastPurchase['price'] }}" type="text" data-name="price"
                                    class="form-control purchase-price purchase-items col-md-12">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12">&nbsp;</label>
                                <button class="btn btn-danger w-100 removePurchase">Remove</button>
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                    }
                }
                ?>

            </div>

            <div class="card-action-btns">
                <button type="button" id="addMorePurchases" class="btn btn-primary">Add More</button>
            </div>

        </div>
    </div>

    <hr>

    <div class="row">

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="sub_total" class="col-md-12">Sub Total</label>
                    <input type="text" id="sub_total" name="sub_total" class="form-control col-md-12"
                        value="{{ old('sub_total', $model->sub_total) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('sub_total'))
                            <span class="help-block" style="color: red">{!! $errors->first('sub_total') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="tax_amount" class="col-md-12">Tax</label>
                    <input type="text" id="tax_amount" name="tax_amount" class="form-control col-md-12"
                        value="{{ old('tax_amount', $model->tax_amount) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('tax_amount'))
                            <span class="help-block" style="color: red">{!! $errors->first('tax_amount') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="form-group">
                <div class="row">
                    <label for="total" class="col-md-12">Total</label>
                    <input type="text" id="total" name="total" class="form-control col-md-12"
                        value="{{ old('total', $model->total) }}">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->has('total'))
                            <span class="help-block" style="color: red">{!! $errors->first('total') !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group row">
        <button type="submit" class="btn btn-complete" style="width: 200px;">Save</button>
    </div>
</form>


<div id="purchases-box-clone" class="d-none">

    <div class="row purchase-row">

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-12">Product</label>
                    <select data-name="product_id" class="form-control purchase-items purchaseProductTemp col-md-12">
                        <option value="">Search Product</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-12">Quantity</label>
                    <input type="text" data-name="quantity" class="form-control purchase-items col-md-12">
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-12">Price</label>
                    <input type="text" data-name="price" class="form-control purchase-items col-md-12">
                </div>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-12">&nbsp;</label>
                    <button class="btn btn-danger w-100 removePurchase">Remove</button>
                </div>
            </div>
        </div>

    </div>

</div>
