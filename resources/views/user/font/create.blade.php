@include('user.header')
<style>
    @media (max-width: 576px){
        .md-container {
            display: inline-block;
        }
        .md-container .col-form-label, .md-container .col-sm-2 {
            width: 20%;
            display: inline-block;
            padding: 0px;
        }
    }
</style>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch">
                    <div class="card-header">
                        <div class="card-title">
                            <button class="btn btn-primary mt-0 d-inline mr-3" type="button" id="newFont">New Font</button>
                            <button class="btn btn-primary mt-0 d-inline mr-3" type="button" id="openFont">Open Font</button>
                            <button class="btn btn-primary mt-0 d-inline mr-3" type="button" id="download">Download</button>
                            <button class="btn btn-primary mt-0 d-inline mr-3" type="button" id="upload">Upload</button>
                            <button class="btn btn-primary mt-0 d-inline mr-3" type="button" id="save">Save / Exit</button>
                        </div>
                        <div class="card-toolbar">
                            <a class="btn btn-sm btn-icon btn-light mr-2 undo">
                                <i class="flaticon2-left-arrow"></i>
                            </a>
                            <a class="btn btn-sm btn-icon btn-light mr-2 redo">
                                <i class="flaticon2-right-arrow"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body" style="overflow:scroll">
                        <div class="mb-3">
                            <span id="font-title">Font: </span>
                        </div>
                        <div class="form-group">
                            <span class="label label-xl label-danger label-inline mr-2 cursor-pointer" id="font-list">Get Letter</span>
                            <span class="label label-xl label-danger label-inline mr-2 cursor-pointer" id="save-letter">Save Letter</span>
                            <span class="label label-xl label-danger label-inline mr-2 cursor-pointer" id="clear-grid">Clear Grid</span>
                            <span class="label label-xl label-danger label-inline mr-2 cursor-pointer" id="revert-grid">Revert Grid</span>
                        </div>
                        <form id="textForm">
                            <div class="form-group row mr-0 ml-0">
                                <div class="col-xs-6 col-md-6 row md-container">
                                    <label class="col-form-label col-sm-2 col-md-2 mr-2">Letter:</label>
                                    <div class="col-sm-2 col-md-3">
                                        <input class="form-control cus-input" type="text" value="" maxlength="1" id="letter" name="letter"/>
                                    </div>
                                    <label class="col-form-label col-sm-2 col-md-2 mr-2">Ascii: </label>
                                    <div class="col-sm-2 col-md-3">
                                        <input class="form-control cus-input" type="text" value="" disabled maxlength="2" id="ascii" name="ascii"/>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-6 row md-container">
                                    <label class="col-form-label col-sm-2 col-md-2 mr-2">H: </label>
                                    <div class="col-sm-2 col-md-3">
                                        <input class="form-control cus-input" type="number" value="10" id="height" name="height"/>
                                    </div>
                                    <label class=" col-form-label col-sm-2 col-md-2 mr-2">W: </label>
                                    <div class="col-sm-2 col-md-3">
                                        <input class="form-control cus-input" type="number" value="6" id="width" name="width"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="fontCanvas" width="0" height="0"></canvas>
                            </div>
                        </div>
                        <canvas id="canvas" width="240" height="400"></canvas>
                        <input type="file" accept=".ttf, .otf, .TTF, .OTF" class="custom-file-input" id="inputFont"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary d-none" id="modalFonts" data-toggle="modal" data-target="#exampleModal">
    List Fonts
</button>

<!-- Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Font</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="list-fonts">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold c-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('user.footer')
<script>
    var canvas = document.getElementById("canvas"),
    c = canvas.getContext("2d"),
    boxSize = 40,
    boxes = Math.floor(canvas.height / boxSize);
    boxes_w = Math.floor(canvas.width / boxSize);
    var clicked = false;

    function drawBox() {
        c.beginPath();
        c.fillStyle = "black";
        c.lineWidth = 3;
        c.strokeStyle = '#585A59';
        for (var row = 0; row < boxes; row++) {
            for (var column = 0; column < boxes; column++) {
                var x = column * boxSize;
                var y = row * boxSize;
                c.rect(x, y, boxSize, boxSize);
                c.fill();
                c.stroke();
            }
        }
        c.closePath();
    }
    function displayFont(letter, font, width, height) {
        $("#letter").val(letter);
        $("#ascii").val(letter.charCodeAt(0));
        $("#width").val(width);
        $("#height").val(height);
        $(".c-modal").click();

        var font = font.split(',');
        drawBox();
        c.fillStyle = "yellow";
        var pp = 0;
        var fillSize = boxSize - 1;
        var total_p = 0
        for (var row = 0; row < boxes; row++) {
            for (var column = 0; column < boxes_w; column++) {
                var x = column * boxSize;
                var y = row * boxSize;
                if(font[pp] == "true") {
                    c.fillRect(x, y, fillSize, fillSize);
                    total_p++;
                }
                pp++;
            }
        }
    }
    var fontName = "";
    $(document.fonts).ready(function(){
        $("#font-title").text('Font: ' + $('#width').val() + "x" + $("#height").val() + '.FON');
        // Letter
        $("#letter").on('keyup', function(e) {
            var value = $("#letter").val();
            if(value != '' ) {
                $("#ascii").val(value.charCodeAt(0));
            }
            if(fontName != "") {
                drawBox();
                c.font = '520px "New"';
                // c.fontKerning = "none";
                c.fontStretch = "ultra-expanded";
                c.fillStyle = "white";
                c.textBaseline = 'top';
                c.fillText($("#letter").val(), 0, -65);
                // ctxFont.fillText  ('New Regular', 10, 10);
            }
        })
        // End of Letter

        $("#clear-grid").on('click', function() {
            drawBox();
        })

        $("#revert-grid").on('click', function() {
            c.restore();
        })

        canvas.addEventListener('click', handleClick);
        canvas.addEventListener('touchstart', handleClick);
        canvas.addEventListener('touchmove', handleClick);
        canvas.addEventListener('mousemove', handleMove);
        canvas.addEventListener('mouseup', function() {
            clicked = false;
        });
        $(document).on('mouseup', function() {
            clicked = false;
        })
        canvas.addEventListener('mousedown', function() {
            clicked = true;
        });

        function handleClick(e) {
            c.fillStyle = "yellow";
            const fillSize = boxSize - 1;
            c.save();
            if(e.offsetX) {
                c.fillRect(Math.floor(e.offsetX / boxSize) * boxSize,
                    Math.floor(e.offsetY / boxSize) * boxSize,
                    fillSize, fillSize);
            } else {
                let r = canvas.getBoundingClientRect();

                let x = e.touches[0].pageX - r.left;
                let y = e.touches[0].pageY - r.top;
                c.fillRect(Math.floor(x / boxSize) * boxSize,
                    Math.floor(y / boxSize) * boxSize,
                    fillSize, fillSize);
            }
        }

        function handleMove(e) {
            if( clicked == false) {
                return false;
            }
            c.fillStyle = "yellow";
            const fillSize = boxSize - 1;
            if(e.offsetX) {
                c.fillRect(Math.floor(e.offsetX / boxSize) * boxSize,
                    Math.floor(e.offsetY / boxSize) * boxSize,
                    fillSize, fillSize);
            }
        }

        drawBox();

        // Save Letter
        $("#save-letter").on('click', function () {
            let newFont = [];
            if($("#letter").val() == '') {
                Swal.fire({
                    title: "Please input letter.",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonText: "Okay"
                }).then(function(result) {
                    if (result.value) {
                        $("#letter").focus();
                    }
                });
                return;
            }
            var total = 0;
            for (var row = 0; row < canvas.height / boxSize; row++) {
                var temp = [];
                for (var column = 0; column < canvas.width / boxSize; column++) {
                    var x = column * boxSize;
                    var y = row * boxSize;
                    // 255, 249, 40
                    const [ r, g, b, a ] = c.getImageData( x + 10, y + 10, 10, 10).data;
                    const color =  `rgb(${r},${g},${b})`;
                    if(color == 'rgb(255,255,0)') {
                        total++;
                        temp[column] = true;
                    } else {
                        temp[column] = false;
                    }
                }
                newFont.push(temp);            
            }
            if(total == 0) {
                Swal.fire("Please fill the color", "", "error");
            }
            var fs = new FormData(document.getElementById('textForm'));
            fs.append('font', newFont);
            fs.append('ascii', $("#ascii").val());
            $.ajax({
                url : '/save-font',
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				data: fs,
                contentType : false,
                processData : false,
                success : function(res){
                    toastr.success("Success");
                }
            })
        })
        // Get Font
        $("#font-list").on('click', function () {
            // font-lists
            $.ajax({
                url: "/font-lists",
                type: "GET",
                success : function(res){
                    $("#list-fonts")[0].innerHTML = '';
                    var html = '';
                    for(var i = 0; i < res.length; i++) {
                        html += '<span class="label label-xl label-rounded label-success mr-2 cursor-pointer"'+
                            'onclick="displayFont(`'+res[i]['letter']+'`, `'+res[i]['font']+'`, `'+res[i]['width']+'`, `'+res[i]['height']+'`)">' + res[i]['letter'] + '</span>';
                    }
                    $("#list-fonts").append(html);
                    $("#modalFonts").click();
                    // location.reload();
                }
            })
        })

        // Open Font
        const fontcanvas = document.getElementById("fontCanvas");
        $("#openFont").on('click', function() {
            $("#inputFont").click();
        });

        $("#inputFont").on('change', function(e) {
            var formData = new FormData();
            formData.append('file', e.target.files[0]);
            $.ajax({
                url: '/upload-font',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("#inputFont").val('');
                    if(res.success == true) {
                        // console.log(res.path);
                        fontName = "New";
                        const fontFile = new FontFace(
                            "New",
                            "url(http://localhost:8000/fonts/" + res.path + ")",
                            // { stretch: "60% 180%" },
                        );
                        document.fonts.add(fontFile);

                        fontFile.load().then(
                        (e) => {
                            fontcanvas.width = 650;
                            fontcanvas.height = 100;
                            const ctxFont = fontcanvas.getContext("2d");
                            ctxFont.reset();
                            // drawBox();
                            // c.font = '520px "New"';
                            // // c.fontKerning = "none";
                            // c.fontStretch = "ultra-expanded";
                            // c.fillStyle = "white";
                            // c.textBaseline = 'top';
                            // c.fillText('A', 0, -65);
                            // ctxFont.font = '300px "New"';
                            // ctxFont.textBaseline = 'top';
                            // ctxFont.fillStyle = 'black';
                            // // ctxFont.fillText  ('New Regular', 10, 10);

                            ctxFont.font = '36px "New"';
                            ctxFont.fillText("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 20, 50);
                            ctxFont.fillText("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 20, 50);
                        },
                        (err) => {
                            console.error(err);
                        })
                    }
                },
                error: function() {
                    toastr.error('fail to upload data');
                }
            })
        })
    });
</script>