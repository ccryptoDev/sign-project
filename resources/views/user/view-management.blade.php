@include('user.header')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap pb-0">
                <div class="card-title">
                    <h3 class="card-label">Employees</h3>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-primary font-weight-bolder" data-toggle="modal"
                        data-target="#newLocationModal">
                        <i class="la la-plus"></i>
                        New Employee
                    </button>
                    <button class="btn btn-primary font-weight-bolder" data-toggle="modal"
                        data-target="#editLocationModal" id="btnUpModal" style="display:none">
                        <i class="la la-plus"></i>
                        Update Location
                    </button>
                        </button>                        
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row pb-5">
                    <div class="col-md-9">
                        <button type="button" class="btn btn-success" onClick="selectAll()">Select All</button>
                        <button type="button" class="btn btn-primary bulk-edit d-none"
                            data-toggle="modal"
                            data-target="#editBulkLocationModal">Change Location</button>
                        <button type="button" class="btn btn-danger bulk-edit d-none" onClick="bulk_delete()">Delete</button>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <select class="form-control" style="width: 100%" id="kt_select2_3">
                            <option value="">Please select location</option>
                            @foreach($locations as $location)
                                <option value="{{$location->name}}">{{$location->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                    style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Location</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>IDs</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $key => $val)
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox" name="Checkboxes1" />
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                @foreach($locations as $location)
                                @if($val->location == $location->id)
                                {{$location->name}}
                                @endif
                                @endforeach
                            </td>
                            <td>{{$val->last_name}}</td>
                            <td>{{$val->first_name}}</td>
                            <td>{{$val->ids}}</td>
                            <td>{{$val->phone}}</td>
                            <td>{{$val->created_at}}</td>
                            <td>
                                <i class="fa fa-edit text-success mr-3 edit" style="cursor:pointer"
                                    data-id="{{$val->id}}" data-fname="{{$val->first_name}}"
                                    data-lname="{{$val->last_name}}" data-ids="{{$val->ids}}"
                                    data-location="{{$val->location}}" data-phone="{{$val->phone}}" title="Edit"></i>
                                <i class="fa fa-trash text-danger" style="cursor:pointer" data-id="{{$val->id}}"
                                    title="Delete"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table> --}}
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Location</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>IDs</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- New Location -->
<div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form id="frmNew">
                <div class="modal-body">
                    <div class="form-group">
                        <span>Location</span>
                        <select class="form-control locations" name="location" required>
                            @foreach($locations as $key => $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Last Name</span>
                        <input class="form-control" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <span>First Name</span>
                        <input class="form-control" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <span>IDs</span>
                        <input class="form-control ids" name="ids" required>
                    </div>
                    <div class="form-group">
                        <span>Cell phone</span>
                        <input class="form-control phone" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close-new btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Location -->
<div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form id="frmUpdate">
                <div class="modal-body">
                    <div class="form-group">
                        <span>Location</span>
                        <input class="form-control id" name="id" type="hidden" required>
                        <select class="form-control locations" name="location" required>
                            @foreach($locations as $key => $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Last Name</span>
                        <input class="form-control last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <span>First Name</span>
                        <input class="form-control first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <span>IDs</span>
                        <input class="form-control ids" name="ids" required>
                    </div>
                    <div class="form-group">
                        <span>Cell phone</span>
                        <input class="form-control phone" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close-update btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Buck Location -->
<div class="modal fade" id="editBulkLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form id="frmBulkUpdate">
                <div class="modal-body">
                    <div class="form-group">
                        <span>Location</span>
                        <select class="form-control locations" name="location" required>
                            @foreach($locations as $key => $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close-update btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('user.footer')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/js/manage.js"></script>
<script>
    $(".fa-edit").on('click', function(){
        $("#frmUpdate").find('.id').val($(this).data('id'));
        $("#frmUpdate").find('.first_name').val($(this).data('fname'));
        $("#frmUpdate").find('.last_name').val($(this).data('lname'));
        $("#frmUpdate").find('.phone').val($(this).data('phone'));
        $("#frmUpdate").find('.locations').val($(this).data('location'));
        $("#frmUpdate").find('.ids').val($(this).data('ids'));
        $("#btnUpModal").click();
    })
    $(".fa-trash").on('click', function(){
        var id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won\'t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url : '/user/delete-subject/'+id,
                    type : "GET",
                    success : function(res){
                        location.reload();
                    }
                })
            }
        });
    })
    $(document).ready(function(){
        $(".phone").inputmask("mask", {
			"mask": "(999) 999-9999"
		});
        $(".ids").inputmask("mask", {
			"mask": "999-99-9999"
		});
        // $('#kt_select2_3').select2({
		// 	placeholder: "Select a location",
		// });
    })
</script>