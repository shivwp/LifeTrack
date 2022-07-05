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
                                <h1 class="page-title">Activity </h1>
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                                </ol>
                               
                            </div>
                            
                        
                        <!-- PAGE-HEADER END -->
@endsection

@section('content')
                        <!-- ROW-1 OPEN-->
                       




                                <div class="card">
                                    <div class="card-body">
<div class="d-flex justify-content-end">
            <a href="{{ route('dashboard.activity.index') }}" class="btn btn-info-light ">Back</a>
</div>

                 <form  method="post" action="{{route('dashboard.activity.store')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                       
                                            <div class="row">
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">User Name</label>
                                                      
                                                  <!--     <input type="text" class="form-control" name="username">-->
                                                       
                                                        <select  class="form-control" name="username">
                                                          <option>Select User </option>
                                                          @foreach($users as $name )
                                                        
                                                           <option value="{{$name->id}}">{{$name->first_name}} [<br>{{$name->email}} </br>]</option>

                                                           @endforeach


                                                                                                              
                                                        </select>
                                                    </div>


                                                    </div>

                                                    <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Activity Name</label>
                                                        <input type="text" class="form-control" name="activity" placeholder="Activity Name" value="{{ old('activity') }}">
                                                        <span class="text-danger discription_text">@error('activity'){{"**".'Activity  Field is Required'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                               


                                              
                                               

                                                 <div class="col-md-6">
                                                    <div class="form-group">

                                                        <label class="form-label" >Parent Category</label>
                                                        <select class="form-control" name="parent_catgory" id='parent_catgory'> 
                                                        
                                                          <option>select category</option>

                                                        @foreach($category as $cat)

                                                         <option value="{{$cat->id}}">{{$cat->title}}</option>
                                                        @endforeach
                                                        </select>
                                                        
                                                    </div>
                                                    
                                                </div>
                                        
                                          <div class="col-md-6">
                                                    <div class="form-group">

                                                        <label class="form-label" >Sub Category</label>
                                                        <select class="form-control" name="sub_category" id="subcategory"> 
                                                      
                                                        <option>select sub category</option>

                                                       <!--  @foreach($sub_category as $cat)

                                                          <option value="{{$cat->title}}">{{$cat->title}}</option>
                                                        @endforeach -->
                                                        </select>
                                                        
                                                    </div>
                                                    
                                                </div>
                                        
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Start Time</label>
                                                        <input type="time" class="form-control" id="currentTime" name="starttime" value="{{ old('starttime') }}">
                                                      <span class="text-danger discription_text">
                                                      @error('starttime'){{"**".'Start Time Field is Required'}}@enderror</span>
                                                       
                                                    </div>
                                                    
                                                </div>

                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">End  Time</label>
                                                        <input type="time" class="form-control" id="ecurrentTime"  name="endtime" value="{{ old('endtime') }}">
                                                        <span class="text-danger discription_text">
                                                      @error('endtime'){{"**".'End Time Field is Required and After Start Time'}}@enderror</span>
                                                       
                                                    </div>
                                                    
                                                </div>


                                                
                                                


                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                         <label class="form-label" >Select privacy</label>

                                                      <input type="radio"  name="selectprivacy" value="Public" checked>
                                                      
                                                      <label>Public</label>
                                                       </br>
                                                       <input type="radio"  name="selectprivacy" value="private">
                                                        
                                                       <label>Private</label>

                                                         

                                                    </div>
                                                </div>
                                                  
                                                 
                                              <div class="col-md-6">
                                                    <div class="form-group">
                                                      
                                                                <label class="form-label">Status</label>

                                                        <input type="radio" name="status" value="enable" checked>

                                                        <label>Enable</label>

                                                        <br>

                                                        <input type="radio" name="status" value="disable"  >

                                                        <label>Disable</label>

                                            
                                                </div>
                                            </div>

                                                 <div class="col-md-6">
                                                    <div >  
    
                                                    <label for="color">Color<span style="color: #F00"> *</span></label>
            
                                                     <input type="text" class="form-control colorpicker" name="selectcolor" value="{{old('selectcolor')}}" id="color">
                                                     <input type="hidden" class="form-control color_value" value="{{ isset($tag) ? $tag['selectcolor'] : '' }}" disabled>
                                                    <span class="text-danger discription_text">@error('selectcolor'){{"**".'Color picker  Field is Required'}}@enderror</span>



                                                 </div>
                                             </div>

                                           

                                           </div>
                                        </div>
                                            

                             
                                      

                                       
                                        <button type ="submit" class="btn btn-success-light mt-3 ">Add</button>
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function () {

    $('#parent_catgory').on('change',function(e) {
      var cat_id = e.target.value;
      $.ajax({
        url:"{{ route('dashboard.subcat') }}",
        type:"POST",
        data: {
          cat_id: cat_id
        },
        success:function (data) {
          $('#subcategory').empty();
          $('#subcategory').append('<option>select sub category</option>');
          $.each(data.subcategories,function(index,subcategory){
            $('#subcategory').append('<option value="'+subcategory.id+'">'+subcategory.title+'</option>');
          })
        }
      })
    });
  });
</script>

<script>


  $(".colorpicker").spectrum({
        allowEmpty: true,
        move: function (color) {
            $(this).parent().parent().next().find('.color_value').val(color.toHexString());
        }
    });

</script>

<script type="text/javascript">
    var date = new Date();

var currentTime = date.getHours() + ':' + date.getMinutes();
var ecurrentTime = date.getHours() + ':' + date.getMinutes();

document.getElementById('currentTime').value = currentTime;
document.getElementById('ecurrentTime').value = ecurrentTime;
</script>

@endsection
