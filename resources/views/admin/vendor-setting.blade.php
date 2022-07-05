@extends('layouts.vertical-menu.master')
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div><h1 class="page-title">Add Store</h1></div>
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')
<!-- <div id="myModal" class="modal fade d-flex justify-content-center align-items-center">  
                                <div class="imgmyModal" >       
                                   <img src="{{ asset('assets/images/loader.gif') }}">
                                </div>
                        </div> -->
<div class="card">
  <div class="card-body" id="add_space">
    <div class="d-flex justify-content-end">
      <a href="{{ route('dashboard.vendorsettings.index') }}#" class="btn btn-info-light ">Back</a>
    </div>


    <form action="{{ route("dashboard.vendorsettings.store") }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
           
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label ">Store Name </label>
                <input type="text" name="first_name" class="form-control first" value="{{($data['first_name'])??''}}" required>
                <small class="text-danger first_name_text"></small>
              </div>
            </div>
              <div class="col-md-6">
              <div class="form-group">
                <label class="control-label ">Location</label>
                <input type="text" name="last_name" class="form-control loc" value="{{($data['last_name'])??''}}" required>
                <small class="text-danger location_text"></small>
              </div>
            </div>
      </div>

        <button class="btn btn-success-light mt-3 submit">Save & update</button>
      </div>
    </form>
   
</div>
</div>
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/datatable.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
        $("#myModal").css("z-index", "0");


    $('.submit').on('click',function(e){
        e.preventDefault();

        value = true;

     
        var first = $('.first').val();
        var loc = $('.loc').val();


        if (first == '') {
            $('.first_name_text').text('Please enter product name');
            value = false;
        }else{
            $('.first_name_text').text('');
        }

        if (loc == '') {
           $( ".location_text" ).text('Please enter discription');
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