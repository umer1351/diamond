<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="otherProductForm" name="otherProductForm" class="form-horizontal">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name:<span
                                style="color:red;">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter Other Product Name" maxlength="50" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" class="col-sm-2 control-label">Unit:<span
                                class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select id="other_product_unit_id" name="other_product_unit_id"
                                class="form-control show-tick" required>
                                <option value="" selected="selected" disabled>--Select
                                    Unit--
                                </option>
                                @foreach ($other_product_units as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button type="submit" id="saveBtn" class="btn btn-primary" value="create">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
