var stepTwoOptions = {
	success:       stepTwoShowResponse  // post-submit callback
};

var dialogOptions = {
    modal: true,
    closeOnEscape: true,
    position: ['center',100],
		create: function(event, ui) {
		//window.scrollTo(0, 0);
		$(".ui-dialog-titlebar-close").hide();
		$(event.target).parent().css('position', 'absolute');
		$(event.target).css('position', 'fixed');
    }
}


$(document).ready(function(){

	$(".magento .cvv-what-is-this").click(function(){

		$("#payment-tool-tip").dialog(dialogOptions);
	});


	$(document).on('click', ".btn-close", function(){

		$("#payment-tool-tip").dialog('close');
	});

	//REGION SELECTION
	$(".select option").hide();
	$(".select option[country_id=US]").show();


	//on change country
	$(".country").change(function(){

		if($(this).hasClass('ship')){

			var cls = '.ship';

		} else {

			var cls = '.bill';

		}

		$(cls + ".select").val(0);
		$(cls + ".text").val('');
		$(cls + ".id").val(0);

		$(cls + ".select option").hide();

		var country_id = $(this).val();
		var count = $(cls + ".select option[country_id=" + country_id +"]").length;

		if(count){
			$(cls + ".select").show();
			$(cls + ".select option[country_id=" + country_id +"]").show();
			$(cls + ".text").hide();
		} else {
			$(cls + ".text").show();
			$(cls + ".select").hide()
		}

		if(count){
			$(".bill.select").show();
			$(".bill.select option[country_id=" + country_id +"]").show();
			$(".bill.text").hide();
		} else {
			$(".bill.text").show();
			$(".bill.select").hide()
		}

	});


	//on change region select
	$(document).on('change', ".select", function(){

		if($(this).hasClass('ship')){

			var cls = '.ship';

		} else {

			var cls = '.bill';

		}

		$(cls + ".text").attr('value',$(cls + ".select option:selected").text());
		$(cls + ".id").val($(this).val());

		$(".bill.text").attr('value',$(cls + ".select option:selected").text());

		$(".bill.id").attr('value', $(this).val());

		$(".bill.id").val($(this).val());

	});

	//on change region text
	$(document).on('change', "input.text", function(){

		if($(this).css('display') != 'none'){

			console.log($(this).attr('id'));
			console.log($(this).attr('class'));
			console.log($(this));

			if($(this).hasClass('ship')){

				var cls = '.ship';

			} else {

				var cls = '.bill';

			}

			$(cls + ".id").val(0);
			$('.bill .id').val(0);

		}

	});


	$('#stepOneSubmit').click(function(e){

		e.preventDefault();

		$('.errors div').html('');

		var errors = '';

		//check required fields
		$('.magento .stepOne input').each(function(){

			var id = $(this).attr('id');
			var val = $(this).attr('value');
			if(!val){val = $(this).val();}

			var confirmation = $('.magento #confirmation').html();
			confirmation = confirmation.replace('{' + id + '}',val);
			$('.magento #confirmation').html(confirmation);



			if(!val && $(this).hasClass('required')){

				var label = $('label[for=' + $(this).attr('name') + ']').html();
				errors += '<div>' + label + ' is required.</div>';

			} else {
				$('.magento #bill_' + id).attr('value', val);


				$('.magento #confirmation_' + id).html(val);

			}

		});

		$('.magento .stepOne select').each(function(){

			var id = $(this).attr('id');
			var val = $(this).val();

			var confirmation = $('.magento #confirmation').html();
			confirmation = confirmation.replace('{' + id + '}',val);
			$('.magento #confirmation').html(confirmation);


			if(!val && $(this).hasClass('required')){

				var label = $('label[for=' + $(this).attr('name') + ']').html();
				errors += '<div>' + label + ' is required.</div>';
			} else {
				$('.magento #bill_' + id).val(val);
				$('.magento #confirmation_' + id).html(val);

			}

		});

		if($('#email').val() != $('#confirm_email').val()){
			errors += '<div>Email does not match Confirm Email</div>';
		}

		$('.magento #confirmation_region').html($("#region_id option:selected").text());
		$('.magento #confirmation_country').html($("#country_id option:selected").text());

		$('.magento #cc_owner').attr('value', $('.magento #firstname').val() + ' ' + $('.magento #lastname').val());

		if(!errors){

			$('.stepOne').hide();
			$('.stepTwo').show();


		} else {

			$('.errors div').html(errors);

		}

		$(window).scrollTop(0);

		return false;
	});

	$('#stepTwoSubmit').click(function(e){

		e.preventDefault();


		$('.errors div').html('');

		var confirmation = $('.magento #confirmation').html();

		var errors = '';

		//check required fields
		$('.magento .stepTwo input').each(function(){

			var id = $(this).attr('id');
			var val = $(this).val();
			if(!val){
				val = $(this).attr('value');
			}

			var confirmation = $('.magento #confirmation').html();
			if(confirmation){
				confirmation = confirmation.replace('{' + id + '}',val);
				$('.magento #confirmation').html(confirmation);
			}

			if($(this).hasClass('required')){

				if(!val){

					var label = $('label[for=' + $(this).attr('name') + ']').html();

					errors += '<div>' + label + ' is required.</div>';

				}
			}
		});

		$('.magento .stepTwo select').each(function(){

			var id = $(this).attr('id');
			var val = $(this).val();

			var confirmation = $('.magento #confirmation').html();
			if(confirmation){
				confirmation = confirmation.replace('{' + id + '}',val);
				$('.magento #confirmation').html(confirmation);
			}

			if($(this).hasClass('required')){

				if(!val){

					var label = $('label[for=' + $(this).attr('name') + ']').html();
					errors += '<div>'+ label + ' is required.</div>';
				}
			}

		});

		var d = new Date();
		var month = d.getMonth();
		var year = d.getYear();

		var check_year = $('.magento .stepTwo #cc_exp_year').val();
		var check_month = $('.magento .stepTwo #cc_exp_month').val();

		if((check_year < year) || (year == check_year && check_month < month)){

			errors += '<div>Please provide a current expiration month and year.</div>';
		}

		$('.magento #confirmation_cc_owner').html($('.magento #cc_owner').val());

		var cc_number = $('.magento #cc_number').val();

		var cc_number_hidden = '';

		for(var i = 0; i < cc_number.length; i++){
			if(i + 4 < cc_number.length){
				var val = 'x';
			} else {
				var val = cc_number[i];
			}
			cc_number_hidden += val;
		}

		$('.magento #confirmation_cc_number').html(cc_number_hidden);

		if(!errors){

			$('#processing').show();
			$("#stepTwoSubmit").html('Processing...');
			$('#stepTwoSubmit').attr('disabled', 'disabled');
			$('#stepTwoBack').attr('disabled', 'disabled');

			//process card

			$('#magentoForm').ajaxSubmit(stepTwoOptions);
			//stepTwoShowResponse(null, null, null, null);


		} else {

			$('.errors div').html(errors);
			$('#ajax_loader').dialog("close");
			$(window).scrollTop(0);
		}




		return false;

	});

	$('#stepTwoBack').click(function(e){

		e.preventDefault();

		$('.stepTwo').hide();
		$('.stepOne').show();
		$(window).scrollTop(0);

		return false;

	});

});

function stepTwoShowResponse(responseText, statusText, xhr, $form)  {

	$('#processing').hide();

	$("#stepTwoBack").removeAttr("disabled");
	$("#stepTwoSubmit").removeAttr("disabled");
	$("#stepTwoSubmit").html('Submit Payment Now');


	if(responseText.indexOf('Error:<br />') > -1){

		$('.errors div').html('<div>' + responseText + '</div>');

	} else {

		//$('.errors div').html('<div>no response</div>');
		$('.stepTwo').hide();

		//if jobs, submit preview form
		if($("input[name=sku]").attr('value') == 'job'){

			//$("form#job_preview").submit();
			$('#job_preview_submit_button').trigger('click');
		} else {


		//else
			$('.stepThree').show();
		}

	}
	$(window).scrollTop(0);
	//$('#ajax_loader').dialog("close");

}

/*function checkCc(){

	var ccNumber = $('#cc_number').attr('value');
	var ccFrontOne = ccNumber.substring(0,0);
	var ccFrontTwo = ccNumber.substring(0,1);
	var ccFrontFour = ccNumber.substring(0,3);

	var ccType = false;

	if(ccFrontTwo == 34 ||ccFrontTwo == 37){
		ccType = 'Amex';
	} else if(ccFrontTwo >= 51 && ccFrontTwo <= 55){
		ccType = 'mastercard';
	} else if(ccFrontFour == 6011){
		ccType = 'Discover';
	} else if(ccFrontOne == 4){
		ccType = 'visa';
	}

	return ccType;
}

function checkExp(){

	var month = $('#cc_exp_month').attr('value');
	var year = $('#cc_exp_year').attr('value');
	var ccFrontOne = ccNumber.substring(0,0);
	var ccFrontTwo = ccNumber.substring(0,1);
	var ccFrontFour = ccNumber.substring(0,3);

	var ccType = false;

	if(ccFrontTwo == 34 ||ccFrontTwo == 37){
		ccType = 'Amex';
	} else if(ccFrontTwo >= 51 && ccFrontTwo <= 55){
		ccType = 'mastercard';
	} else if(ccFrontFour == 6011){
		ccType = 'Discover';
	} else if(ccFrontOne == 4){
		ccType = 'visa';
	}

	return ccType;
}*/
