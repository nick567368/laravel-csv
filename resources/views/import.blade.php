<!DOCTYPE html>
<html>

<head>
     <title>Import Excel File in Laravel</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
     <style>
     .progress {
          position: relative;
          width: 100%;
     }

     .bar {
          background-color: #00ff00;
          width: 0%;
          height: 20px;
     }

     .percent {
          position: absolute;
          display: inline-block;
          left: 50%;
          color: #040608;
     }
     </style>
</head>

<body>
     <br />

     <div class="container">
          <h3 align="center">Import Excel File in Laravel</h3>
          <br />
          @if(count($errors) > 0)
          <div class="alert alert-danger">
               Upload Validation Error<br><br>
               <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
               </ul>
          </div>
          @endif

          @if($message = Session::get('success'))
          <div class="alert alert-success alert-block">
               <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $message }}</strong>
          </div>
          @endif
          <form method="post" action="/import" id="upload_form" enctype="multipart/form-data">
               {{  csrf_field() }}
               <div class="form-group">
                    <table class="table">
                         <tr>
                              <td width="40%" align="right"><label>Select File for Upload</label></td>
                              <td width="30">
                                   <input type="file" class="form-control" name="file" />
                              </td>
                              <div class="progress">
                                   <div class="bar"></div>
                                   <div class="percent">0%</div>
                              </div>
                              <td width="30%" align="left">
                                   <input type="submit" name="upload" class="btn btn-primary" value="Upload">
                              </td>
                         </tr>
                         <tr>
                              <td width="40%" align="right"></td>
                              <td width="30"><span class="text-muted">.xls, .xslx</span></td>
                              <td width="30%" align="left"></td>
                         </tr>
                    </table>
               </div>
          </form>

          <br />
          <div class="panel panel-default">
               <div class="panel-heading">
                    <h3 class="panel-title">Customer Data</h3>
               </div>
               <div class="panel-body">
                    <div class="table-responsive">
                         <table class="table table-bordered table-striped">
                              <tr>
                                   <th>Time Ref</th>
                                   <th>Account</th>
                                   <th>Code</th>
                                   <th>Product Type</th>
                                   <th>Value</th>
                                   <th>Status</th>
                              </tr>
                         </table>
                    </div>
               </div>
          </div>
     </div>
</body>
<script type="text/javascript">

</script>

<script type="text/javascript">
$(document).ready(function() {
     var dtCategoryTable = $('.category-list-table');
     if (dtCategoryTable.length) {
          var dtCategoryTableInit = $.ajax({
               url: 'api/import',
               type: 'GET',
               dataType: 'JSON',
               success: (function(response, responseStatus) {
                    if (responseStatus == 'success') {
                         $("#ajaxOutput").html(response);
                    }
               }),

               // Check for existence of file
               statusCode: {
                    404: function() {
                         $("#ajaxOutput")
                              .html("File does not exists!");
                    }
               },

               // If the time exceeds 5 seconds
               // then throw error message
               error: function(xhr, textStatus, errorThrown) {

                    if (textStatus == 'timeout') {
                         $("#ajaxOutput")
                              .html("Error : Timeout for this call!");
                    }
               }
          });


     }
     var bar = $('.bar');
     var percent = $('.percent');
     $('#upload_form').on('submit', function(e) {
          e.preventDefault();
          $.ajax({
               type: "POST",
               url: "import",
               data: new FormData(this),
               dataType: 'JSON',
               contentType: false,
               cache: false,
               processData: false,
               success: function(response) {
                    setTimeout(function() {
                         customer();
                    }, 10000);

               },
               error: function(error) {
                    console.log(error)

               },


          });

          var customer = $.ajax({
               url: 'api/import',
               type: 'GET',
               dataType: 'JSON',
               success: (function(response, responseStatus) {
                    if (responseStatus == 'success') {
                         $("#ajaxOutput").html(response);
                    }
               }),

               // Check for existence of file
               statusCode: {
                    404: function() {
                         $("#ajaxOutput")
                              .html("File does not exists!");
                    }
               },

               // If the time exceeds 5 seconds
               // then throw error message
               error: function(xhr, textStatus, errorThrown) {

                    if (textStatus == 'timeout') {
                         $("#ajaxOutput")
                              .html("Error : Timeout for this call!");
                    }
               }
          });


     });



});
</script>

</html>