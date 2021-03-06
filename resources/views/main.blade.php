@extends('welcome')

@section('content')
<div class="container">
	<nav>
	  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Customer Info</a>
	    <!-- <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Appointment</a>
	    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Payment Method</a> -->
	  </div>
	</nav>
	<div class="tab-content" id="nav-tabContent">
	  	<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
	  		@include('inner.innerhome')
		</div>
	  	<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
	  		@include('inner.innerprofile')
	  	</div>
	  	<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
	  		@include('inner.innercontact')
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function(){
		$('#datetimepicker1').datetimepicker();
		$('#datepickerbday').datetimepicker({
			format: "YYYY-MM-DD"
		});

		$('#mobilenumber').keyup(function(){
			var prefix = "63";
			if(this.value.indexOf(prefix) !== 0){
				this.value = prefix + this.value;
			}
		});

		getAppointmentType();

		// check availability
		$(document).on('click', '#check', function(){
			var appointment = $('#appointment').val();
			// console.log(appointment);
			$.ajax({
				url : "{{ url('api/checkdate') }}",
				method : "POST",
				datatype : "JSON",
				data : {
					appointment : appointment
				},
				success:function(r){
					if(r.response){
						toastr.info(r.message);
					}
				},
				error:function(r){
					console.log(r);
				}
			});
		});

		$(document).on('click', '#save', function(){
			var firstname = $('#firstname').val();
			var middlename = $('#middlename').val();
			var lastname = $('#lastname').val();
			var bday = $('#bday').val();
			var mobilenumber = $('#mobilenumber').val();
			var emailaddress = $('#emailaddress').val();
			var date = $('#appointment').val();
			var appointmentType = $('#appointmentType').val();

			$.ajax({
				url: "{{ url('api/appoint') }}",
				method: "POST",
				datatype: "JSON",
				data:{
					firstname : firstname,
					middlename : middlename,
					lastname : lastname,
					bday : bday,
					mobilenumber : mobilenumber,
					emailaddress : emailaddress,
					appointment : date,
					appointmentType : appointmentType
				},
				success:function(r){
					// console.log(r);
					if(r.response){
						toastr.success("Appointment Successful");
						$('#firstname').val('');
						$('#middlename').val('');
						$('#lastname').val('');
						$('#phonenumber').val('');
						$('#mobilenumber').val('');
						$('#emailaddress').val('');
						$('#appontment').val('');
					}
					if(!r.response){
                        // console.log("I went here");
                        toastr.error(r.message);
                    }
				},
				error:function(r){
					if(r.message = "The given data was invalid."){
						toastr.error("User Already Exist, Please Login");
					}				
				}
			});

		});

	});

	function getAppointmentType(){
		$.ajax({

			url : "{{ url('api/getAppointmentType') }}",
			method : "POST",
			dataType : "JSON",
			success:function(r){
				console.log(r);
				$.each(r.query, function(i, items){
					$('#appointmentType').append($('<option>', {
						value: items.id,
						text: items.name
					}));
				});
			},
			error:function(r){

			}

		});
	}

</script>
@endsection