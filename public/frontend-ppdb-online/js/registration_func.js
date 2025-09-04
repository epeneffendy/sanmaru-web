	/*  Wizard */
	jQuery(function ($) {
        "use strict";
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
		// $('form#wrapped').attr('action', 'registration.php');
		$("#wizard_container").wizard({
			stepsWrapper: "#wrapped",
			submit: ".submit",
			beforeSelect: function (event, state) {
				if ($('input#website').val().length != 0) {
					return false;
				}
				if (!state.isMovingForward)
                    return true;

				var inputs = $(this).wizard('state').step.find(':input');
				return !inputs.length || !!inputs.valid();
			}
		}).validate({
			errorPlacement: function (error, element) {
				if (element.is(':radio') || element.is(':checkbox')) {
					error.insertBefore(element.next());
				} else {
					error.insertAfter(element);
				}
			}
		});
		//  progress bar
		$("#progressbar").progressbar();
		$("#wizard_container").wizard({
			afterSelect: function (event, state) {
				$("#progressbar").progressbar("value", state.percentComplete);
				$("#location").text("(" + state.stepsComplete + "/" + state.stepsPossible + ")");
			}
		});
		// Validate select
		$('#wrapped').validate({
			ignore: [],
			rules: {
				select: {
					required: true
				}
			},
			errorPlacement: function (error, element) {
				if (element.is('select:hidden')) {
					error.insertAfter(element.next('.nice-select'));
				} else {
					error.insertAfter(element);
				}
			}
		});
	});

	// Summary
	function getVals(formControl, controlType) {
		switch (controlType) {

			case 'name':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#name").text(value);
				break;

			case 'address':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#address").text(value);
				break;

			case 'email':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#email").text(value);
				break;

			 case 'phone':
				// Get the value for a select
				var value = $(formControl).val();
				$("#phone").text(value);
				break;

			case 'mobile-phone':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#mobile-phone").text(value);
				break;

			case 'father-name':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#father-name").text(value);
				break;

			case 'mother-name':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#mother-name").text(value);
				break;

			case 'payment-type':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#payment-type").text(value);
				break;

			case 'last_scholl':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#last_school").text(value);
                break;

			case 'date_of_birth':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#date_of_birth").text(value);
                break;

			case 'password':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#password").text(value);
				break;

			case 'retype-password':
				// Get the value for a input text
				var value = $(formControl).val();
				$("#retype-password").text(value);
				break;
		}
	}
