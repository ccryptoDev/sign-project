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
                    <button class="btn btn-primary d-none font-weight-bolder" data-toggle="modal"
                        id="btnUpModal"
                        data-target="#editLocationModal">
                        <i class="la la-plus"></i>
                        New Employee
                    </button>
                </div>
            </div>
            <?php
                $level = Auth::user()->level;
            ?>
            <div class="card-body">
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Level</th>
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
                        <span>First Name</span>
                        <input class="form-control" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <span>Last Name</span>
                        <input class="form-control" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <span>Email</span>
                        <input class="form-control" name="email" required>
                    </div>
                    {{-- <div class="form-group">
                        <span>Cell phone</span>
                        <input class="form-control phone" name="phone">
                    </div> --}}
                    <div class="form-group">
                        <span>Level</span>
                        <select class="form-control form-control-solid" name="level">
                            <option value="0">Main User</option>
                            <option value="1">Admin</option>
                            @if($level == 2)
                                <option value="2">Super User</option>
                            @endif
                        </select>
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
                        <span>First Name</span>
                        <input class="form-control id d-none" name="id" required>
                        <input class="form-control first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <span>Last Name</span>
                        <input class="form-control last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <span>Email</span>
                        <input class="form-control email" name="email" required>
                    </div>
                    <div class="form-group">
                        <span>Level</span>
                        <select class="form-control form-control-solid level" name="level">
                            <option value="0">Main User</option>
                            <option value="1">Admin</option>
                            @if($level == 2)
                                <option value="2">Super User</option>
                            @endif
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
<script src="/js/manage-users.js"></script>
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