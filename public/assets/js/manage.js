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
                url : '/user/delete-subject/'+id,
                type : "GET",
                success : function(res){
                    table.DataTable().ajax.reload();
                }
            })
        }
    });
}

var edit_data = function(id, fname, lname, phone, location, ids) {
    $("#frmUpdate").find('.id').val(id);
    $("#frmUpdate").find('.first_name').val(fname);
    $("#frmUpdate").find('.last_name').val(lname);
    $("#frmUpdate").find('.phone').val(phone);
    $("#frmUpdate").find('.locations').val(location);
    $("#frmUpdate").find('.ids').val(ids);
    $("#btnUpModal").click();
}
var selectedIds = [];

var changeCheckbox = function() {
    getSelectedItems();
    if(selectedIds.length > 0) {
        jQuery('.bulk-edit').each(function() {
            jQuery(this).removeClass('d-none');
        })
    } else {
        jQuery('.bulk-edit').each(function() {
            jQuery(this).addClass('d-none');
        })
    }
}
var getSelectedItems = function () {
    var rowcollection = table.DataTable().$('input', {'page': 'current'});
    var items = [];
    rowcollection.each(function(){
        if(jQuery(this).prop('checked')) {
            var id = jQuery(this).data('id');
            items.push(id);
        }
    })
    selectedIds = items;
}
var bulk_delete = function() {
    getSelectedItems();
    Swal.fire({
        title: "Are you sure?",
        text: "You won\'t be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url : '/user/delete-subjects/',
                data: {
                    ids: selectedIds,
                },
                type : "GET",
                success : function(res){
                    // if(res.success == true) {
                        table.DataTable().ajax.reload();
                        jQuery('.bulk-edit').each(function() {
                            jQuery(this).addClass('d-none');
                        })
                    // }
                }
            })
        }
    });
}
var selectAll = function () {
    var rowcollection = table.DataTable().$('input', {'page': 'current'});
    rowcollection.each(function(){
        jQuery(this).prop('checked', true).change();
    })
    jQuery('.bulk-edit').each(function() {
        jQuery(this).removeClass('d-none');
    })
}
var KTDatatablesDataSourceAjaxClient = function() {
	var initTable1 = function() {

		// begin first table
		table.DataTable({
			responsive: true,
			ajax: {
				url: '/user/list-management',
				type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				// data: {
                //     status : $("#update_status").val(),
				// },
			},
            dom: 'Bfrtip',
            buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
            lengthMenu: [10, 25, 50, 100],
            // pageLength: 10,
            dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            columns: [
                { data: 'location' },
                { data: 'location_name' },
                { data: 'last_name' },
                { data: 'first_name' },
                { data: 'ids' },
                { data: 'phone' },
                { data: 'time' },
                { data: 'id', responsivePriority: -1 },
            ],
            order : [6, 'desc'],
			columnDefs: [
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return '\
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit" title="Edit details" onclick="edit_data(`' + full.id+ '`, `' + full.first_name+ '`, `' + full.last_name+ '`, `' + full.phone+ '`, `' + full.location+ '`, `' + full.ids+ '`)">\
								<i class="la la-edit"></i>\
							</a>\
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete" onclick="delete_data(`' + full.id+ '`)">\
								<i class="la la-trash"></i>\
							</a>\
						';
                    },
                },
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '<label class="checkbox">' +
                            '<input type="checkbox" name="Checkboxes1" onChange="changeCheckbox()" data-id="' + full.id + '"/>' +
                            '<span></span>' +
                        '</label>';
                    },
                },
            ],
		});

        $("#kt_select2_3").on('change', function(){
            var locations = $(this).val();
            table.DataTable().columns( 1 ).search($(this).val()).draw()
            // locations.map((item) => {
            //     table.DataTable().column(1).search(item, false, false);
            // })
            // table.DataTable().draw();
        });

        $("#frmUpdate").submit(function(event){
            event.preventDefault();
            KTApp.blockPage();
            var fs = new FormData(document.getElementById("frmUpdate"));
            $.ajax({
                url : '/user/update-subject',
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
                        $('.btn-close-update').click();
                        table.DataTable().ajax.reload();
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
                url : '/user/new-subject',
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
                        $('.btn-close-new').click();
                        jQuery("#frmNew").find('input').each(function(){
                            jQuery(this).val('');
                        })
                        table.DataTable().ajax.reload();
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
        $("#frmBulkUpdate").submit(function(event){
            event.preventDefault();
            var fs = new FormData(document.getElementById("frmBulkUpdate"));
            fs.append('ids', selectedIds);
            $.ajax({
                url : '/user/update-bulk-subject',
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
                        $('.btn-close-update').click();
                        table.DataTable().ajax.reload();
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