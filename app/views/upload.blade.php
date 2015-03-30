<html>
<head>
	<title>Senedi XLS to form demo thingy.</title>
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/css/bootstrap-theme.min.css" />
	<script type="text/javascript" src="<?php echo url(); ?>/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="jumbotron">
      <div class="container">
      	<h3>This is a simple thing to demonstrate the upload excel and then fill up PDF</h3>
		{{ Form::open(array('action' => 'ProcessController@loadXls', 'method' => 'post', 'files' => true)) }}
		<div class="form-group">
			{{ Form::label('excelsheet', 'Upload Excel spreadsheet:') }}
			{{ Form::file('excelsheet') }}
		</div>
		{{ Form::button('Process and download form!', array('class' => 'btn btn-default', 'type' => 'submit')) }}
		{{ Form::close() }}
      </div>
    </div>
</body>
</html>