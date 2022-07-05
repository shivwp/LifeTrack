@extends('layouts.vertical-menu.master')
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div><h1 class="page-title">Store Edit</h1></div>
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')
<div id="myModal" class="modal fade d-flex justify-content-center align-items-center">  
                                <div class="imgmyModal" >       
                                   <img src="{{ asset('assets/images/loader.gif') }}">
                                </div>
                        </div>
<div class="card">

  <div class="card-body" id="add_space">
     <div class="d-flex justify-content-end">
      <a href="{{ route('dashboard.vendorsettings.index') }}#" class="btn btn-info-light ">Back</a>
    </div>
    <form action="{{ route("dashboard.vendorsettings.store") }}" method="post" enctype="multipart/form-data">
      @csrf

      <input type="hidden" class="form-control" name="vendor_id" value="{{ isset($vendor) ? $vendor->id : '' }}">
      <div class="row">
             <!--  <div class="col-md-2">
          <div class="form-group">Profile Image </label>
            <input type="file" class="" id="exampleInputuname_1" name="profile_img" value="{{($data['profile_img'])??''}}">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
           <img class="vendor_image" src="{{url('')}}/images/vendor/settings/{{($data['profile_img'])??''}}" style="height:100px;width:100px;" alt="logo" >
          </div>
        </div>
         <div class="col-md-2">
          <div class="form-group">
            <label class="control-label ">Banner Image </label>
            <input type="file" class="" id="exampleInputuname_1" name="banner_img" value="{{($data['banner_img'])??''}}">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
              <img class="vendor_image" src="{{url('')}}/images/vendor/settings/{{($data['banner_img'])??''}}" style="height:100px;width:100px;" alt="logo" >
            
          </div>
        </div>
          @if(isset($vendor->id))
          <div class="col-md-12">
          <div class="form-group">
            <label class="control-label ">Commision </label>
            <input type="text" name="commision" class="form-control" value="{{($data['commision'])??''}}">
          </div>
        </div>          
        @else
      
        @endif
        -->
         <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Store Name </label>
            <input type="text" name="first_name" class="form-control first_name" value="{{($data['first_name'])??''}}" required>
            <small class="text-danger first_name_text"></small>
          </div>
        </div>
          <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Location </label>
            <input type="text" name="last_name" class="form-control location" value="{{($data['last_name'])??''}}" required>
            <small class="text-danger location_text"></small>
          </div>
        </div>
      </div>
      <!-- <div class="row">
          <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Store Name </label>
            <input type="text" name="store_name" class="form-control" value="{{($data['store_name'])??''}}" required>
          </div>
        </div>
      
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Store url </label>
            <input type="text" name="store_url" class="form-control " value="{{($data['store_url'])??''}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Phone Number </label>
            <input type="number" name="phone_number" class="form-control " value="{{($data['phone_number'])??''}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Email </label>
            <input type="email" class="form-control"  name="email" value="{{($data['email'])??''}}" required>
          </div>
        </div> -->
        <!--/span
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Username</label>
            <input type="text" class="form-control" name="user_name " value="{{($data['user_name'])??''}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Password </label>
            <input type="number" class="form-control"  name="password" value="{{($data['password'])??''}}" required>
          </div>
        </div>

        
      </div>
    <div class="row"> -->
        <!-- <div class="col-md-6">
        <h4 class="mt-5">Address</h4> <hr>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Street-1</label>
            <input type="text" class="form-control"  name="street_1" value="{{($data['street_1'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Street-2</label>
            <input type="text" class="form-control"  name="street_2" value="{{($data['street_2'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">City</label>
            <input type="text" class="form-control"  name="city" value="{{($data['city'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Zip</label>
            <input type="number" class="form-control"  name="zip" value="{{($data['zip'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Country</label>
            <input type="text" class="form-control"  name="country" value="{{($data['country'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">State</label>
            <input type="text" class="form-control"  name="state" value="{{($data['state'])??''}}">
          </div>
        </div>
      </div></div>
      <div class="col-md-6">
        <h4 class="mt-5">Social links</h4><hr>
        <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Instagram</label>
            <input type="text" class="form-control"  name="instagram" value="{{($data['instagram'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Youtube</label>
            <input type="text" class="form-control"  name="youtube" value="{{($data['youtube'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Twitter</label>
            <input type="text" class="form-control"  name="twitter" value="{{($data['twitter'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Linkedin</label>
            <input type="number" class="form-control"  name="linkedin" value="{{($data['linkedin'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Facebook</label>
            <input type="text" class="form-control"  name="facebook" value="{{($data['facebook'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Pinterest</label>
            <input type="text" class="form-control"  name="pinterest" value="{{($data['pinterest'])??''}}">
          </div>
        </div>
      </div>
      
      </div>
    
       </div>
       <h4>Payment Options</h4>
       <hr>
      <div class="row">
            <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Account Name </label>
            <input type="text" class="form-control" name="account_name" value="{{($data['account_name'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Account Number </label>
            <input type="number" class="form-control" name="account_number" value="{{($data['account_number'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Bank Name </label>
            <input type="text" class="form-control" name="bank_name" value="{{($data['bank_name'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">bank Number </label>
            <input type="text" class="form-control" name="bank_number" value="{{($data['bank_number'])??''}}">
          </div>
        </div>
          <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Swift Code </label>
            <input type="text" class="form-control" name="swift_code" value="{{($data['swift_code'])??''}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Routing Number </label>
            <input type="text" class="form-control" name="routing_number" value="{{($data['routing_number'])??''}}">
          </div>
        </div>
         <div class="col-md-12">
            <label class="switch">
              <input type="checkbox" id="selling" name="selling"  >
              <span class="slider round"></span>
            </label>
        
        <label for="scales">Enable Selling</label>
      </div>
        <div class="col-md-12">
            <label class="switch">
              <input type="checkbox" id="product_publish" name="product_publish" >
              <span class="slider round"></span>
            </label>
        
        <label for="scales">Publish Product direct</label>
      </div>
        <div class="col-md-12">
           <label class="switch">
              <input type="checkbox" id="feature_vendor" name="feature_vendor" >
              <span class="slider round"></span>
            </label>
       
        <label for="scales">Make feature vendor</label>
      </div>
       <div class="col-md-12">
        <label class="switch">
             <input type="checkbox" id="notify" name="notify" >
              <span class="slider round"></span>
            </label>
        
        <label for="scales">Send the vendor an email About their account</label>
      </div>
      </div>
    

    <div class="form-actions" id="add_space"> -->
      <button class="btn btn-success-light mt-3 submit">Save & update</button>
    </div>
    </div>
    </div>
  </form>
</div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
        $("#myModal").css("z-index", "0");

        $('.submit').on('click',function(e){
        e.preventDefault();

        value = true;

     
        var first_name = $('.first_name').val();
        var location = $('.location').val();

        if (first_name == '') {
            $('.first_name_text').text('Please enter a Store name');
            value = false;
        }else{
            $('.first_name_text').text('');
        }

        if (location == '' ) {
           $( ".location_text" ).text('Please enter store location');
            value = false;
        }else{
            $('.location_text').text('');
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
    })
</script>