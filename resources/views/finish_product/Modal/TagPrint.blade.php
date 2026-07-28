<div class="modal fade" id="tagPrintModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading">New Tag Print</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                          <label for="name">Tag Product 1:<span style="color:red;">*</span></label>
                                          <select name="tag_product_1" class="form-control" id="tag_product_1" required>
                                                @foreach ($finish_product as $item)
                                                <option value="{{ $item->id ?? '' }}">{{ $item->tag_no ?? '' }}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="col-md-6">
                                    <div class="form-group">
                                          <label for="name">Tag Product 2:<span style="color:red;">*</span></label>
                                          <select name="tag_product_2" class="form-control" id="tag_product_2" required>
                                                @foreach ($finish_product as $item)
                                                <option value="{{ $item->id ?? '' }}">{{ $item->tag_no ?? '' }}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button type="submit" id="printBtn" class="btn btn-primary" value="create">Submit</button>
                  </div>
            </div>
      </div>
</div>