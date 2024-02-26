@include('user.header')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom">
                <div class="card-header flex-wrap pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Locations</h3>
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#newLocationModal">
                            <i class="la la-plus"></i>
                            New Location
                        </button>
                        <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#editLocationModal" id="btnUpModal" style="display:none">
                            <i class="la la-plus"></i>
                            Update Location
                        </button>                        
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number of Employees</th>
                                <th>Number/Percentage</th>
                                <th>Flag</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $key => $val)
                                <tr>
                                    <td>{{$val->name}}</td>
                                    <td>
                                        @foreach($subjects as $subject)
                                            @if($val->id == $subject->location)
                                                {{$subject->total}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$val->flag==1?"%".number_format($val->number,2):number_format($val->number, 0)}}</td>
                                    <td>{{$val->flag==1?"%":"Number"}}</td>
                                    <td>{{$val->created_at}}</td>
                                    <td>
                                        <i class="fa fa-edit text-success mr-3 edit" style="cursor:pointer"
                                            data-id="{{$val->id}}" data-name="{{$val->name}}"
                                            data-flag="{{$val->flag}}" data-limit="{{$val->flag==1?'%'.number_format($val->number,2):$val->number}}"
                                            title="Edit"
                                        ></i>
                                        <i class="fa fa-trash text-danger" style="cursor:pointer" data-id="{{$val->id}}" title="Delete"></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- New Location -->
    <div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form id="frmNew">
                    <div class="modal-body">
                        <div class="form-group">
                            <span>Name</span>
                            <input class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <div class="radio-inline status">
                                <label class="radio">
                                    <input type="radio" checked="checked" name="flag" value="0"/>
                                    <span></span>
                                    Number
                                </label>
                                <label class="radio">
                                    <input type="radio" name="flag" value="1"/>
                                    <span></span>
                                    Percentage
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <span>Number</span>
                            <input class="form-control number" type="number" name="number" required>
                        </div>
                        <div class="form-group">
                            <span>Percentage</span>
                            <input class="form-control percentage" type="text" name="number" disabled required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Location -->
    <div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form id="frmUpdate">
                    <div class="modal-body">
                        <div class="form-group">
                            <span>Name</span>
                            <input class="form-control" name="id" id="id" type="hidden" required>
                            <input class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <div class="radio-inline status">
                                <label class="radio">
                                    <input type="radio" checked="checked" name="flag" value="0"/>
                                    <span></span>
                                    Number
                                </label>
                                <label class="radio">
                                    <input type="radio" name="flag" value="1"/>
                                    <span></span>
                                    Percentage
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <span>Number</span>
                            <input class="form-control number" type="number" name="number" required>
                        </div>
                        <div class="form-group">
                            <span>Percentage</span>
                            <input class="form-control percentage" type="text" name="number" disabled required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@include('user.footer')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/js/location.js"></script>
<script>
    $(".edit").on('click', function(){
        $("#id").val($(this).data('id'));
        $("#name").val($(this).data('name'));
        var limit = $(this).data('limit')
        if($(this).data('flag') == 0){
            $("#frmUpdate .status").find('input').each(function(){
                if($(this).val() == 0){
                    $("#frmUpdate .number").attr('disabled', false);
                    $("#frmUpdate .number").val(limit)
                }
                else{
                    $("#frmUpdate .percentage").val('')
                    $("#frmUpdate .percentage").attr('disabled', true);
                }
            })
        }
        else{
            $("#frmUpdate .status").find('input').each(function(){
                if($(this).val() == 0){
                    $("#frmUpdate .number").attr('disabled', true);
                    $("#frmUpdate .number").val(0)
                }
                else{
                    $("#frmUpdate .percentage").attr('disabled', false);
                    $("#frmUpdate .percentage").val(limit)
                }
            })
        }
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
                    url : '/user/delete-location/'+id,
                    type : "GET",
                    success : function(res){
                        location.reload();
                    }
                })
            }
        });
    })
    $(document).ready(function(){
        $(".percentage").inputmask("99.99");
        $("#frmNew .status input").on('change', function(){
            if($(this).val() == 0){
                $("#frmNew .percentage").attr('disabled', true)
                $("#frmNew .number").attr('disabled', false)
            }
            else{
                $("#frmNew .number").attr('disabled', true)
                $("#frmNew .percentage").attr('disabled', false)
            }
        })
        $("#frmUpdate .status input").on('change', function(){
            if($(this).val() == 0){
                $("#frmUpdate .percentage").attr('disabled', true)
                $("#frmUpdate .number").attr('disabled', false)
            }
            else{
                $("#frmUpdate .number").attr('disabled', true)
                $("#frmUpdate .percentage").attr('disabled', false)
            }
        })
        $("#frmUpdate").submit(function(event){
            event.preventDefault();
            KTApp.blockPage();
            var fs = new FormData(document.getElementById("frmUpdate"));
            $.ajax({
                url : '/user/update-location',
                type : "POST",
                data : fs,
                contentType : false,
                processData : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(res){
                    KTApp.unblockPage();
                    if(res == 'success'){
                        location.reload();
                    }
                    else{
                        toastr.error(res);
                    }
                },
                error : function(err){
                    KTApp.unblockPage();
                    toastr.error("Please refresh your browser");
                }
            })
        })
        $("#frmNew").submit(function(event){
            event.preventDefault();
            KTApp.blockPage();
            var fs = new FormData(document.getElementById("frmNew"));
            $.ajax({
                url : '/user/new-location',
                type : "POST",
                data : fs,
                contentType : false,
                processData : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(res){
                    KTApp.unblockPage();
                    if(res == 'success'){
                        location.reload();
                    }
                    else{
                        toastr.error(res);
                    }
                },
                error : function(err){
                    KTApp.unblockPage();
                    toastr.error("Please refresh your browser");
                }
            })
        })
    })
</script>