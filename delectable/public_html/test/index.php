<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://cdn.rawgit.com/leongersen/noUiSlider/master/distribute/nouislider.min.css" rel="stylesheet" />
        <style type="text/css">
            body {
            margin-top: 30px;
            margin-bottom: 30px;
            background: #f2f2f2;
            }
            .canvas-container {
            margin-right: auto;
            margin-left: auto;
            }
        </style>
    </head>
    <body>
        <div class="container text-center">
            <div class="form-group admin-menu">
                <div class="row">
                    <div class="col-sm-2 col-sm-offset-3 form-group">
                        <label>Width (px)</label>
                        <input type="number" id="width" class="form-control" value="720" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label>Height (px)</label>
                        <input type="number" id="height" class="form-control" value="540" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label>&nbsp;</label>
                        <br />
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary rectangle">+ &#9647; Table</button>
                    <button class="btn btn-primary circle">+ &#9711; Table</button>
                    <button class="btn btn-primary triangle">+ &#9651; Table</button>
                    <button class="btn btn-primary chair">+ Chair</button>
                    <button class="btn btn-primary bar">+ Bar</button>
                    <button class="btn btn-default wall">+ Wall</button>
                    <button class="btn btn-danger remove">Remove</button>
                    <button class="btn btn-warning customer-mode">Customer mode</button>
                </div>
            </div>
            <div class="form-group customer-menu" style="display: none;">
                <div class="btn-group">
                    <button class="btn btn-success submit">Submit reservation</button>
                    <button class="btn btn-warning admin-mode">Admin mode</button>
                </div>
                <br />
                <br />
                <div id="slider"></div>
                <div id="slider-value"></div>
            </div>
            <canvas id="canvas" width="720" height="540"></canvas>
        </div>
        <div class="modal fade" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p id="modal-table-id"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" id="save">Download</button>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.11/fabric.min.js"></script>
        <script src="https://cdn.rawgit.com/leongersen/noUiSlider/master/distribute/nouislider.min.js"></script>
        <script type="text/javascript" src="./test.js"></script>
        <script type="text/javascript">
            document.getElementById('save').addEventListener('click', function () {
              // retrieve the canvas data
              var canvasContents = canvas.toDataURL(); // a data URL of the current canvas image
              var data = { image: canvasContents, date: Date.now() };
              var string = JSON.stringify(data);
            
              // create a blob object representing the data as a JSON string
              var file = new Blob([string], {
                type: 'application/json'
              });
              
              // trigger a click event on an <a> tag to open the file explorer
              var a = document.createElement('a');
              a.href = URL.createObjectURL(file);
              a.download = 'data.json';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            });
        </script>
    </body>
</html>