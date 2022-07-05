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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('page-header')

<!-- PAGE-HEADER -->

<div>

	<h1 class="page-title">{{$title}}</h1>

	<ol class="breadcrumb">

		<li class="breadcrumb-item"><a href="{{ route('dashboard.category.index') }}">Category</a></li>
		@if(isset($category->id))
		<li class="breadcrumb-item active" aria-current="page">Edit</li>
	@else
		<li class="breadcrumb-item active" aria-current="page">Add</li>
	@endif


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



	<div class="card-body">
		<div class="d-flex justify-content-end">
			<a href="{{ route('dashboard.category.index') }}" class="btn btn-info-light ">Back</a>
		</div>
		

		<form method="POST" action="{{ route('dashboard.category.store') }}" enctype="multipart/form-data">

			@csrf

			<div class="row">

				<div class="col-6">	

					<input type="hidden" name="id" value="{{ isset($category) ? $category->id : '' }}">

					<div class="form-group">

						<label class="form-label">Title</label>

						<input type="text" class="form-control title" name="title" placeholder="Title" value="{{isset($category) ? $category->title : '' }}" required>
						
						<span class="text-danger">@error('title'){{"**".$message}}@enderror</span>
					</div>

				</div>

				<!-- <div class="col-6">	

					<div class="form-group">

						<label class="form-label">Status</label>

						<select name="status" class="form-control select2" required>

						<option value="" disabled>Select</option>

						<option value="active" {{isset($category) && ($category->status == 'active') ? 'selected' : '' }}>Active</option>

						<option value="pending" {{isset($category) && ($category->status == 'pending') ? 'selected' : '' }}>Pending</option>

						</select>

					</div>

				</div> -->
				<!-- @if(isset($category->id))
				<div class="col-6">
		          <div class="form-group">
		            <label class="control-label ">Commision</label>
		            <input type="text" class="form-control" id="exampleInputuname_1" name="commision" value="{{isset($category) ? $category->commision : '' }}">
		          </div>
		        </div>
				@else	
				@endif -->


				<div class="col-6">

					<div class="form-group">

						<label class="form-label">Select Parent</label>

						<select name="parent_cat" class="form-control select2" id="pc">

							<option value="0">No parent</option>

							@foreach($cat as $val)

							@if($val->level < 4)

								<option value ="{{$val->id}}" level="{{ $val->level }}" {{isset($category) && ($category->parent_id == $val->id) ?  'selected' : '' }}>{{$val->title}}</option>

							@endif

							@endforeach

						</select>

						<input type="hidden" id="cat-level" name="level" value="">

					</div>

				</div>

                                                 
				<div class="col-12">
					<div class="form-group">
						<label class="form-label">Discription</label>
						<textarea class="form-control discription" name="discription" rows="6" placeholder="text here.." required>{{isset($category) ? $category->discription : '' }}</textarea>
						<small class="text-danger discription_text"></small>
						
					</div>
				</div>

			</div>
			   <div class="col-md-6">
                                     <div class="col-md-6">

                                                        <label class="form-label">Status</label>

                                                        <input type="radio" name="status" value="enable" {{ isset($category) && $category->status == 'enable' ?  'checked' : ''}} checked>

                                                        <label>Enable</label>

                                                        <br>

                                                        <input type="radio" name="status" value="disable" {{ isset($category) && $category->status == 'disable' ? 'checked' : '' }} >

                                                        <label>Disable</label>

                                                    </div>
                                                  

			@if(isset($category->id))
		<button class="btn btn-success-light mt-3 submit" type="submit">Update</button>
	@else
		<button class="btn btn-success-light mt-3 submit" type="submit">Save</button>
	@endif

		</form>

	</div>

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

<script type="text/javascript">

	

	$(document).ready(function(){
		$("#myModal").css("z-index", "0");

		$('.submit').on('click',function(e){
        e.preventDefault();

        value = true;

     
        var title = $('.title').val();
        var discription = $('.discription').val();
        var price = $('.price').val();


        if (title == '') {
            $('.title_text').text('Please enter product name');
            value = false;
        }else{
            $('.title_text').text('');
        }

        if (discription == '') {
           $( ".discription_text" ).text('Please enter discription');
            value = false;
        }else{
            $('.discription_text').text('');
        }

        if (price == '' ) {
            $('.price_text').text('Please enter price');
            value = false;
        }else{
            $('.price_text').text('');
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

<script>

	$(document).ready(function() {

		$('#dataTable').DataTable();

	});

</script>

<script>

	$(document).ready(function() {

		$('#pc').change(function() {

			var element = $(this).find('option:selected'); 

			//var myTag = element.attr("myTag");

			$('#cat-level').val(element.attr('level'));

			$('#cat-level').attr('value',element.attr('level'));

  		});

	});

</script>









@endsection

