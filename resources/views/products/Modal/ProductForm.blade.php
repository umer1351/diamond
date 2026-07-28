<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="image_path" id="image_path">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name:<span style="color:red;">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="prefix" class="col-sm-2 control-label">Prefix:<span style="color:red;">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="prefix" name="prefix" placeholder="Enter Product Prefix" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image_file" class="col-sm-3 control-label">Category Image</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                            <small class="text-muted">Used on storefront category circles. Leave empty to keep current image.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Product description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tags" class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="tags" name="tags" placeholder="bracelets, necklace, rings">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attributes" class="col-sm-2 control-label">Attributes</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="attributes" name="attributes" rows="3" placeholder="JSON or comma-separated attributes"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-sm-2 control-label">Stock</label>
                        <div class="col-sm-12">
                            <input type="number" step="1" min="0" class="form-control" id="stock" name="stock" placeholder="Auto-synced from inventory">
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
