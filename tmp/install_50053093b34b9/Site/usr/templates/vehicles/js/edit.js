/**
 * @version: $Id: edit.js 1202 2011-04-15 19:23:38Z Radek Suski $
 * @package: SobiPro Template
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2011-04-15 21:23:38 +0200 (Fri, 15 Apr 2011) $
 * $Revision: 1202 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/usr/templates/vehicles/js/edit.js $
 */
// it has to be MT :( because of the tiny
window.addEvent( 'load', function() {
	var els = SP_class( 'SPPaymentBox' );
	for( var i = 0; i < els.length; i++ ) {
		SP_ActivatePayment( SP_id( els[ i ].id ) );
	}	
	$( 'spEntryForm' ).addEvent( 'submit', function( ev ) {
		var els = SP_class( 'mce_editable' );
		for( var i = 0; i < els.length; i++ ) {
			if( tinyMCE.get( els[ i ].id ).getContent().length ) {
				els[ i ].value = tinyMCE.get( els[ i ].id ).getContent();
				els[ i ].disabled = false;
			}
		}	
	} );
} );

function SP_ActivatePayment( e )
{
	var cid = e.id.replace( 'Payment', 'Container' );
	if( e.checked ) {
		jQuery( "#" + cid + " input" ).each( function( i, el ){ this.disabled = false; } ); 
		jQuery( "#" + cid + " select" ).each( function( i, el ){ this.disabled = false; } );
		jQuery( "#" + cid + " textarea" ).each( function( i, el ){
			if( el.className == 'mce_editable' ) {
				tinyMCE.execCommand( 'mceToggleEditor', true, el.id );
			}
			else {
				this.disabled = false;
			}
		} );
	}
	else {
		jQuery( "#" + cid + " input" ).each( function( i, el ){ this.disabled = true; } ); 
		jQuery( "#" + cid + " select" ).each( function( i, el ){ this.disabled = true; } );
		jQuery( "#" + cid + " textarea" ).each( function( i, el ){ 
			if( el.className == 'mce_editable' ) {
				tinyMCE.execCommand( 'mceToggleEditor', false, el.id );
			}
			this.disabled = true;
		} );
	}
	e.disabled = false;
}