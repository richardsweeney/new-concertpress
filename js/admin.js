jQuery( function ( $ ) {
	"use strict";

    var
        $new                = $( '.new' ),
        $newTrigger         = $( '.new-trigger a' ),
        $concertpressSelect = $( '.concertpress-select' ),
        $multiDateTrigger   = $( '.concertpress-multi-date-trigger' ),
        $endDate            = $( '.end-date' ),
        $hourMin            = $( '.concertpress-time')

    $new.hide()

    if ( !$multiDateTrigger.is( ':checked' ) )
        $endDate.hide()

    $newTrigger.on( 'click', function ( e ) {

        e.preventDefault()
        $( this ).parent().hide().next().slideDown( 350 )
        $( this ).parent().prev().val( 0 )

    })

    $concertpressSelect.on( 'change', function( e ) {
        var $trigger = $( this ).next()

        if ( !$trigger.is( ':visible' ) )
            $trigger.show().next().slideUp( 200 )

    })


    $.each( $( '.concertpress-datepicker' ), function () {
        $( this ).datepicker({
            dateFormat: 'yy-mm-dd',
            firstDay  : 1
        })
    })

    $multiDateTrigger.on( 'change', function () {
        if ( !$endDate.is( ':visible' ) ) {
            $endDate.slideDown( 200 )
            $hourMin.attr( 'disabled', true )
        } else {
            $endDate.slideUp( 200 )
            $hourMin.attr( 'disabled', false )
        }
    })

});
