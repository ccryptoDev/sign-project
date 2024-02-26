'use strict';
var table = $('#kt_datatable');

var delete_data = function(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won\'t be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url : '/delete-user/'+id,
                type : "GET",
                success : function(res){
                    table.DataTable().ajax.reload();
                    toastr.success('Success');
                }
            })
        }
    });
}

var edit_data = function(id, fname, lname, level, email) {
    $("#frmUpdate").find('.id').val(id);
    $("#frmUpdate").find('.first_name').val(fname);
    $("#frmUpdate").find('.last_name').val(lname);
    $("#frmUpdate").find('.email').val(email);
    $("#frmUpdate").find('.level').val(level).change();
    $("#btnUpModal").click();
}
var KTDatatablesDataSourceAjaxClient = function() {
	var initTable1 = function() {

		// begin first table
		table.DataTable({
			responsive: true,
			ajax: {
				url: '/list-management',
				type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				data: {
                    status : $("#update_status").val(),
				},
			},
            // dom: 'Bfrtip',
            // buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            // dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			// <'row'<'col-sm-12'tr>>
			// <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            columns: [
                { data: 'status' },
                { data: 'email' },
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'level' },
                { data: 'time' },
                { data: 'key', responsivePriority: -1 },
            ],
            order : [5, 'desc'],
			columnDefs: [
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return '\
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit" title="Edit details" onclick="edit_data(`' + full.id+ '`, `' + full.first_name+ '`, `' + full.last_name+ '`, `' + full.level+ '`, `' + full.email+ '`)">\
								<i class="la la-edit"></i>\
							</a>\
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete" onclick="delete_data(`' + full.id+ '`)">\
								<i class="la la-trash"></i>\
							</a>\
						';
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if(data == 0) {
                            return `<span class="label label-primary label-inline mr-2">Main User</span>`
                        } else if (data == 1) {
                            return `<span class="label label-success label-inline mr-2">Admin</span>`
                        } else {
                            return `<span class="label label-info label-inline mr-2">Super User</span>`
                        }
                    },
                },
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return full.key;
                    },
                },
            ],
		});

        // $("#kt_select2_3").on('change', function(){
        //     var locations = $(this).val();
        //     table.DataTable().columns( 1 ).search($(this).val()).draw()
        //     // locations.map((item) => {
        //     //     table.DataTable().column(1).search(item, false, false);
        //     // })
        //     // table.DataTable().draw();
        // });

        $("#frmUpdate").submit(function(event){
            event.preventDefault();
            KTApp.blockPage();
            var fs = new FormData(document.getElementById("frmUpdate"));
            $.ajax({
                url : '/update-user',
                type : "POST",
                data : fs,
                contentType : false,
                processData : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(res){
                    KTApp.unblockPage();
                    if(res == 'Success'){
                        $('.btn-close-update').click();
                        table.DataTable().ajax.reload();
                        toastr.success(res);
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
                url : '/new-user',
                type : "POST",
                data : fs,
                contentType : false,
                processData : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(res){
                    KTApp.unblockPage();
                    if(res == 'Success'){
                        $('.btn-close-new').click();
                        jQuery("#frmNew").find('input').each(function(){
                            jQuery(this).val('');
                        })
                        table.DataTable().ajax.reload();
                        toastr.success(res);
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
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesDataSourceAjaxClient.init();
});