@extends('layouts.app')
@section('content')
<section class="content-header">
	<h1>{{$pageTitle}}</h1>
</section>
<div class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-header-tools pull-left" >
				<a href="{{ url($pageModule.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}" >
					<i class="fa fa-arrow-left fa-2x"></i>
				</a>
			</div>
		</div>
		<div class="box-body">
			<ul class="parsley-error-list">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			{!! Form::open(array('url'=>$pageModule.'/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}


{{--			todo--}}
			{!! Form::hidden('expense_id', $row['expense_id']) !!}

			<div class="form-group">
					<label class="col-md-2 control-label">Name/Title <span class="text-danger"></span></label>
					<div class="col-md-3">
						<input type="text" class="form-control" value="{{$row['expense_name']}}" name="expense_name" required="" data-parsley-id="4">
					</div>
					<label for="Date" class=" control-label col-md-2 text-left"> {{Lang::get('core.date')}}<span class="asterix"></span></label>
					<div class="col-md-3">
						<div class="input-group m-b">
							{!! Form::text('date', $row['date'],array('class'=>'form-control date')) !!}
							<span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
						</div>
					</div>
			</div>

			<div class="form-group">

				<label for="Accounts" class="col-md-2 control-label">{{Lang::get('Account')}} <span class="asterix"> * </span></label>
				<div class="col-md-3">
					<div  class="input-group">
						<select name='account_id'  rows='5' id='account_id'  class=" form-control select_box select2" style="width: 100%" required  ></select>


							<div class="input-group-addon" title="" data-toggle="tooltip" data-placement="top" data-original-title="New Accounts">
								<a data-toggle="modal"
								   data-target="#myModal"
								   onclick="MmbModal(this.href,'Add New'); return false; "
								   href=
								   "{{URL::to( "accounts" .'/update-ajax?return='.$return)}}"
								>
									<i class="fa fa-plus"></i>
								</a>
							</div>
						
					</div>
				</div>
				<label for="payment_method " class=" control-label col-md-2 text-left"> {{Lang::get('Payment Method')}} <span class="asterix"></span></label>
				<div class="col-md-3">
					<div  class="input-group">

					<select name='payment_id'  rows='5' id='payment_id' class=" form-control select_box select2" style="width: 100%" ></select>
					
					<div class="input-group-addon" title="" data-toggle="tooltip" data-placement="top" data-original-title="New Accounts">
						<a data-toggle="modal"
						   data-target="#myModal"
						   onclick="MmbModal(this.href,'Add New'); return false; "
						   href=
						   "{{URL::to( "paymenttypes" .'/update-ajax?return='.$return)}}"
						>
							<i class="fa fa-plus"></i>
						</a>
					</div>
			</div>
		</div>
				
		</div>
		{{-- <div class="form-group" style="display: none">
			<label class="col-md-2 control-label">Status<span class="text-danger"></span></label>
			<div class="col-md-3">
				<input type="text" class="form-control" value="1" name="status" ="" data-parsley-id="4">
			</div>
			<div style="display: none">
				<label class="col-md-2 control-label">Action<span class="text-danger"></span></label>
				<div class="col-md-3">
					<input type="text" class="form-control" value="{{$row['action']}}" name="action" required="" data-parsley-id="4">
				</div>
			</div>

	</div> --}}

	<div class="form-group">
		<label class="col-md-2 control-label">Amount<span class="text-danger">*</span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" value="{{$row['amount']}}" name="amount"  data-parsley-id="4">
		</div>

		<label class="col-md-2 control-label">Paid By </label>
		<div class="col-md-3">
			<div class="input-group">
				<select class="form-control select_box" style="width: 100%"
				id='paid_by' name="paid_by">

				</select>
				
				<div class="input-group-addon" title="" data-toggle="tooltip" data-placement="top" data-original-title="New Accounts">
					<a data-toggle="modal"
					   data-target="#myModal"
					   onclick="MmbModal(this.href,'Add New'); return false; "
					   href=
					   "{{URL::to( "core/users" .'/update-ajax?return='.$return)}}"
					>
						<i class="fa fa-plus"></i>
					</a>
				</div>
				
			</div>
		</div>

		{{-- <label class="col-md-2 control-label">Reference<span class="text-danger"></span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" value="{{$row['expense_reference']}}" name="expense_reference"  data-parsley-id="4">
		</div> --}}
	</div>
		<div class="form-group ml-9">
			<label class="col-md-2 control-label">Notes<span class="text-danger">*</span></label>
			<div class="col-md-3">
				<textarea type="text" class="form-control" value="{{$row['expense_notes']}}" name="expense_notes"  data-parsley-id="4">{{$row['expense_notes']}}</textarea>
			</div>

			<label for="Categories" class="col-md-2 control-label">{{Lang::get('Category')}} <span class="asterix"> * </span></label>
			<div class="col-md-3">
				<div  class="input-group">
					{{-- <select class="form-control select_box" style="width: 100%"
					id='category_id' name="category_id"> --}}
					<select name='category_id'  rows='5' id='category_id'  class=" form-control select_box select2" style="width: 100%" required  ></select>


						<div class="input-group-addon" title="" data-toggle="tooltip" data-placement="top" data-original-title="New Category">
							<a data-toggle="modal"
							   data-target="#myModal"
							   onclick="MmbModal(this.href,'Add New'); return false; "
							   href=
							   "{{URL::to( "categories" .'/update-ajax?return='.$return)}}"
							>
								<i class="fa fa-plus"></i>
							</a>
						</div>
					
				</div>
			</div>

			</div>



			<div class="form-group" >
				<label for="Recur Every" class=" control-label col-md-2 text-left">Recur Every</label>
				<div class="col-md-3">
					<select name="recur_frequency" id="recuring_frequency" class="form-control" data-parsley-id="6">
						<option value="none" @if($row['recur_frequency'] == "none") selected @endif>None</option>
						<option value="7D" @if($row['recur_frequency'] == "7D") selected @endif>Week</option>
						<option value="1M" @if($row['recur_frequency'] == "1M") selected @endif>Month</option>
						<option value="3M" @if($row['recur_frequency'] == "3M") selected @endif>Quarter</option>
						<option value="6M" @if($row['recur_frequency'] == "6M") selected @endif>Six Monthly</option>
						<option value="1Y" @if($row['recur_frequency'] == "1Y") selected @endif>One year</option>
						<option value="2Y" @if($row['recur_frequency'] == "2Y") selected @endif>Two year</option>
						<option value="3Y" @if($row['recur_frequency'] == "3Y") selected @endif>Three year</option>
					</select>
				</div>

				<div id="cycle_input">
					<label for="cycle" class=" control-label col-md-2 text-left">  {{Lang::get('Cycles')}} <span class="asterix"></span></label>
					<div class="col-md-3">
					  
						<label class='radio radio-inline'>
						<input id="cycle_input" type="number" class="form-control" value="{{$row['cycle']}}" name="cycle"  data-parsley-id="4">
						{{-- <input type='checkbox' name='cycle' value ='0' checked="checked"  >  {{Lang::get('core.fr_minactive')}} </label> --}}
	
					 </div> 
				</div>
	   </div>

	   <label for="Attachment" class=" control-label col-md-2 text-left"> {{ Lang::get('Attachment') }}</label>
	   <div class="col-md-3">
		   <div class="btn btn-primary col-md-7 btn-file"><i class="fa fa-upload fa-2x"></i> {{ Lang::get('core.selectfile') }}
			   <input  type='file' name='attachment' id='attachment' @if($row['attachment'] =='') @endif style='width:150px !important;'  /></div>
			   <div >
			   @if(file_exists('./uploads/images/'.$row['attachment']) && $row['attachment'] !='')
			   <span class="pull-left removeMultiFiles "  url="/uploads/images/{{$row['attachment']}}">
			   <i class="fa fa-trash-o fa-2x text-red " data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}" data-content="{{ Lang::get('core.youwanttodeletethis') }}" title="{{ Lang::get('core.deletethisimage') }}" ></i>
			   </span>
			   {!! SiteHelpers::showUploadedFile($row['attachment'],'/uploads/images/') !!}
			   @endif

			   </div>					

			  </div> 
			  <div style="clear:both"></div>
			  <div class="form-group">
				  <label class="col-sm-4 text-right">&nbsp</label>
				  <div class="col-sm-8">
				  <button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button>
				  <button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
				  <button type="button"
						  onclick="location.href='{{ URL::to($pageModule.'?'.'&return='.$return) }}' "
						  class="btn btn-danger btn-sm ">
					  {{ Lang::get('core.sb_cancel') }}
				  </button>
				  </div>
			  </div>
   </div>
				   {!! Form::close() !!}

				</div>
			
				<!-- End discount Fields -->
				{{-- <div class="form-group terms">
					<label class="col-lg-3 control-label">Description </label>
					<div class="col-lg-5">
						<textarea name="description" class="form-control" data-parsley-id="6">{{$row['description']}}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Initial Balance <span class="text-danger">*</span></label>
					<div class="col-lg-5">
						<input type="text" data-parsley-type="number" class="form-control" value="{{$row['balance']}}" name="balance" required="" data-parsley-id="8">
					</div>
				</div> --}}


        </div>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$("#cycle_input").hide();

	$("#account_id").jCombo("{!! url('expense/comboselect?filter=tbl_accounts:account_id:account_name') !!}",
			{  selected_value : '{{ $row["account_id"]  }}'
			
		});

	$("#payment_id").jCombo("{!! url('expense/comboselect?filter=def_payment_types:paymenttypeID:payment_type') !!}",
	{  selected_value : '{{ $row["payment_id"]  }}'
	
	});

	$("#paid_by").jCombo("{!! url('expense/comboselect?filter=tb_users:id:username') !!}",
	{  selected_value : '{{ $row["paid_by"]  }}'
	
	});

	$("#category_id").jCombo("{!! url('expense/comboselect?filter=tbl_categories:category_id:category_name') !!}",
	{  selected_value : '{{ $row["category_id"]  }}'
	
	});

	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',

	});



	$("#recuring_frequency").change(function(){
		console.log($('#recuring_frequency option:selected').val());

		if($('#recuring_frequency option:selected').val() == "none"){
			console.log("none");
			$("#cycle_input").hide();

			// $('#cycle_input').attr('disabled', 'disabled');

		}
		else{
			console.log("other");
			$("#cycle_input").show();
			// $('#cycle_input').removeAttr('disabled');
		}
	});

	$('.removeMultiFiles').on('click',function(){
	var removeUrl = '{{ url("invoice/removefiles?file=")}}'+$(this).attr('url');
	$(this).parent().remove();
	$.get(removeUrl,function(response){});
	$(this).parent('div').empty();
	return false;
	});

	$("#chkPassport").on('click',function () {
            if ($(this).is(":checked")) {
				console.log("chackeeeeeed");
                $("#txtPassportNumber").removeAttr('disabled');
                $("#txtPassportNumber").focus();
            } else {
                $("#txtPassportNumber").prop('disabled','disabled');
            }
        });
	});



</script>
@stop
