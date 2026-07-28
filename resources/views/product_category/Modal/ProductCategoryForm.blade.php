<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form id="productCategoryForm" name="productCategoryForm" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prefix">Prefix:</label>
                                <input type="text" class="form-control" id="prefix" name="prefix" placeholder="Enter prefix" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Optional description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image_file">Image:</label>
                                <input type="file" class="form-control" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                                <small class="text-muted">JPG, PNG, WEBP or GIF, up to 5 MB.</small>
                                <div class="form-check mt-2" id="removeImageWrap" style="display:none;">
                                    <input type="checkbox" class="form-check-input" id="remove_image" name="remove_image" value="1">
                                    <label class="form-check-label" for="remove_image">Remove current image</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Preview:</label>
                                <div class="border rounded p-2 d-flex align-items-center justify-content-center" style="min-height:110px;">
                                    <img id="imagePreview" src="" alt="" style="max-width:100%;max-height:90px;display:none;object-fit:contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button type="submit" id="saveBtn" class="btn btn-primary" value="create">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
