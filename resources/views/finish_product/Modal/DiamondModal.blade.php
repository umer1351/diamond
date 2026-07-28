<div class="modal fade" id="diamondCaratModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Diamond Detail</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="diamondCaratForm" name="diamondCaratForm" class="form-horizontal">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Diamond QTY:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="diamonds" name="diamonds"
                                    placeholder="Enter diamond" onkeypress="return isNumberKey(event)" maxlength="10"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Type:<span style="color:red;">*</span></label>
                                <select name="diamond_type" class="form-control" id="diamond_type" required>
                                    <option value="" selected disabled>--Select Type--</option>
                                    @foreach ($diamond_types as $item)
                                        <option value="{{ $item->name??'' }}">{{ $item->name??'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Cut:<span style="color:red;">*</span></label>
                                <select name="cut" class="form-control" id="cut" required>
                                    <option value="" selected disabled>--Select Cut--</option>
                                    @foreach ($diamond_cuts as $item)
                                        <option value="{{ $item->name??'' }}">{{ $item->name??'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Color:<span style="color:red;">*</span></label>
                                <select name="color" class="form-control" id="color" required>
                                    <option value="" selected disabled>--Select Color--</option>
                                    @foreach ($diamond_colors as $item)
                                    <option value="{{ $item->name??'' }}">{{ $item->name??'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Clarity:<span style="color:red;">*</span></label>
                                <select name="clarity" class="form-control" id="clarity" required>
                                    <option value="" selected disabled>--Select Clarity--</option>
                                    @foreach ($diamond_clarities as $item)
                                    <option value="{{ $item->name??'' }}">{{ $item->name??'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Carat:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="carat" name="carat"
                                    placeholder="Enter diamond carat" value="0" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Rate/Carat:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="carat_rate" name="carat_rate"
                                    placeholder="Enter diamond carat rate" value="0" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Total PKR:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="diamond_total" name="diamond_total"
                                    placeholder="Enter diamond total" value="0" onkeypress="return isNumberKey(event)" maxlength="10"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-4">
                                <a class="btn btn-primary waves-effect text-center text-white" style="width:100%;"
                                            onclick="addDiamond()">
                                            <i class="fa fa-plus"></i> Add
                                        </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="diamondsTable">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr.</th> -->
                                        <th>Diamond QTY</th>
                                        <th>Type</th>
                                        <th>Cut</th>
                                        <th>Color</th>
                                        <th>Clarity</th>
                                        <th>Carat</th>
                                        <th>Rate/Carat</th>
                                        <th>Total(PKR)</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
