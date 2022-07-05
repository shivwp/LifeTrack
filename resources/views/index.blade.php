@extends('layouts.vertical-menu.master')
@section('css')
<style>
	.bg-secondary {
    background-color: #d43f8d!important;
}
</style>
@endsection
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div>
                                <h1 class="page-title">Dashboard</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </div>
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')
						<!-- ROW-1 -->
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
								<div class="row">
									<div class="col-lg-3 col-md-12 col-sm-12 col-xl-3">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<a href="{{ route('dashboard.users.index') }}">
													<div class="counter-icon bg-primary mb-0 box-primary-shadow">
														<i class="fe fe-user-plus text-white"></i>
													</div>
													<h6 class="mt-4 mb-1">Total User</h6>
													<h2 class="mb-2 number-font usercount"></h2>
													<p class="text-muted"></p>
												</a>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 col-sm-12 col-xl-3">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<a href="{{--route('dashboard.vendorsettings.index') --}}">
													<div class="counter-icon bg-secondary mb-0 box-secondary-shadow" >
														<i class="fe fe-users text-white"></i>
													</div>
													<h6 class="mt-4 mb-1">Total Journal</h6>
													<h2 class="mb-2 number-font Journalcount"></h2>
													<p class="text-muted"></p>
												</a>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 col-sm-12 col-xl-3">
										<div class="card">
											
											<div class="card-body text-center statistics-info">
												<a href="#">
												<div class="counter-icon bg-success mb-0 box-success-shadow">
													<i class="icon icon-people text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Total Groups</h6>
												<h2 class="mb-2  number-font "> 5</h2>
												<p class="text-muted"></p>
											</a>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 col-sm-12 col-xl-3">
										<div class="card">
											
											<div class="card-body text-center statistics-info">
												<a href="#">
												<div class="counter-icon bg-info mb-0 box-info-shadow">
													<i class="ion-pricetag text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Total Tags</h6>
												<h2 class="mb-2  number-font ">10</h2>
												<p class="text-muted"></p>
											</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							
                   
							<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
								<div class="row">
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card border-none">

							
                                                     <h4 class="mt-4 mb-1  text-blue text-center"> Latest User</h4>
                                                     <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12" 
                                                    >
                                                      <a href="{{ route('dashboard.users.index') }}" class="btn btn-info-light "  style="float:right; margin-top: -30px; margin-bottom: 15px;">Show all</a>
                                                    
                                                    </div>
											
                                        
                                          

                                            <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="wd-15p">Name</th>
                                                        <th class="wd-15p">Email</th>
                                                        
                                                         <th class="wd-15p">Roles</th>
                                                       
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                @if(count($users_dash)>0)
                                                    @foreach($users_dash as $key => $item)
                                                        <tr>
                                                            <td>{{ $item->id ?? '' }}</td>
                                                            <td>{{ $item->first_name ?? '' }}</td>
                                                            <td>{{ $item->email ?? '' }}</td>
                                                            
                                                            <td>
                                                            @foreach($item->roles as $key => $item1)
                                                                <span class="badge badge-info">{{ $item1->title }}</span>
                                                            @endforeach
                                                            </td>

                                                           
                                                            
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
											

											
											
										</div>
									</div>
									
									
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card">

											<h4 class="mt-4 mb-1 text-center text-blue "> Latest Journal</h4>
	                                                  	
                                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                                                         <a href="{{route('dashboard.journalmang.index') }}" class="btn btn-info-light "style="float:right; margin-top: -30px; margin-bottom: 15px;" >Show all</a>
                                                     </div>
											

											
                                          

                                           <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                                                <thead>
                                    

                                                    <tr>
                                                        <th class="wd-15p">S.no</th>
                                                        <th class="wd-15p">Name</th>
                                                        
                                                        <th class="wd-15p">Date</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody" >
                                                        @php
                                                        $i = 1;
                                                        @endphp
                                                        @foreach($Journal as $vale => $key)
                                                    <tr>
                                                        
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $key->name }}</td>
                                                        
                                                         <td>{{ \Carbon\Carbon::parse($key->date)->format('Y-m-d')}}</td>
                                                         

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
											
										</div>
									</div>
								</div>
							
				

						
					</div>
				</div>
				<!-- CONTAINER END -->
            </div>

<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
         type:'GET',
         url:'{{ route("dashboard.update-user-count","view")}}',
         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
         success:function(data){
            $('.usercount').text(data);
         }
      });
	})
</script>

<script type="text/javascript">
	  $(document).ready(function(){
		$.ajax({
         type:'GET',
         url:'{{ route("dashboard.update-journaluser-count","view")}}',
         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
         success:function(data){
            $('.Journalcount').text(data);
         }
      });
	}) 
</script>

<script type="text/javascript">
	 /*$(document).ready(function(){
		$.ajax({
         type:'GET',
         url:'{{-- route("dashboard.update-list-count","view")--}}',
         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
         success:function(data){
            $('.listcount').text(data);
         }
      });
	}) */
</script>
<script type="text/javascript">
	/* $(document).ready(function(){
		$.ajax({
         type:'GET',
         url:'{{-- route("dashboard.update-product-count","view")--}}',
         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
         success:function(data){
            $('.prductcount').text(data);
         }
      });
	}) */
</script>




@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/apexcharts/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/index1.js') }}"></script>
@endsection




