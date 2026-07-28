@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" rel="stylesheet">
    <!-- Select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Journal Entries</h1>
            @if (isset($journal_entry))
                <ul>
                    <li>Edit</li>
                    <li>Update</li>
                </ul>
            @else
                <ul>
                    <li>Create</li>
                    <li>Save</li>
                </ul>
            @endif
        </div>
        <div class="separator-breadcrumb border-top"></div>
        <!-- end of row -->
        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="row">
                                <div class="col-md-12">
                                    <a type="button" class="btn  btn-primary m-1" href="{{ url('journal-entries') }}"
                                        style="position: absolute;right: 45px;"><i
                                            class="nav-icon mr-2 i-File-Horizontal-Text"></i>Back to List</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="font-size: 14px;">
                            {{-- Edit Form  --}}
                            <form id="form_validation" action="#" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Journal:<span style="color:red;">*</span></label>
                                            <select @if (isset($journal_entry)) disabled @endif id="journal_id"
                                                name="journal_id" class="form-control show-tick" required tabindex="1">
                                                <option value="" selected="selected" disabled>--Select Journal--
                                                </option>
                                                @foreach ($journals as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (isset($journal_entry)) {{ $journal_entry->journal_name->id == $item->id ? 'selected' : '' }} @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Supplier/Karigar:</label>
                                            <select @if (isset($journal_entry)) disabled @endif id="supplier_id"
                                                name="supplier_id" class="form-control show-tick" tabindex="3">
                                                <option value="" selected="selected" disabled>--Select
                                                    Supplier/Karigar--
                                                </option>
                                                @foreach ($suppliers as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (isset($journal_entry)) {{ $journal_entry->supplier_id == $item->id ? 'selected' : '' }} @endif>
                                                        {{ $item->name ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Currency:<span
                                                style="color:red;">*</span></label>
                                            <select @if (isset($journal_entry)) disabled @endif id="currency"
                                                name="currency" class="form-control show-tick" required>
                                                <option value="0" @if (isset($journal_entry)) {{ $journal_entry->currency == 0 ? 'selected' : '' }} @endif>PKR</option>
                                                <option value="1" @if (isset($journal_entry)) {{ $journal_entry->currency == 1 ? 'selected' : '' }} @endif>Gold (AU)</option>
                                                <option value="2" @if (isset($journal_entry)) {{ $journal_entry->currency == 2 ? 'selected' : '' }} @endif>Dollar ($)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group form-float">
                                            <div class="form-group">
                                                <label class="form-label">Reference:<span
                                                        style="color:red;">*</span></label>
                                                <input type="text" class="form-control"
                                                    value="{{ isset($journal_entry) ? $journal_entry->reference : '' }}"
                                                    id="reference" maxlength="255" name="reference" required tabindex="4"
                                                    @if (isset($journal_entry)) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control"
                                        value="{{ isset($journal_entry) ? $journal_entry->id : '' }}" id="id"
                                        name="id">
                                    <input type="hidden" class="form-control" id="tbl_id" name="tbl_id">
                                    <input type="hidden" class="form-control" id="tbl_index" name="tbl_index">
                                    <input readonly type="hidden" id="journalId" name="journalId" maxlength="100"
                                        class="form-control borderless"
                                        value="{{ isset($journal_entry) ? $journal_entry->journal_name->id : '' }}">
                                    <div class="col-md-12">
                                        <hr class="mb-2 mt-2">
                                        <b>Add Journal Entries Detail:</b>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-float">
                                            <label class="form-label">Account Name:<span style="color:red;">*</span></label>
                                            <div class="form-line">
                                                <select class="form-control" id="account_id" name="account_id" required
                                                    tabindex="6">
                                                    <option value="" selected="selected" disabled>--Select Account--
                                                    </option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}"
                                                            {{ $account->name == $account->id ? 'selected' : '' }}>
                                                            {{ $account->code }} -- {{ $account->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" name="pdate"
                                                        value="{{ isset($journal_entry) ? $journal_entry->date_post : '' }}"
                                                        id="pdate" class="form-control date" required tabindex="2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <div class="form-group">
                                            <label class="form-label">Acc Code:<span style="color:red;">*</span></label>
                                            <input type="text" maxlength="100"
                                                class="form-control borderless accountCode" id="accountCode"
                                                name="accountCode" value='' required readonly
                                                placeholder="Code...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cheque #</label>
                                        <input type="text" id="checkno" name="checkno" maxlength="100"
                                            tabindex="7" class="form-control borderless" value='' required>

                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bill #</label>
                                        <input type="text" id="billno" name="billno" maxlength="100"
                                            tabindex="8" class="form-control borderless" value='' required>

                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Explanation</label>
                                        <input style="font-family:arial; font-size :12px !important;" type="text"
                                            id="explanation" name="explanation" maxlength="1000" tabindex="9"
                                            class="form-control borderless" placeholder="description" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Debit:<span style="color:red;">*</span></label>
                                        <input type="text" id="debit" name="debit" maxlength="15"
                                            class="borderless form-control db" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Credit:<span style="color:red;">*</span></label>
                                        <input type="text" id="credit" name="credit" maxlength="15"
                                            class="form-control borderless cd" required>
                                    </div>
                                </div>
                            </form>


                            <div class="row mt-3">
                                <div class="col-md-1">
                                    <button tabindex="9" class="btn btn-primary waves-effect" accesskey="a"
                                        id="add">ADD</button>
                                </div>
                                <div class="col-md-2" style="text-align: right">
                                    <label>Bal : </label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" id="balance"
                                        style="text-align:right; font-weight:bold; font-size:12px; font-family:tahoma;    background: #f60a0a73;
    color: #000;border: 1px solid #ed0d0d;"
                                        class="form-control" value="" readonly>
                                </div>
                                <div class="col-md-1" style="text-align: right">
                                    <label>Total : </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="total_debit"
                                        style="text-align:right; font-weight:bold; font-size:12px; font-family:tahoma;"
                                        class="form-control" value="" readonly>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="total_credit"
                                        style="text-align:right; font-weight:bold; font-size:12px; font-family:tahoma;"
                                        class="form-control" value="" readonly>
                                </div>
                            </div>
                            <hr>
                            <div id="jsGrid" class="jsGrid"></div>
                            <hr>
                            <div class="col" style="margin-right:20px">
                                <button class="btn btn-primary" id="submitajax">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                    <!-- end of col -->
                </div>
                <!-- end of row -->
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
    <script src="{{ asset('js/common-methods/http-requests.js') }}" type="module"></script>
    <script src="{{ url('journal-entries/js/editJournalEntry.js') }}" type="module"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
        var journal_entry_id = "{{ isset($journal_entry) ? $journal_entry->id : '' }}";
    </script>
    <script>
        $(document).ready(function() {
            $('#account_id').select2();
            $('#journal_id').select2();
            $('#supplier_id').select2();
            $('#currency').select2();
            const pdate = document.getElementById("pdate");

            // ✅ Using the visitor's timezone
            pdate.value = formatDate();

            console.log(formatDate());

            function padTo2Digits(num) {
                return num.toString().padStart(2, "0");
            }

            function formatDate(date = new Date()) {
                return [
                    padTo2Digits(date.getMonth() + 1),
                    padTo2Digits(date.getDate()),
                    date.getFullYear(),
                ].join("/");
            }
            pdate.value = new Date().toISOString().split("T")[0];
        });
    </script>
@endsection
