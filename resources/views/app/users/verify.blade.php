<!DOCTYPE html>
<html lang="en">
<head>
    <!-- MediNexa Account Verification -->
    <meta charset="utf-8">
    <title>Account Verification | MediNexa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MediNexa - AI-Powered Healthcare and Hospital Management Platform">

    <!-- The styles -->
    <link id="bs-css" href="css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="css/charisma-app.css" rel="stylesheet">


    <!-- jQuery -->
    <script src="bower_components/jquery/jquery.min.js"></script>

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">

</head>

<body>
<div class="ch-container">
    <div class="row">

        <div class="row">
            <div class="col-md-12 center login-header">
                <h2>Welcome to "{{$institute->name}}"</h2>
            </div>
            <!--/span-->
        </div><!--/row-->

        <div class="row">
            <div class="well col-md-5 center login-box">
                @if (Session::get('message'))
                    <div class="alert alert-success text-center">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <strong> {{ Session::get('message')}} </strong>

                    </div>
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ asset('img/medinexa-logo.svg') }}" style="height:64px;" alt="MediNexa Logo">
                </div>

                <form class="form-horizontal" action="{{url('/verified')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                            <input type="text" class="form-control" name="code" placeholder="Enter Code">
                        </div>
                        
                        <div class="clearfix"></div>

                    
                        <div class="clearfix"></div>
                        @if (isset($error))
                                <div class="alert alert-danger">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <strong>{{ $error }}.</strong>
                            </div>
                        @endif


                        <p class="center col-md-5">
                            <button type="submit" class="btn btn-primary">Verify</button>
                        </p>
                    </fieldset>
                </form>
            </div>
            <!--/span-->
        </div><!--/row-->
    </div><!--/fluid-row-->

</div><!--/.fluid-container-->

<!-- external javascript -->

<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


<script src="js/charisma.js"></script>


</body>
</html>
