@extends('layouts.vertical-menu.master')

@section('css')

<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">

<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet">

<link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/date-picker/spectrum.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

@endsection

@section('page-header')

                        <!-- PAGE-HEADER -->

                            <div>

                                    <h1 class="page-title"> 
                                        <?php
                                            if (isset($user->id) && $user->id == 1 ) {
                                                echo "Admin Edit";
                                            }else{
                                                echo "User Edit";
                                            }
                                        ?>
                                    </h1>

                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.users.index') }}">User</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?php
                                            if (isset($user->id)) {
                                                echo "Edit";
                                            }else{
                                                echo "Add";
                                            }
                                        ?>
                                    </li>

                                </ol>

                            </div>

                        

                        <!-- PAGE-HEADER END -->

@endsection



@section('content')
<div id="myModal" class="modal fade d-flex justify-content-center align-items-center">  
                                <div class="imgmyModal" >       
                                   <img src="{{ asset('assets/images/loader.gif') }}">
                                </div>
                        </div>
                        <!-- ROW-1 OPEN-->

                                <div class="card">

                                <form  method="post" action="{{route('dashboard.users.store')}}" enctype="multipart/form-data">

                                    @csrf

                                    <div class="card-body">
<div class="d-flex justify-content-end">
            <a href="{{ route('dashboard.users.index') }}#" class="btn btn-info-light ">Back</a>
        </div>
                                        <input type="hidden" name="id" value="{{ old('id', isset($user) ? $user->id : '') }}">

                                            <div class="row">

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">First Name</label>

                                                        <input type="text" class="form-control first_name" name="first_name" placeholder="First Name" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" required>
                                                        <small class="text-danger first_name_text"></small>
                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Last Name</label>

                                                        <input type="text" class="form-control last_name" name="last_name" placeholder="Last Name" value="{{ old('last_name', isset($user) ? $user->last_name : '') }}" required>
                                                        <small class="text-danger last_name_text"></small>
                                                    </div>

                                                </div>

                                                  <div class="col-md-6">

                                                <div class="form-group">

                                                    <label class="form-label">Email</label>

                                                    <input type="email" class="form-control email" name="email" placeholder="Email" value="{{ old('email',isset($user) ? $user->email : '') }}" required>
                                                    <span class="text-danger email_text">@error('email'){{ "**"."Email already exists" }}@enderror</span>
                                                </div>

                                                </div>

                                                 <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Password</label>

                                                        <input type="password" class="form-control password" name="password" placeholder="Password" value="" required>
                                                        <span class="text-danger password_text">@error('password'){{'**'.$message }}@enderror</span>

                                                    </div>

                                                 </div>
                                                
                                                 <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="form-label">Select Role</label>

                                                        <select name="role" id="role" class="form-control select2">

                                                            @foreach($roles as $id => $role)

                                                                <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>

                                                            @endforeach

                                                        </select>

                                                    </div>
                                                   </div>
                                                    <div class="col-md-6">
                                                        
                                                       
                                                        <label class="form-label">Status</label>

                                                        <input type="radio" name="status" value="1" {{ isset($user) && $user->status == '1' ?  'checked' : ''}} checked>

                                                        <label>Enable</label>

                                                        <br>

                                                        <input type="radio" name="status" value="0" {{ isset($user) && $user->status == '0' ? 'checked' : '' }} >

                                                        <label>Disable</label>
                                                    

                                                    </div>
                                                     <div class="col-md-6">
                                                        
                                                       
                                                        <label class="form-label">Verified User</label>

                                                        <input type="radio" name="verfy_user" value="disable" {{ isset($user) && $user->verfy_user == 'disable' ?  'checked' : ''}} checked>

                                                        <label>Disable</label>

                                                        <br>

                                                        <input type="radio" name="verfy_user" value="enable" {{ isset($user) && $user->verfy_user == 'enable' ? 'enable' : '' }} >

                                                        <label>Enable</label>
                                                    

                                                    </div>
                                        

                                                </div>

                                                

                                            </div>

                                       

                                      @if(isset($user->id))
                                                <button class="btn btn-success-light mt-3 submit" type="submit">Update</button>
                                            @else
                                                <button class="btn btn-success-light mt-3 submit" type="submit">Save</button>
                                            @endif

                                    </div>



                                </form>

                                    

                                </div>                  

@endsection

@section('js')

<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/date-picker/spectrum.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/time-picker/toggles.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

<script>

    $(document).ready(function() {

          $('#dataTable').DataTable();

    });

</script>

<script type="text/javascript">

    $(document).ready(function(){
        $("#myModal").css("z-index", "0");

        $('.submit').on('click',function(e){
        e.preventDefault();

        value = true;

     
        var first_name = $('.first_name').val();
        var last_name = $('.last_name').val();
        var email = $('.email').val();
       


        if (first_name == '') {
            $('.first_name_text').text('Please first enter name');
            value = false;
        }else{
            $('.first_name_text').text('');
        }

        if (last_name == '') {
           $( ".last_name_text" ).text('Please last name ');
            value = false;
        }else{
            $('.last_name_text').text('');
        }

        if (email == '' ) {
            $('.email_text').text('Please enter email');
            value = false;
        }else if(IsEmail(email) == false){
             $('.email_text').text('Please enter Valid email');
            value = false;
        }
        else{
            $('.email_text').text('');
        }
        
     
        
        if (value == true) {
            
            setTimeout(function () { disableButton(); }, 0);
            function disableButton() {
                $(".submit").prop('disabled', true);
            }
                $('form').unbind('submit').submit();
                $("#myModal").css("z-index", "999");
                $("#myModal").modal('show');
        }
    })
        function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }

    })

</script>

@endsection

