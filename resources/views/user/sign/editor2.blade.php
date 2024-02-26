@include('user.header')
<style>
    table,
    tr,
    td {
        border: 1px solid black;
        cursor: pointer;
    }

    table {
        border-collapse: collapse;
        background-color: white;
        margin: 0 auto;
    }

    .disabled {
        background-color: #ddd;
        border-width: 0;
    }

    .disabled td {
        border-width: 0;
    }

    tr {
        height: 20px;
    }

    td {
        width: 20px;
    }

    p {
        margin: 1em 0 1em;
    }

    .gridSizeLabel {
        font-size: 18px;
    }

    #createGrid,
    #paintBtn {
        margin-right: 10px;
    }


    /* text fly in effect */

    /* .flyItIn {
        animation: flyin 1s ease forwards;
        opacity: 0;
        transform: scale(2);
        filter: blur(4px);
    }

    @keyframes flyin {
        to {
            filter: blur(0);
            transform: scale(1);
            opacity: 1;
        }
    }

    .flyItIn2 {
        animation: flyin2 1s ease forwards;
        opacity: 0;
        transform: scale(2);
        filter: blur(4px);
    }

    @keyframes flyin2 {
        to {
            filter: blur(0);
            transform: scale(1);
            opacity: 1;
        }
    } */

    /* rotates the Design Canvas div */

    .rotationTime {
        -webkit-transition: all 4s linear;
        -moz-transition: all 4s linear;
        -ms-transition: all 4s linear;
        -o-transition: all 4s linear;
        transition: all 4s linear;
    }

    .rotateCanvas {
        -webkit-transform: rotateX(360deg) rotateY(360deg);
        -moz-transform: rotateX(360deg) rotateY(360deg);
        -ms-transform: rotateX(360deg) rotateY(360deg);
        -o-transform: rotateX(360deg) rotateY(360deg);
        transform: rotateX(360deg) rotateY(360deg);
    }

    @font-face {
        font-family: 'FFFFORWA';
        /* src: url('/fonts/FFFFORWA.TTF'); */
        src: url('/fonts/SUBWT.ttf');
        /* font-display: block; */
    }

    #canvas {
        border: 1px solid #ddd;
    }


    /* make adjustments for smaller devices */
</style>

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom card-stretch">
                    <div class="card-header">
                        <div class="card-title">
                            <button class="btn btn-danger mt-0 d-inline" type="button" id="createGrid">Set</button>
                            <button class="btn btn-warning mt-0 d-inline mr-3" type="button">Amber</button>
                            <select class="form-control selectpicker d-inline" id="edit-mode" data-style="btn-success">
                                <option value="0">3-Line Mode</option>
                                <option value="1">Dot-Type</option>
                            </select>
                            <div class="gridControl">
                                <form id="sizePicker" name="gridSize">
                                    {{-- <div class="gridCreateClear d-flex align-items-center mb-5">
                                        <input class="btn btn-success btn-block" type="submit" id="createGrid"
                                            value="Create Grid" name="submitGrid"
                                            title="Makes a grid of tiles using the values above">
                                        <button class="btn btn-danger btn-block mt-0" type="button" id="clearGrid"
                                            value="Clear" title="Clears the colors from the grid so you can start over">
                                            Clear
                                        </button>
                                    </div> --}}
                                    {{-- <div class="form-group mb-0">
                                        <label>Grid Width</label>
                                        <input class="form-control" id="gridWidthDisplay" value="60" type="number"
                                            max="100" min="1" />
                                    </div>
                                    <input class="form-control" type="range" id="inputWidth" value="60">
                                    <div class="form-group mb-0">
                                        <label>Grid Height</label>
                                        <input class="form-control" id="gridHeightDisplay" value="40" type="number"
                                            max="100" min="1" />
                                    </div>
                                    <input class="form-control" type="range" id="inputHeight" value="40" /> --}}
                                </form>
                                {{-- <div class="form-group mt-5">
                                    <label class="form-label">Pick a Color:</label>
                                    <input type="color" class="form-control mb-3" id="colorPicker" value="#ff0000"
                                        title="Set PAINT color">
                                </div> --}}
                                {{-- <div class="form-group">
                                    <label for="exampleSelectd">Edit Mode</label>
                                    <select class="form-control selectpicker" id="edit-mode" data-style="btn-success">
                                        <option value="0">3-Line Mode</option>
                                        <option value="1">Dot-Type</option>
                                    </select>
                                </div> --}}
                                {{-- <div id="mode" class="form-group">
                                    <label class="form-label">Mode:</label><span id="modeDisplay" class="paintOrErase">
                                        PAINT</span>
                                    <br>
                                    <button id="paintBtn" class="btn btn-outline-secondary btn-mode paint"
                                        data-mode="PAINT" title="Set mode to PAINT" type="button">
                                        <i class="fas fa-paint-brush paint"></i>
                                    </button>
                                    <button id="eraseBtn" class="btn btn-outline-secondary btn-mode erase"
                                        data-mode="ERASE" title="Set mode to ERASE" type="button">
                                        <i class="fas fa-eraser erase"></i>
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="btn-group text-alignment mr-2" role="group" aria-label="Basic example">
                                <button class="btn btn-sm btn-icon btn-light text-left" data-alignment="left">
                                    <i class="fas fa-align-left"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-center active" data-alignment="center">
                                    <i class="fas fa-align-center"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-right" data-alignment="right">
                                    <i class="fas fa-align-right"></i>
                                </button>
                                {{-- <button type="button" class="btn btn-primary">Middle</button> --}}
                                {{-- <button type="button" class="btn btn-primary">Right</button> --}}
                            </div>
                            <a class="btn btn-sm btn-icon btn-light mr-2 undo">
                                <i class="flaticon2-left-arrow"></i>
                            </a>
                            <a class="btn btn-sm btn-icon btn-light mr-2 redo">
                                <i class="flaticon2-right-arrow"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body text-center" style="overflow:scroll">
                        <textarea class="form-control" id="dummy" style="opacity: 0; height: 0px" rows="3"></textarea>
                        {{-- <canvas id="canvas_bg" width="800" height="600" class="d-none"></canvas> --}}
                        <canvas id="canvas" width="700" height="390" class="d-none"></canvas>
                        <div id="gridCanvas" class="gridCanvas rotationTime d-none">
                            <table id="pixelCanvas" class="flyItIn2"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.footer')
<script src="/js/font.js"></script>
<script>
    // drawGrid();
    function arrayReverse(originalArray) {
        var numRows = originalArray.length;
        var numCols = originalArray[0].length;

        // Create a new array to store the reversed data
        var reversedArray = [];

        for (var col = 0; col < numCols; col++) {
            var newRow = [];
            for (var row = 0; row < numRows; row++) {
                newRow.push(originalArray[row][col]);
            }
            reversedArray.push(newRow);
        }
        return reversedArray;
    }
    $(document).ready(function(){
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
        var value = "";
        var undo_lists = [];
        var redo_lists = [];
        var undo_flag = false;
        var alignmentList = ['center', 'center', 'center'];
        canvas.addEventListener('mousemove', handleClick);
        // canvas.addEventListener('touchmove', handleClick);
        var line = 0;
        var x = canvas.width / 2;

        function drawLED(messageArray) {
            var messageLength = messageArray.length;
            var totalScrollLength = 60 + messageLength;
            fontSize = 10;
            color = 'yellow';
            ctx.fillStyle = color;
            ctx.fillRect(3 * fontSize, 3 * fontSize, fontSize, fontSize);
            ctx.strokeRect(0, (i + rep * (blankRows + 10)) * cellHeight, canvas.width, cellHeight);
            // console.log(messageArray)
            // if(messageLength>0){        
            //     for(var col=0;col<messageLength;col++){
            //         for(var row=0;row<7;row++){
            //             var offsetCol = 0 + col;
            //             if(offsetCol<60 || offsetCol >= 0){
            //                 console.log(row);
            //                 console.log(offsetCol);
            //                 // setLight(row,offsetCol,messageArray[col][row]);

            //                 ctx.fillStyle = color;
            //                 ctx.fillRect(row * fontSize, offsetCol * fontSize, fontSize, fontSize);
            //             }                
            //         }
            //     }
            // }
        }
        function drawText () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawBoard()
            // ctx.font = "120px serif";
            ctx.font = "119px FFFFORWA";
            // lineHeight = ctx.measureText('M').width;
            // ctx.textBaseline = "middle";
            // ctx.fontKerning = "none";
            if(value) {
                var newArray = value.split("\n");
                newArray.map((item, index) => {
                    // console.log(item)
                    var myArray = item.split('');
                    var rowArray = [];
                    myArray.map(temp => {
                        var d = charToLED(temp);
                        var newOne = arrayReverse(d);
                        console.log(newOne);
                        drawLED(newOne);
                        // if(rowArray.length == 0) {
                        //     rowArray = newOne;
                        // } else {
                        //     rowArray.concat(newOne);
                        // }
                    })
                    console.log(rowArray);
                    // const alignment = alignmentList[index];
                    // ctx.textAlign = alignment;
                    // ctx.color = "black";
                    // ctx.fillStyle = 'yellow';
                    // if(alignment == 'left') {
                    //     ctx.fillText(item, 10, 130 * (index + 1));
                    // } else if(alignment == 'center') {
                    //     ctx.fillText(item, x, 130 * (index + 1));
                    // } else {
                    //     ctx.fillText(item, canvas.width - 10, 130 * (index + 1));
                    // }
                    
                    // ctx.fillText(alignment + "-aligned", item, 10 , 100 * (index + 1) + (index == 0 ? 0 : 10 * index));
                })
                if(undo_flag == false) {
                    undo_lists.push(value);
                    undo_flag = false;
                }
            }
        }
        function handleClick(e) {
            // if(!isDown) {
            //     return;
            // }
            var flag = false;
            for(let j = 0; j < 3; j++) {
                if(e.offsetY > (100 * j  + 30 * (j + 1)) && e.offsetY < 130 * (j + 1)) {
                    flag = true;
                }
            }
            if(flag == true) {
                canvas.style.cursor = 'text';
            } else {
                canvas.style.cursor = 'default';
            }
        }
        // Draw board
        var numRows = 39, numCols = 70, blankRows = 3, blankStrokeColor = 'black', whiteColor = 'black', whiteStrokeColor = 'white', repetitions = 4
        function drawBoard(){
            const cellWidth = canvas.width / numCols;
            const cellHeight = canvas.height / numRows;
            console.log('cellHeight', cellHeight);
            console.log('cellWidth', cellWidth);

            for (let rep = 0; rep < repetitions; rep++) {
                // Draw blank rows
                for (let i = 0; i < blankRows; i++) {
                    ctx.fillStyle = '#ffffff'; // White color
                    ctx.strokeStyle = blankStrokeColor; // Stroke color for blank rows
                    ctx.fillRect(0, (i + rep * (blankRows + 10)) * cellHeight, canvas.width, cellHeight);
                    ctx.strokeRect(0, (i + rep * (blankRows + 10)) * cellHeight, canvas.width, cellHeight);
                }

                // Draw white rows
                for (let row = blankRows; row < blankRows + 10; row++) {
                    ctx.fillStyle = whiteColor; // You can customize the white color
                    ctx.strokeStyle = whiteStrokeColor; // Stroke color for white rows
                    for (let col = 0; col < numCols; col++) {
                        ctx.fillRect(col * cellWidth, (row + rep * (blankRows + 10)) * cellHeight, cellWidth, cellHeight);
                        ctx.strokeRect(col * cellWidth, (row + rep * (blankRows + 10)) * cellHeight, cellWidth, cellHeight);
                    }
                }
            }
        }
        // End of draw board
        drawBoard();

        let pathsry = [];
        $("#dummy").focus();
        $(document).on('keyup', function(event) {
            if(event.keyCode == 8) {
                value = value.slice(0, -1);
                drawText();
            }
        })
        $(document).on('click', function(){
            $("#dummy").trigger('tap');
        })
        $("#dummy").on('change', function(e){
            if(e.which == 13) {
                var newArray = value.split("\n");
                if(newArray.length >= 3) {
                    return false;
                }
            }
            value = $(this).val();
            drawText();
        })
        // Text Alignment
        $(".text-alignment .btn").on('click', function() {
            $(".text-alignment .btn").each(function(){
                $(this).removeClass('active');
            })
            $(this).addClass('active');
            line = value.split("\n").length - 1;
            alignmentList[line] = $(this).data('alignment');
            drawText();
        })
        var changeMode = function() {
            value = "";
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawBoard();
            if($("#edit-mode").val() == 0) {
                $("#canvas_bg").removeClass('d-none');
                $("#canvas").removeClass('d-none');
                $("#gridCanvas").addClass('d-none');
            } else {
                $("#gridCanvas").removeClass('d-none');
                $("#canvas").addClass('d-none');
                $("#canvas_bg").addClass('d-none');
            }
        }

        changeMode();
        var undoFunction = function () {
            if(undo_lists.length >= 0 && undo_lists[undo_lists.length - 1]) {
                undo_flag = true;
                var poped = undo_lists.pop();
                redo_lists.push(poped);
                if(undo_lists[undo_lists.length - 2]) {
                    value = undo_lists[undo_lists.length - 2];
                    drawText();
                } else if(!undo_lists[undo_lists.length - 2] && undo_lists[undo_lists.length - 1]) {
                    value = undo_lists[undo_lists.length - 1];
                    drawText();
                } else {
                    value = '';
                    drawText();
                }
            }
        }
        $(".undo").click(function(){
            undoFunction();
        })

        function redoAction () {
            if(redo_lists.length >= 0 && redo_lists[redo_lists.length - 1]) {
                undo_flag = true;
                value = redo_lists[redo_lists.length - 1];
                undo_lists.push(redo_lists.pop());
                drawText();
            }
        }
        $(".redo").click(function() {
            redoAction();
        })


        $(document).on('keydown', function(e) {
            console.log(e.which)
            if( e.which === 91 && e.ctrlKey){
                undoFunction();
            }
            else if( e.which === 90 && e.ctrlKey ){
                undoFunction();
            } else if( e.which === 89 && e.ctrlKey ){
                redoAction();
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || e.which == 32){
                value += e.key;
                drawText();
            } else if(e.keyCode == 13) {
                var newArray = value.split("\n");
                if(newArray.length >= 3) {
                    return false;
                }
                value += "\n";
                line ++;
                drawText();
            }
        });
        // Grid Control
        $(".btn-toggle").on("click", function() {
            $(".gridContainer").toggle();
        })
        // Create a grid that a user can color with clicks
        //   - allows grid size entry and color selection

        // When size is submitted by the user, call makeGrid()

        // Set the inital 'paint' changes happen in click event
        const PAINT = 'PAINT';
        const ERASE = 'ERASE';
        const theGridSize = document.forms.gridSize;
        const userColor = document.getElementById('colorPicker');
        const tileMode = document.getElementById('modeDisplay');
        const displayHeight = document.getElementById('gridHeightDisplay');
        const displayWidth = document.getElementById('gridWidthDisplay');
        const userHeight = document.getElementById('inputHeight');
        const userWidth = document.getElementById('inputWidth');
            // let grid = $('#pixelCanvas');
        const grid = document.getElementById('pixelCanvas');
        const gridCanvas = document.getElementById('gridCanvas');
        let gridTileMode = PAINT // controls paint or erase of grid cells (td's)

        // $('#createGrid').on('click', function makeGrid(event) {gridSize
        $("#createGrid").on("click", function() {
            event.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: 'You won"t be able to revert this!',
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, create new one!",
                customClass: {
                    confirmButton: "btn-danger",
                },
            }).then(function(result) {
                if (result.value) {
                    changeMode();
                    makeGrid();
                }
            });
        })
        var makeGrid = function () {
            // changeMode();
            // prevent page refreshing when clicking submit
            // event.preventDefault();
            let mouseIsDown = false;
            // let rows = $("#inputHeight").val();
            // let columns = $("#inputWidth").val();
            // const rows = userHeight.value;
            // const columns = userWidth.value;
            const rows = 56;
            const columns = 40;

            // grid.children().remove(); // delete any previous table rows
            while (grid.hasChildNodes()) {
                grid.removeChild(grid.lastChild); // delete any previous table rows
            }

            //Build the grid row by row and then append to the table
            //  project rubrics requires use of for and while loops

            let tableRows = '';
            let r = 1;
            
            var selectedMode = $("#edit-mode").val();
            var blank = 3;
            var j = 1;
            while (r <= rows) {
                if(selectedMode == 0) {
                    var editableRow = parseInt(rows / 3) - blank;
                    var totalRow = rows / 3;
                    if(r > totalRow * j) {
                        j++;
                    }
                    if(r <= totalRow * j - 3) {
                        tableRows += '<tr>';
                    } else {
                        tableRows += '<tr class="disabled">';
                    }
                } else {
                    tableRows += '<tr>';
                }
                for (let c=1; c <= columns; c++) {
                    tableRows += '<td class="' +c+ '"></td>';
                }
                tableRows += '</tr>';
                r += 1;
            } // end while loop
            grid.insertAdjacentHTML('afterbegin', tableRows); // add grid to DOM
            // grid.classList.toggle('flyItIn'); // fly in effect for grid
            // grid.classList.toggle('flyItIn2'); // Twice to trigger reflow
            // Listen for click to paint or erase a tile
            // grid.on('click', 'td', function() {
            //     paintEraseTiles($(this));
            // });
            grid.addEventListener("click", function(event) {
                event.preventDefault();
                paintEraseTiles(event.target);
            });
            grid.addEventListener("touchstart", function(event) {
                event.preventDefault();
                paintEraseTiles(event.target);
            });
            grid.addEventListener("touchmove", function(event) {
                event.preventDefault();
                console.log(event.target);
                paintEraseTiles(event.target)
            });

            // Listen for mouse down, up and over for continuous paint and erase

            // grid.on('mousedown', function(event) {
            grid.addEventListener('mousedown', function(event) {
                event.preventDefault();
                mouseIsDown = event.which === 1 ? true : false;
            });
            grid.addEventListener('touchstart', function(event) {
                event.preventDefault();
                mouseIsDown = event.which === 1 ? true : false;
            });

            // document.on('mouseup', function() {
            document.addEventListener('mouseup', function(event) {
                event.preventDefault();
                mouseIsDown = false;
            });

            // grid.on('mouseover', 'td', function() {
            grid.addEventListener('mouseover', function(event) {
                // if (mouseIsDown) {paintEraseTiles($(this));}
                event.preventDefault();
                if (mouseIsDown) {paintEraseTiles(event.target);}
            }); // end continuous paint and erase
        // }); // end grid
        }; // end grid
        makeGrid();

        // paint or erase cells based on the mode (girdTileMode)

        function paintEraseTiles(targetCell) {
            if (targetCell.nodeName === 'TD') {
                if($($(targetCell).parent())[0].className == 'disabled') {
                    return;
                }
                // targetCell.style.backgroundColor = gridTileMode === PAINT ? userColor.value : 'transparent';
                targetCell.style.backgroundColor = 'red';
                //     // $(targetCell).css('background-color', $('#colorPicker').val());
                //     // $(targetCell).css('background-color', 'transparent');
            } else {
                console.log("Nice try: " + targetCell.nodeName + " talk to the hand!");
            }
        }

        $("#inputWidth").on('change', function() {
            $(displayWidth).val($(this).val());
        })
        $("#gridWidthDisplay").on('change', function() {
            $("#inputWidth").val($(this).val());
        })
        $("#inputHeight").on('change', function() {
            $(displayHeight).val($(this).val());
        })
        $("#gridHeightDisplay").on('change', function() {
            $("#inputHeight").val($(this).val());
        })

        $("#colorPicker").on('change', function() {
            gridTileMode = PAINT;
            tileMode.innerHTML = ' ' + gridTileMode;
        })
        // userColor.oninput = function (event){
        //     gridTileMode = PAINT;
        //     tileMode.innerHTML = ' ' + gridTileMode;
        // };
        // Erase colors from the grid

        // clear.on('click', function(){
        document.getElementById('clearGrid').addEventListener('click', function() {
            // gridCanvas.classList.toggle('rotateCanvas'); // rotate the Design Canvas div
            let tiles = grid.getElementsByTagName('td');
            // grid.children().children().removeAttr("style");
            for(let i = 0; i <= tiles.length; i++) {
                if(tiles[i]) {
                    tiles[i].style.backgroundColor = 'transparent';
                }
            }
        });

        // set the mode to PAINT or ERASE
        $(".btn-mode").on('click', function() {
            $(".btn-mode").each((index, item) => {
                $(item).removeClass('btn-danger');
            })
            $(this).addClass('btn-danger');
        })
        // $('button').on('click', function(event) {
        document.getElementById('mode').addEventListener('click', function(event) {
            gridTileMode = event.target.className.indexOf('paint') >=0 ? PAINT : ERASE;
            // $('.paintOrErase').text(' ' + gridTileMode);
            tileMode.innerHTML = ' ' + gridTileMode;
        });

    });
</script>