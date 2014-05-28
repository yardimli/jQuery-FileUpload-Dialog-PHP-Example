<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <title>ixlenim - single upload test</title>

	<link href="css/jquery.fileupload.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">

    <script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	
	<script src="js/bootstrap.min.js" type="text/javascript"></script>

	<script src="js/jquery.iframe-transport.js"></script>
	<script src="js/jquery.fileupload.js"></script>
	<script src="js/jquery.fileupload-process.js"></script>
	<script src="js/jquery.fileupload-validate.js"></script>
	
  </head>

  <body>

<!-- 
------------------------------ TEMPLATE DIALOG -----------------------------
-->
<div class="modal" id="TemplateDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Add Template</h4>
      </div>
      <div class="modal-body" style="margin-bottom:0px; padding-bottom:0px;">
	  
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for="inputTemplateName" class="col-sm-3 control-label">Name</label>
				<div class="col-sm-9">
					<input type="email" class="form-control" id="inputTemplateName" placeholder="Name of template">
				</div>
			</div>		
			<div class="form-group">
				<label for="InputTemplateDescription" class="col-sm-3 control-label">Description</label>
				<div class="col-sm-9">
					<textarea id="InputTemplateDescription" class="form-control" rows="3"></textarea>
				</div>
			</div>
			
			<div class="form-group" style="margin-top:10px;">
				<label for="InputTemplateDescription" class="col-sm-3 control-label">Template Zip</label>
				<div class="col-sm-9">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<span>Click to select or Drag file here</span>
						<!-- The file input field used as target for the file upload widget -->
						<input id="fileupload" type="file" name="files">
					</span>
					<br>
					<br>
					<!-- The global progress bar -->
					<div id="progress" class="progress" style="margin-bottom:15px">
						<div class="progress-bar progress-bar-success" style="width:50%"></div>
					</div>
					<div id="files" class="files"></div>
				</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Save and Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
$(document).ready(function() {

	$("#TemplateDialog").show();

	
    // Change this to the location of your server-side upload handler:
    var uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: 'uploadfileindex.php',
        dataType: 'json',
        autoUpload: true,
		singleFileUploads:true,
        acceptFileTypes: /(\.|\/)(zip)$/i,
        maxFileSize: 5000000, // 5 MB
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $('#progress .progress-bar').css('width','0%');
    }).on('fileuploadprocessalways', function (e, data) {
		console.log(data);
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);

		if (file.error) {
            console.log("upload error:"+file.error);
        }
        if (index + 1 === data.files.length) {
            data.context.find('button').text('Upload').prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css('width',progress + '%'  );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
				console.log(file.url);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index]).append('<br>').append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index, file) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index]).append('<br>').append(error);
        });
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});



</script>