@include('user.header')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom">
                <div class="card-header flex-wrap pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Reports</h3>
                    </div>
                    <div class="card-toolbar">
                        <select class="form-control d-inline" id="location" style="width: auto">
                            <option value="">Please select location</option>
                            @foreach($locations as $location)
                                <option value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-danger font-weight-bolder ml-3 btn-report">
                            <i class="far fa-file-pdf"></i>
                            Report
                        </button>                      
                    </div>
                </div>
                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            <span>Success</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $val)
                                <tr>
                                    <td>{{$val->location}}</td>
                                    <td>{{$val->created_at}}</td>
                                    <td>
                                        <a href="/rand/{{base64_encode($val->id)}}">
                                            <i class="fa fa-eye text-success mr-3 edit" style="cursor:pointer"></i>
                                        </a>
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
@include('user.footer')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/js/report.js"></script>
<script>
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
                    url : '/user/delete-report/'+id,
                    type : "GET",
                    success : function(res){
                        location.reload();
                    }
                })
            }
        });
    })
    $(document).ready(function(){
        $(".btn-report").on('click', function(){
            var location_name = jQuery("#location option:selected").text();
            if($("#location").val() != '') {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You only report data for " + location_name,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes"
                }).then(function(result) {
                    if (result.value) {
                        reportData();
                    }
                })
            } else {
                reportData();
            }
        })
        var reportData = function() {
            $.ajax({
                url : '/user/report',
                type : 'GET',
                data: {
                    location: $("#location").val()
                },
                success : function(res){
                    if(res['success'] == true){
                        Swal.fire({
                            title: "Do you want to check it now?",
                            text: "You have just created a report",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Yes"
                        }).then(function(result) {
                            if (result.value) {
                                location.href = '/rand/'+res['id'];
                            }
                            else{
                                location.reload();
                            }
                        });
                    }
                    else{
                        toastr.error(res);
                    }
                },
                error : function(err){
                    toastr.error("Please refresh your browser");
                }
            })
        }
    })
</script>