<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../resources/css/register.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">



</head>
<!-- -->
<div class="formdiv"> <body>
    <h1 style="text-align: center; padding-top: 5%;font-size: 50px; color: #4C4C4F; padding-right: 3%;">Sign Up</h1>
    
    <!--CHECK IF THERE ARE ANY ERRORS -->

    @if($errors->any())
    @foreach ($errors->all as $err)
    <li>{{$err}}</li>
    @endforeach
    @endif

    <!---->
    <?php $successmsg = Session::get('successmsg'); ?>
    <h2 style="text-align: center; padding-right: 3%; font-size: 25px; color: green; font-style: italic ">{{$successmsg }}</h2>
    <div class="form">
        <form action="{{ url('register') }}" method="POST" id="form">
            @csrf
            <input type="text" name="username" placeholder="Username"><br>
            <span style="color: red">@error('username'){{$message}}@enderror</span>


            <input type="password" name="password" placeholder="Password" id="password"></input>
            <i class="far fa-eye" id="togglePassword"></i><br>
            <span style="color: red">@error('password'){{$message}}@enderror</span>


            <input type="email" name="email" placeholder="Email"><br>
            <span style="color: red">@error('email'){{$message}}@enderror</span>
            <br><br>

            <button class="button button1">Sign up</button> 
            <br>
            <a href="{{ url('login') }}" style="text-align: center; font-size: 15px; color: grey; padding-left: 25%" >Already have an account? 
            </form> 
        </div>
        <script src="../resources/js/app.js"></script>


    </body></div>

    <style type="text/css">
       /*.formdiv {
        align-content: center;
        height: 800px;
        margin-top: 5%;
        margin-left: 15%;
        margin-right: 15%;
        border-radius: 25px;
        border: 5px solid;
        background-color: #24323D;
        box-sizing: border-box;
    }*/
</style>
</html>