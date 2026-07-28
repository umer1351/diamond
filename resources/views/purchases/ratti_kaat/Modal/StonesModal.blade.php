<div class="modal fade" id="stoneWeightModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Stone Weight</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <!-- <form id="stoneWeightForm" name="stoneWeightForm" class="form-horizontal"> -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Category:<span style="color:red;">*</span></label>
                                <select class="form-control" name="category" id="category" required>
                                    <option value="" selected disabled>--Select Category--</option>
                                    @foreach ($stone_categories as $item)
                                        <option value="{{ $item->name ?? '' }}">{{ $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Type:<span style="color:red;">*</span></label>

                                <input type="text" class="form-control" id="stone_type" name="stone_type"
                                    placeholder="Enter type" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Stone QTY:<span style="color:red;">*</span></label>

                                <input type="text" class="form-control" id="stones" name="stones"
                                    placeholder="Enter stones" onkeypress="return isNumberKey(event)" maxlength="10"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Gram:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="stone_gram" name="stone_gram"
                                    placeholder="Enter stone gram" onkeypress="return isNumberKey(event)" maxlength="10"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Carat:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="stone_carat" name="stone_carat"
                                    placeholder="Enter stone carat" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Rate/Gram:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="stone_gram_rate" name="stone_gram_rate"
                                    placeholder="Enter stone gram rate" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Rate/Carat:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="stone_carat_rate" name="stone_carat_rate"
                                    placeholder="Enter stone carat rate" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Total PKR:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="stone_total" name="stone_total"
                                    placeholder="Enter stone total" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-4">
                                <button onclick="addStoneProduct()" style="width: 100%;" class="btn btn-primary"
                                    value="create">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr.</th> -->
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Stone QTY</th>
                                        <th>Gram</th>
                                        <th>Carat</th>
                                        <th>Rate/Gram</th>
                                        <th>Rate/Carat</th>
                                        <th>Total</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="stones_products">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
