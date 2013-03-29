jQuery( function ( $ ) {
	"use strict";

	var
		$new                = $( '.new' ),
		$newTrigger         = $( '.new-trigger a' ),
		$concertpressSelect = $( '.concertpress-select' ),
		$multiDateTrigger   = $( '.concertpress-multi-date-trigger' ),
		$endDate            = $( '.end-date' ),
		$startDate          = $( '.start-date' ),
		$hourMin            = $( '.concertpress-time');


	if ( 'event' === pagenow )
		$new.hide();

	if ( !$multiDateTrigger.is( ':checked' ) )
		$endDate.hide();


	$newTrigger.on( 'click', function ( e ) {

		e.preventDefault();
		$( this ).parent().hide().next().slideDown( 350 );
		$( this ).parent().prev().val( 0 );

	});


	$concertpressSelect.on( 'change', function( e ) {
		var $trigger = $( this ).next();

		if ( ! $trigger.is( ':visible' ) )
			$trigger.show().next().slideUp( 200 );

	});


	/** Datepicker */
	$.each( $( '.concertpress-datepicker' ), function () {
		$( this ).datepicker({
			monthNames: $.parseJSON( cp.monthNames ),
			monthNamesShort: $.parseJSON( cp.monthNamesShort ),
			dayNames: $.parseJSON( cp.dayNames ),
			dayNamesShort: $.parseJSON( cp.dayNamesShort ),
			dateFormat: 'yy-mm-dd',
			firstDay  : 1
		});
	});


	/** Show the end date */
	$multiDateTrigger.on( 'change', function () {

		if ( !$endDate.is( ':visible' ) ) {
			$endDate.slideDown( 200 );
			$hourMin.attr( 'disabled', true );
		} else {
			$endDate.slideUp( 200 );
			$hourMin.attr( 'disabled', false );
		}

	});


});
