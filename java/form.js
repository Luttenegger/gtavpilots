

 /**

*

*  UTF-8 data encode / decode

*  http://www.webtoolkit.info/

*

**/

 

var Utf8 = {

 

	// public method for url encoding

	encode : function (string) {

		string = string.replace(/\r\n/g,"\n");

		var utftext = "";

 

		for (var n = 0; n < string.length; n++) {

 

			var c = string.charCodeAt(n);

 

			if (c < 128) {

				utftext += String.fromCharCode(c);

			}

			else if((c > 127) && (c < 2048)) {

				utftext += String.fromCharCode((c >> 6) | 192);

				utftext += String.fromCharCode((c & 63) | 128);

			}

			else {

				utftext += String.fromCharCode((c >> 12) | 224);

				utftext += String.fromCharCode(((c >> 6) & 63) | 128);

				utftext += String.fromCharCode((c & 63) | 128);

			}

 

		}

 

		return utftext;

	},

 

	// public method for url decoding

	decode : function (utftext) {

		var string = "";

		var i = 0;

		var c = c1 = c2 = 0;

 

		while ( i < utftext.length ) {

 

			c = utftext.charCodeAt(i);

 

			if (c < 128) {

				string += String.fromCharCode(c);

				i++;

			}

			else if((c > 191) && (c < 224)) {

				c2 = utftext.charCodeAt(i+1);

				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));

				i += 2;

			}

			else {

				c2 = utftext.charCodeAt(i+1);

				c3 = utftext.charCodeAt(i+2);

				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));

				i += 3;

			}

 

		}

 

		return string;

	}

 

}



 function dd_change( n, max, prefix ){

    if( n >= max-1 )

        return; // the last dropdown, no need to query

    

    //var prefix = 'dd_' ;

    // reset all other dropdown options

    var next = n+1; 

    for( var i = next; i < max; i ++ ){

        var dd = document.getElementById(prefix +'_' + i );

        if( dd && dd.length >= 1 ) dd.length = 1 ; // keep the first one '- select -'

    };





    // request drop down data from server

    var me = this;

    var http;

    var isIE = navigator.appName == "Microsoft Internet Explorer";

    if(isIE){

        me.http = new ActiveXObject("Microsoft.XMLHTTP");

    }else{

        me.http = new XMLHttpRequest();

    };

    

    

    // build query string

    var lookup = [];

    for( var i = 0; i < next; i ++ ){

        var v = document.getElementById(prefix +'_' +  i ).value ; 

        lookup.push( "lookup[" + i + "]=" + escape( isIE ? Utf8.encode(v) : v ) );

    };

    lookup = lookup.join('&');

    

    var url = 'admin.php?mod=dd&func=lookup&n='+next+ '&field_name=' + prefix + '&' + lookup;

    me.http.open('get', url);

    me.http.onreadystatechange = function(){

    

        if( me.http.readyState == 4 ){

            // rebuild the next dropdown options

            var eNext = document.getElementById(prefix +'_' + next );

            if( !eNext )

                return;

            

            var data = me.http.responseText;    

            var opts = String(data).split("\n");

            for( var j = 0, J = opts.length; j < J; j ++ ){

                eNext.options[ eNext.length ] = new Option( opts[j], opts[j], false, false );                

            }; // for

        }; //if

        

    }; 

    me.http.send(null);



 }

 

    

function PHPFMG( formID ){

    var frmID = formID;

    var exts = {

        'upload_control' : '',

        'harmful_exts'  : '.php, .html, .css, .js, .exe, .com, .bat, .vb, .vbs, scr, .inf, .reg, .lnk, .pif, .ade, .adp, .app, .bas, .chm, .cmd, .cpl, .crt, .csh, .fxp, .hlp, .hta, .ins, .isp, .jse, .ksh, .Lnk, .mda, .mdb, .mde, .mdt, .mdw, .mdz, .msc, .msi, .msp, .mst, .ops, .pcd, .prf, .prg, .pst, .scf, .scr, .sct, .shb, .shs, .url, .vbe, .wsc, .wsf, .wsh',

        'harmful_errmsg': "File is potential harmful. Upload is not allowed.",

        'allow_exts'  : '.jpg, .gif, .png, .bmp',

        'allow_errmsg': "Upload is not allowed. Please check your file type."

    };

    var redirect = "gtavpilots.com/success";





    var Form = null;

    var err_fields=null;

    

    function $( id ){

        return document.getElementById(id);

    }



    function get_form( id ){

        var frm = 'object' == typeof($(id)) ? $(id) : eval( 'document.' + id ) ;

        return frm ? frm : document.forms[0];

    }

    

    function file_ext( f ){

        var n = f.lastIndexOf(".");

        return -1 == n ? '' : f.substr( n ).toLowerCase();

    }

    

    function addLabelEvents(){

        var labels = document.body.getElementsByTagName('LABEL');

        for( var i = 0, N = labels.length; i < N; i ++ ){

            var e = labels[i];

            if( -1 != String(e.className).indexOf('form_choice_text') ){

                var oid = e.getAttribute('oid'); 

                if( !oid ) continue;



                e.onmouseout = function(){ this.className = 'form_choice_text'; };

                e.onmouseover = function(){ this.className = 'form_choice_text form_choice_over'; };

                e.onclick = function(){

                    try{

                        var oid = this.getAttribute('oid'); 

                        var O = document.getElementById(oid);

                        O.checked = !O.checked;

                    }

                    catch(E){};

                };

            }; // if

        }; // for

    } 





    function addFieldBlockEvents(){

        var divs = document.body.getElementsByTagName('DIV');

        for( var i = 0, N = divs.length; i < N; i ++ ){

            var e = divs[i];

            if( -1 != String(e.className).indexOf('field_block') ){

                e.onmouseout = function(){  if( String(this.className).indexOf('form_error_highlight') == -1 ) this.className = 'field_block'; };

                e.onmouseover = function(){ if( String(this.className).indexOf('form_error_highlight') == -1 ) this.className = 'field_block field_block_over'; };

            }; // if

        }; // for

    } 



    function removeHighlight( elements ){

        var divs = typeof(elements) == 'object' ? elements : document.body.getElementsByTagName('DIV');

        for( var i = 0, N = divs.length; i < N; i ++ ){

            var e = divs[i];

            var cn = String(e.className);

            if( -1 != cn.indexOf('form_error_highlight') ){

                e.className = cn.replace('form_error_highlight','');

            } else if ( -1 != cn.indexOf('instruction_error') ){

                e.className = cn.replace('instruction_error','');

            };



        }; // for

    } 

    

    function showProcessing( hide ){

        try{

            var E = $('phpfmg_processing');

            if( !E ) return ;

            if( -1 != navigator.userAgent.toLowerCase().indexOf('msie') ){

                E.style.backgroundColor='#2960AF';

                $('phpfmg_processing_gif').style.display = 'none';

                setInterval( 'fmgHandler.dots()', 300 );

            }else{

                $('phpfmg_processing_gif').style.display = hide ? 'none' : '';

            };

            E.style.display = hide ? 'none' : '';

        }catch(e){};

        

    }



    function setVisible( id, show ){

        var E = $(id);

        if( !E ) return ;

        E.style.display = show ? '' : 'none';

    }





    

    this.highlight_fields = function( fields ){

        var A = fields.split(',');

        for( var i = 0, N = A.length; i < N; i ++ ){

            var E = $( A[i] + '_div' );

            if( E ){

                E.className += ' form_error_highlight'; 

            };

            var T = $( A[i] + '_tip' );

            if( T ){

                T.className += ' instruction_error'; 

            };

        };

        

        if( A.length > 0 ) {

            $('err_required').style.display= has_entry( fields ) ? 'none' : '';

        };

    }

    

    function has_entry( fields ){

        var div = $('one_entry_msg'); 

        if( !div )

            return false;



        div.style.display = fields.indexOf('phpfmg_found_entry') != -1 ? '' : 'none';

        if( typeof(found_entry) != 'undefined' ){

            div.innerHTML = div.innerHTML.replace(/%Entry%/gi,found_entry);

            return true;

        };

        

        return false ;

    }

    

    

    this.choice_clicked = function( id ){

        $(id).checked = !$(id).checked ;

    }

    

    

    this.init = function(){

        //addLabelEvents();

        addFieldBlockEvents();

    }



    this.harmful = function(e){

        if( 'deny' != exts['upload_control'] || e.value == '' ){

            return true; 

        };

        

        var div = $(e.id+'_div');

        removeHighlight( [div] );

        var ext = file_ext(e.value);

        if( -1 != exts['harmful_exts'].toLowerCase().indexOf(ext) ){

            this.highlight_fields(e.id);

            alert( exts['harmful_errmsg'] );

            return false ;

        };

        return true;

    }

    

     

        

    this.is_allow = function(e){

        if( 'allow' != exts['upload_control'] || e.value == '' ){

            return true; 

        };

        

        var div = $(e.id+'_div');

        removeHighlight( [div] );

        var ext = file_ext(e.value);

        if( -1 == exts['allow_exts'].toLowerCase().indexOf(ext) ){

            this.highlight_fields(e.id);

            alert( exts['allow_errmsg'] );

            return false ;

        };

        return true;

    } 



    this.check_upload = function(e){

        if( '' == exts['upload_control'] )

            return true;

        else

            return ( 'deny' == exts['upload_control'] ) 

                   ? this.harmful(e) 

                   : this.is_allow(e);

    }



    this.dots = function(){

        $('phpfmg_processing_dots').innerHTML += '.';

		if( $('phpfmg_processing_dots').innerHTML.length >= 38 ) {

			$('phpfmg_processing_dots').innerHTML = '.';

		};

    }

    

    this.check_fields = function(){

        if( !Form )

            return true ;



        var pass = true ;

        for( var i=0, n=Form.elements.length; i < n ; i ++ ){

          var field = Form.elements[i];

          var type = field.length != undefined && field.type == undefined ? 'radio' : field.type ;

          switch( type.toLowerCase() ){

            case 'file':

                pass = this.check_upload(field);

                break;

          };

          if( !pass ) return false ;

        };

        

        return true;

    }



    function removeAllHighlightedFields(){

        removeHighlight( document.body.getElementsByTagName('LI') );    

        removeHighlight();

        var E = $('err_required');

        if( E ) E.style.display = 'none' ;

    }

    

    this.onSubmit = function( form ){

        Form = form ? form : get_form(frmID) ;

        //Form.action = location.href.replace('#error','');

        if( !this.check_fields() )

            return false ;



        removeAllHighlightedFields();

        showProcessing();

        return true;

    }



    this.onResponse = function( data ){

        removeAllHighlightedFields();

        showProcessing( true ); // true : hide processing indicator



        var ok = data && typeof data['ok'] === 'boolean' ? data['ok'] : false ;

        if( ok === true ) {

            fmgHandler.submitOK();

            return;

        };



        var fields = data && typeof data['error_fields'] === 'object' ? data['error_fields'] : false ;

        this.highlight_fields( fields + "" );

        

        

        var oneEntry = data && typeof data['OneEntry'] !== 'undefined'  ? data['OneEntry'] : '' ;

        if( oneEntry != '' ){

            setVisible( 'err_required', false );

            window.found_entry = oneEntry; // inject it as global variable for below function call

            has_entry( fields );

        };





/*        // reset errors

        $('#err_required').hide();

        $( '.form_error_highlight' ).removeClass('form_error_highlight');



        $.each( fields, function( idx, field ){

            $('#'+field+'_div').addClass('form_error_highlight');

        } );

        

        if( fields.length > 0 ){

            $('#err_required').show();    

        };

*/

    }



     function showThankYouMessage(){

        setVisible( 'frmFormMailContainer',false );

        setVisible( 'thank_you_msg',true );

    }



    this.submitOK = function(){

        if( redirect == '' ){

            showThankYouMessage();

            return;

        };



        try{

            if( parent ) parent.location.href = redirect;

        }catch(e){

            location.href = redirect;

        };

    }





}



function toggleOtherInputBox( name, type, id ){

    var field = document.getElementById(id);

    if( !field ) return ;

    var box = document.getElementById(name+'_other');

    var other_check = document.getElementById(name+'_other_check'); 

    if( !box || !other_check ) return ;

    

    switch( type.toLowerCase() ){

        case 'checkbox':

            box.style.display = field.checked ? '' : 'none';

            other_check.value = field.checked ? 1 : 0 ; 

            break;

        case 'radio':

            for( var i=0,n=document.forms.length; i < n; i ++ ){

                try{

                    var r = eval( 'document.forms['+i+'].'+name );

                    if( r ){

                        box.style.display = r[r.length-1].checked ? '' : 'none';

                        other_check.value = r[r.length-1].checked ? 1 : 0 ; 

                    };

                }catch(err){};

            };

            break;

        case 'select':

            box.style.display = field.options[field.options.length-1].selected ? '' : 'none';

            other_check.value = field.options[field.options.length-1].selected ? 1 : 0 ;

            break;

    };

    

}







var fmgHandler = new PHPFMG();

fmgHandler.init();

/*

// Sep 2013, add ajax submit support with jQuery

$('#frmFormMail').submit( function(event){

    event.preventDefault();

    var 

    $form = $(this),

    url = $form.prop('action');

    $.post( url, $form.serialize(), function(data){

        var ok = data && typeof data['ok'] === 'boolean' ? data['ok'] : false ;

        if( ok === true ) {

            fmgHandler.submitOK();

            return;

        };



        var fields = data && typeof data['error_fields'] === 'object' ? data['error_fields'] : false ;

        // reset errors

        $('#err_required').hide();

        $( '.form_error_highlight' ).removeClass('form_error_highlight');



        $.each( fields, function( idx, field ){

            $('#'+field+'_div').addClass('form_error_highlight');

        } );

        

        if( fields.length > 0 ){

            $('#err_required').show();    

        };

        





    }, 'json' );



});

*/


