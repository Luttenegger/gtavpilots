<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "gtavpilots.com@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "6751c7" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onClick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'127F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0NDkMRYHVhbGRoCHZDViTqINDqgiTE6MDQ6NDrCxMBOWpm1aumqpStDs5DcB1Q3hWEKI7reAIYAdDEIRBVjbWBtQBUTDRENdUUTG6jwoyLE4j4AtizGoui90C8AAAAASUVORK5CYII=',
			'71DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZAlhDGUNDkEVbGQNYGx0dUFS2sgawNgSiik1hQBaDuClqVdTSVZGhWUjuY3RgwNDL2oApJoJFLAAkhuaWgAbWUKCbUd0yQOFHRYjFfQDrScf3qMFy7wAAAABJRU5ErkJggg==',
			'EAFE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMYAlhDA0MDkMQCGhhDWBsYHRhQxFhbMcVEGl0RYmAnhUZNW5kaujI0C8l9aOqgYqKhmGLY1GGKhYaAxVDcPFDhR0WIxX0AvADLREi4rbMAAAAASUVORK5CYII=',
			'6627' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUNDkMREprC2Mjo6NIggiQW0iDSyNgSgioF5AUCIcF9k1LSwVSuzVmYhuS9kimgrAwgi620VaXSYwjAFQyyAIYAB3S0OjA7obmYNDUQRG6jwoyLE4j4AjCDLckIh92UAAAAASUVORK5CYII=',
			'DC9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYQxlCGVqRxQKmsDY6OjpMdUAWaxVpcG0ICAhAE2NtCHQQQXJf1NJpq1ZmRmZNQ3IfSB1DCFwdQqwhMDQETcyxAU0d2C2OKGIQNzOiiA1U+FERYnEfAFOpzbaViSzSAAAAAElFTkSuQmCC',
			'8BFD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0MdkMREpoi0sjYwOgQgiQW0ijS6AsVEsKgTQXLf0qipYUtDV2ZNQ3Ifmjqc5uGzA9ktYDc3MKK4eaDCj4oQi/sA3K3LH/7ujq4AAAAASUVORK5CYII=',
			'ACF9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDA6Y6IImxBrA2ujYwBAQgiYlMEWlwBaoWQRILaBVpYEWIgZ0UtXTaqqWhq6LCkNwHUccwFVlvaChYrAHdPKC9aHZguiWgFehmoHnIbh6o8KMixOI+ADzbzIKZNkZEAAAAAElFTkSuQmCC',
			'3A6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGVqRxQKmMIYwOjpMdUBW2craytrgEBCALDZFpNG1gdFBBMl9K6OmrUydujJrGrL7QOocHWHqoOaJhro2BIaGoIiBzAtEURcA1OuIplc0QKTRIZQR1bwBCj8qQizuAwCwjMvwY9oWIwAAAABJRU5ErkJggg==',
			'FC69' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMZQxlCGaY6IIkFNLA2Ojo6BASgiIk0uDY4OoigibE2MMLEwE4KjZq2aunUVVFhSO4Dq3N0mIqpF0Si2xGAZgc2t2C6eaDCj4oQi/sAKF/N4b8DOSoAAAAASUVORK5CYII=',
			'7E0F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQxmmMIaGIIu2ijQwhDI6MKCJMTo6oopNEWlgbQiEiUHcFDU1bOmqyNAsJPcxOqCoA0PWBkwxkQZMOwIaMN0S0AB2M6pbBij8qAixuA8A9c/IylgP2dEAAAAASUVORK5CYII=',
			'75FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0MDkEVbRRpYGxgdGAiJTREJQRKDuClq6tKloStDs5DcB1TR6Iqml7UBU0ykQQRDLKCBtRXd3oAGRpC9qG4eoPCjIsTiPgBqz8lCoJep3AAAAABJRU5ErkJggg==',
			'ACE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDHVqRxVgDWBtdGximIouJTBFpAIqFIosFtIo0sDYwwPSCnRS1dNqqpaGrliK7D00dGIaGYoqB1LliiIHdgiYGdnNowCAIPypCLO4DAGWbzK5J81z+AAAAAElFTkSuQmCC',
			'7194' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nM3QwQ2AIAxA0XLoBg5UNigJvXQDt6gHN2AJmVK4FfGoUXp7KeEHqNMx+NO80icCDALGXvfAIdI2GjIa74MV6FbY92nVY1VV1xeovZET+btozSxJdrY0C63E73G3SBdDmZo/+r8H56bvBM2lyzc5dNVCAAAAAElFTkSuQmCC',
			'B453' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYWllDHUIdkMQCpjBMZW1gdAhAFmtlCGUF0iIo6hhdWacCaST3hUYtXbo0M2tpFpL7AqaItIJUoZonCrQzANW8VqBb0MWmMLQyOjqiuAXkZoZQBhQ3D1T4URFicR8AN3LN3U1oXXsAAAAASUVORK5CYII=',
			'0A58' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHaY6IImxBjCGsDYwBAQgiYlMYW1lBaoWQRILaBVpdJ0KVwd2UtTSaStTM7OmZiG5D6TOoSEAxbyAVtFQh4ZAFPNEpgDNQxNjDRBpdHR0QNELdEWjQygDipsHKvyoCLG4DwAeZ8xw18avwwAAAABJRU5ErkJggg==',
			'42CE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpI37pjCGMIQ6hgYgi4WwtjI6BDogq2MMEWl0bRBEEWOdwgAUY4SJgZ00bdqqpUtXrQzNQnJfwBSGKawIdWAYGsoQgC4GdIsDK5odEJ2BaGKioQ7obh6o8KMexOI+AMVkyX6B2O8pAAAAAElFTkSuQmCC',
			'CFE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WENEQ11DHVqRxURaRRpYGximIosFNILFQlHEGsBiML1gJ0Wtmhq2NHTVUmT3oanDLdaIKQZ1C4oYawhQLNQhNGAQhB8VIRb3AQDYwMvxXH/ktgAAAABJRU5ErkJggg==',
			'47F2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37poiGuoYGTHVAFgthaHRtYAgIQBJjBIsxOoggibFOYWhlBdIiSO6bNm3VtKWhq1ZFIbkvYApDAFBdI7IdoaGMDkCxVlS3sDYAxaagiomAxAIwxRhDQwZD+FEPYnEfAG53y3LLaLMTAAAAAElFTkSuQmCC',
			'16A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQximMLQii7E6sLYyhDJMdUASE3UQaWR0dAgIQNEr0sDaEAgkEe5bmTUtbOmqyKxpSO5jdBBtRVIH09voGopFrCEAzQ5WoN4AVLeEMIYAxVDcPFDhR0WIxX0A0JTJ1y3LCXUAAAAASUVORK5CYII=',
			'9674' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDAxoCkMREprC2MjQENCKLBbSKNIJINLEGhkaHKQFI7ps2dVrYqqWroqKQ3MfqKtrKMIXRAVkvA9A8hwDG0BAkMQGgmKMDA4ZbWBtQxcBuRhMbqPCjIsTiPgAgN82BTIiAEgAAAABJRU5ErkJggg==',
			'B4C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QgMYWhlCHUMDkMQCpjBMZXQIdEBWFwBUxdogiCo2hdGVtYHR1QHJfaFRS5cuXbUyKgrJfQFTRFpZgbQIinmioa4YYgytIDtQxKYwtDI6BAQguw/iZoepDoMg/KgIsbgPAEarzHVMgE/CAAAAAElFTkSuQmCC',
			'59AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYQximMIY6IIkFNLC2MoQyOgSgiIk0Ojo6OoggiQUGiDS6NgTC1IGdFDZt6dLUVZGhWcjua2UMRFIHFWNodA0NRDEvoJUFbB6ymMgU1lZWNL2sAYwhQDEUNw9U+FERYnEfAJ7EzJtb6xqIAAAAAElFTkSuQmCC',
			'C365' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WENYQxhCGUMDkMREWkVaGR0dHZDVBTQyNLo2oIk1MLSyNjC6OiC5L2rVqrClU1dGRSG5D6zO0aFBBFUv0LwAVDGwHYEOIhhucQhAdh/EzQxTHQZB+FERYnEfAHZuy9XJFthAAAAAAElFTkSuQmCC',
			'3C71' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDA1qRxQKmsDY6NARMRVHZKtIAFAtFEZsi0sDQ6ADTC3bSyqhpq1YtBUJk94HUTWFoRTePIQBTzNGBAcMtrg2oYmA3NzCEBgyC8KMixOI+AP6RzKKeAum+AAAAAElFTkSuQmCC',
			'37B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QPQ6AIAxGy9AbwH26sNfELpymDNwAvYGDnFLcSnTUhH7bS39eCu1RCjPlF7/AQaLARoZxhRwzMdvO0pku5C2rUDCTeuN3prYf0lqyfhW492Ua9jlC5TLYFFS8rw8uXvssj86diZN1gv99mBe/C1S0zOvP/ahVAAAAAElFTkSuQmCC',
			'667C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA6YGIImJTGFtZWgICBBBEgtoEWlkaAh0YEEWawCqaHR0QHZfZNS0sFVLV2Yhuy9kimgrwxRGB2R7A1pFGh0CMMUcHRhR7AC5hbWBAcUtYDc3MKC4eaDCj4oQi/sAbU3LahdkLQwAAAAASUVORK5CYII=',
			'6161' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGVqRxUSmMAYwOjpMRRYLaGENYG1wCEURa2AAisH1gp0UGbUqaunUVUuR3RcyBajO0QHFjoBWkN4AgmIiQL2MaHqBLgkFujk0YBCEHxUhFvcBAHHtyhp82itSAAAAAElFTkSuQmCC',
			'DE98' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGaY6IIkFTBFpYHR0CAhAFmsVaWBtCHQQwRALgKkDOylq6dSwlZlRU7OQ3AdSxxASgGEeAxbzGNHFsLgFm5sHKvyoCLG4DwDYas2Bxs3CaAAAAABJRU5ErkJggg==',
			'D1A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYAhimMDQEIIkFTGEMYAhlaEQRa2UNYHR0aEUVYwhgBaoOQHJf1FIwiopCch9EXaADht7QwNAQTPPQ3IIpFgrUiS42UOFHRYjFfQAx/M4JdITG7AAAAABJRU5ErkJggg==',
			'8050' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHVqRxUSmMIawNjBMdUASC2hlbQWKBQSgqBNpdJ3K6CCC5L6lUdNWpmZmZk1Dch9InUNDIEwd1DxsYiA7AtDsYAxhdHRAcQvIzQyhDChuHqjwoyLE4j4Aw2rL41NSTgkAAAAASUVORK5CYII=',
			'051D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMIY6IImxBog0MIQwOgQgiYlMEWlgBIqJIIkFtIqEAPXCxMBOilo6demqaSuzpiG5L6CVodFhCrpeTDGgHRhirAGsrSA7kN3C6AB0SagjipsHKvyoCLG4DwCfc8pGu/dIWQAAAABJRU5ErkJggg==',
			'A8AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YQximMIYGIImxBrC2MoQyOiCrE5ki0ujo6IgiFtDK2sraEAgTAzspaunKsKWrIkOzkNyHpg4MQ0NFGl1DA9HMA4o1oIth6g1oZQwBiqG4eaDCj4oQi/sAhW/LYyhFTGwAAAAASUVORK5CYII=',
			'88CA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCHVqRxUSmsLYyOgRMdUASC2gVaXRtEAgIQFPH2sDoIILkvqVRK8OWrlqZNQ3JfWjqkMxjDA3BEBNEUQdxSyCKGMTNjihiAxV+VIRY3AcAucXLracbhpQAAAAASUVORK5CYII=',
			'FE5A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDHVqRxQIaRBpYGximOmCKBQSgi01ldBBBcl9o1NSwpZmZWdOQ3AdSx9AQCFOHLBYagmEHpjpGR0c0MdFQhlBGFLGBCj8qQizuAwBo2Mw9MobLWwAAAABJRU5ErkJggg==',
			'4E64' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37poiGMoQyNAQgi4WINDA6OjQiizECxVgbHFqRxVingMQYpgQguW/atKlhS6euiopCcl8ASJ2jowOy3tBQkN7A0BAUt4DEAlDdMgXsFjQxLG4eqPCjHsTiPgAxIM0n1RWkdwAAAABJRU5ErkJggg==',
			'9677' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0NDkMREprC2MjQENIggiQW0ijRiEWtgaHQAiiLcN23qtLBVS1etzEJyH6uraCvDFIZWFJuB5jkEAEWRxASAYo4ODAEMaG5hbWB0wHAzmthAhR8VIRb3AQDVHMtoivvlawAAAABJRU5ErkJggg==',
			'F56E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGUMDkMQCGkQaGB0dHRjQxFgbMMRCWBsYYWJgJ4VGTV26dOrK0Cwk9wHNbnTFMA8o1hCIbh4WMdZWTLcwhqC7eaDCj4oQi/sAGCjLawPJdOoAAAAASUVORK5CYII=',
			'48A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjCGMExhaEURC2FtZQhlmIosxhgi0ujo6BCKLMY6hbWVtSEAphfspGnTVoYtXRW1FNl9AajqwDA0VKTRNRRVjGEKUKwBXQxTL8jNQLHQgMEQftSDWNwHAGtazMqC1OmeAAAAAElFTkSuQmCC',
			'98A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQximMIQ6IImJTGFtZQhldAhAEgtoFWl0dHRoEEERY21lbQhoCEBy37SpK8OWropamoXkPlZXFHUQCDTPNTQAxTwBkFgDqhjILawNgShuAbkZaB6Kmwcq/KgIsbgPAH+/zWzWcvq8AAAAAElFTkSuQmCC',
			'3513' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RANEQxmmMIQ6IIkFTBFpYAhhdAhAVtkq0sAYwtAggiw2RSQEqLchAMl9K6OmLl01bdXSLGT3TWFodECog5oHERNBtQNDLGAKayvDFFS3iAYwhjCGOqC4eaDCj4oQi/sAOgTMWbDP/foAAAAASUVORK5CYII=',
			'79DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGVpRRFtZW1kbHaY6oIiJNLo2BAQEIItNAYkFOogguy9q6dLUVZFZ05Dcx+jAGIikDgxZGxhAekNDkMREGlga0dUFNIDc4ogmBnIzI4rYQIUfFSEW9wEApAfMeW70MQ0AAAAASUVORK5CYII=',
			'33D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RANYQ1hDGUMDkMQCpoi0sjY6OqCobGVodG0IRBWbwtDK2hDo6oDkvpVRq8KWroqMikJ2H1hdQIMIhnnYxAIdRDDc4hCA7D6ImxmmOgyC8KMixOI+AOvTzC0ukaFTAAAAAElFTkSuQmCC',
			'53DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDGUNDkMQCGkRaWRsdHRhQxBgaXRsCUcQCAxhaWRFiYCeFTVsVtnRVZGgWsvtaUdTBxDDMC8AiJjIF0y2sAWA3o5o3QOFHRYjFfQCZQ8qw1BfbggAAAABJRU5ErkJggg==',
			'886B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMdkMREprC2Mjo6OgQgiQW0ijS6Njg6iKCpY21ghKkDO2lp1MqwpVNXhmYhuQ+sDqt5gSjmYRPD5hZsbh6o8KMixOI+AGApy5ycUOxhAAAAAElFTkSuQmCC',
			'F2C7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMZQxhCHUNDkMQCGlhbGR0CGkRQxEQaXRsE0MQYgGJgGu6+0KhVS5euWrUyC8l9QPkprA0MrQyoegOAYlNQxRgdWBsEAlDFWIGigQ6oYqKhDqGOKGIDFX5UhFjcBwBYjcza+YU6uwAAAABJRU5ErkJggg==',
			'0C38' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YQxlDGaY6IImxBrA2ujY6BAQgiYlMEWlwaAh0EEESC2gF8hDqwE6KWjpt1aqpq6ZmIbkPTR1CDM08bHZgcws2Nw9U+FERYnEfAKQ4zVy27bW5AAAAAElFTkSuQmCC',
			'5BF3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA0IdkMQCGkRaWRsYHQJQxRpdgbQIklhgAEgdSA7hvrBpU8OWhq5amoXsvlYUdTAxDPMCsIiJTMF0C2sA0M0NDChuHqjwoyLE4j4AIF/MyuRVjv4AAAAASUVORK5CYII=',
			'BB1E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QgNEQximMIYGIIkFTBFpZQhhdEBWF9Aq0uiILgZSNwUuBnZSaNTUsFXTVoZmIbkPTR3cPAdixLDoBbmZMdQRxc0DFX5UhFjcBwAT6stlMEwSVQAAAABJRU5ErkJggg==',
			'7D47' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNFQxgaHUNDkEVbRVoZWh0aRFDFGh2moolNAYoFOjQEILsvatrKzMyslVlI7mN0EGl0bXRoRbaXtQEoFhowBVlMBCjm0OgQgCwW0AB0S6OjA6oY2M0oYgMVflSEWNwHAPG+zXhYP4/LAAAAAElFTkSuQmCC',
			'76A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QkMZQximMLSiiLaytjKEMkx1QBETaWR0dAgIQBabItLA2hDoIILsvqhpYUtXRWZNQ3Ifo4NoK5I6MGRtEGl0DUUVEwGJNQSg2BHQwArUG4DiloAGxhCgGKqbByj8qAixuA8AE5nMgT/2zHUAAAAASUVORK5CYII=',
			'A5A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMLQii7EGiDQwhDJMRRYTmSLSwOjoEIosFtAqEsIKJJHdF7V06tKlQBLZfUAVja4IdWAYGgoUC0UVA5qHoS6glbWVFUOMEWRvaMAgCD8qQizuAwBENM2oZNCJ2gAAAABJRU5ErkJggg==',
			'C2F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDA6Y6IImJtLK2sjYwBAQgiQU0ijS6NjA6iCCLNTAgi4GdFLVq1dKloauiwpDcB1Q3BWjeVDS9Aawgu1DsYHQAiqHYAXRLA7pbWENEQ12B5iG7eaDCj4oQi/sAlIbLqGc2WIMAAAAASUVORK5CYII=',
			'218D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjAGMDo6OgQgiQW0sgawNgQ6iCDrbmUAqxNBdt+0VVGrQldmTUN2XwCKOjBkdGDAMI+1AVMMyMZwS2goayi6mwcq/KgIsbgPAM8cx+qg+lBxAAAAAElFTkSuQmCC',
			'EF11' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUElEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMLQiiwU0iDQwhDBMRRdjDGEIxVCH0At2UmjU1LBV01YtRXYfmjqKxUJDgG4JdQgNGAThR0WIxX0A0I3MzhBsaAAAAAAASUVORK5CYII=',
			'3983' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7RAMYQxhCGUIdkMQCprC2Mjo6OgQgq2wVaXRtCGgQQRabItIIVNYQgOS+lVFLl2aFrlqahey+KYyBSOqg5jFgmtfKgiGGzS3Y3DxQ4UdFiMV9AMyMzLLlKaKUAAAAAElFTkSuQmCC',
			'FEC3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7QkNFQxlCHUIdkMQCGkQaGB0CHQLQxFgbBIAkuhiIRrgvNGpq2NJVq5ZmIbkPTR2KGKZ5mHZgugXTzQMVflSEWNwHAPPyzaSlsBWlAAAAAElFTkSuQmCC',
			'A578' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA6Y6IImxBogAyYCAACQxkSkgsUAHESSxgFaREIZGB5g6sJOilk5dumrpqqlZSO4LaAWqmsKAYl5oKEgnI7p5jY4O6GKsrawNqHoDWhlDgGIobh6o8KMixOI+AEPNzSC5gTBKAAAAAElFTkSuQmCC',
			'E126' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDAGMDo6BASgiLEGsDYEOgigiAH1AsWQ3RcatSpq1crM1Cwk94HVtTKimQcUm8LoIIIuFoApxujAgKI3NIQ1lDU0AMXNAxV+VIRY3AcAgSXKAjktZT0AAAAASUVORK5CYII=',
			'FADA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDGVqRxQIaGENYGx2mOqCIsbayNgQEBKCIiTS6NgQ6iCC5LzRq2srUVZFZ05Dch6YOKiYaChQLDcFtHkKs0RFTLJQRRWygwo+KEIv7AOAhzoOnluQgAAAAAElFTkSuQmCC',
			'BCC7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgMYQxlCHUNDkMQCprA2OjoENIggi7WKNLg2CKCKTRFpYAXRSO4LjZq2aumqVSuzkNwHVdfKgGYeUGwKuhjQjgAGDLcEOmBxM4rYQIUfFSEW9wEAibfNzRnUu1AAAAAASUVORK5CYII=',
			'D192' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGaY6IIkFTGEMYHR0CAhAFmtlDWBtCHQQQRFjAIoFNIgguS9q6aqolZlRq6KQ3AdSxxAS0OiAphdMookxAm1HEZvCAHYLqptZQxlCGUNDBkH4URFicR8AmRLLqOKO8EQAAAAASUVORK5CYII=',
			'772C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGaYGIIu2MjQ6OjoEiKCJuTYEOrAgi00BigLFUNwXtWraqpWZWcjuY3RgCGBoZXRAtpcVJDoFVUwEKMoQwIhiRwBQFKRfBE2MNTQA1c0DFH5UhFjcBwDnuMo/sRgGggAAAABJRU5ErkJggg==',
			'43AD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37prCGMExhDHVAFgsRaWUIZXQIQBJjDGFodHR0dBBBEmOdwtDK2hAIEwM7adq0VWFLV0VmTUNyXwCqOjAMDWVodA1FFWOYAhRrQBcTAesNQBFjDQGKobp5oMKPehCL+wAPxculNJ/+jwAAAABJRU5ErkJggg==',
			'FCC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkMZQxlCHRoCkMQCGlgbHR0CGlHFRBpcGwRa0cVYGximBCC5LzRq2qqlq1ZFRSG5D6KO0QFTL2NoCKYd2NyCJobp5oEKPypCLO4DAIsbz7dTL4EdAAAAAElFTkSuQmCC',
			'9B5A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHVqRxUSmiLSyNjBMdUASC2gVaXRtYAgIQBVrZZ3K6CCC5L5pU6eGLc3MzJqG5D5WVxGg+YEwdRAINM+hITA0BElMAGwHqjqQWxgdHVHEQG5mCGVENW+Awo+KEIv7AIMVy2xo0Z5YAAAAAElFTkSuQmCC',
			'2795' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2Quw2AMAwF7SIbhH2cIr0j4YJswBahyAaQHciUuHQEJUj4uid/ToZ+qwJ/4hM/x5OQoLDJ/A5bCIFsH1fYYklDBhWqKymS9Wu9neuSs/VjZebizSwS6q4xcwrqDZt5BQOx9RPRDoGDfvC/F3nwuwBbXMrB0xVg9gAAAABJRU5ErkJggg==',
			'8082' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYXR0CAhAEgtoZW1lbQh0EEFRJ9Lo6OjQIILkvqVR01Zmha5aFYXkPqi6RgcU80QaXYEkA4YdAVMYsLgF082MoSGDIPyoCLG4DwDz2Mv/35elswAAAABJRU5ErkJggg==',
			'D0BA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYAlhDGVqRxQKmMIawNjpMdUAWa2VtZW0ICAhAERNpdG10dBBBcl/U0mkrU0NXZk1Dch+aOoRYQ2BoCIYdgajqwG5B1QtxMyOK2ECFHxUhFvcBACovzZzR31U6AAAAAElFTkSuQmCC',
			'2DFB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0MdkMREpoi0sjYwOgQgiQW0ijS6AsVEkHVDxQKQ3Tdt2srU0JWhWcjuC0BRB4ZAkzDMY23AFBNpwHRLaCjQzQ2MKG4eqPCjIsTiPgDKkcsFZdukjwAAAABJRU5ErkJggg==',
			'DD62' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGaY6IIkFTBFpZXR0CAhAFmsVaXRtcHQQwRBjaBBBcl/U0mkrU6cCaST3gdU5OjQ6YOgNaGXAFJvCgMUtmG5mDA0ZBOFHRYjFfQCu8s7zvDgu4wAAAABJRU5ErkJggg==',
			'2F4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQx0aHUMdkMREpog0MLQ6OgQgiQW0AsWmOjqIIOsGiQXCxSBumjY1bGVmZtY0ZPcFiDSwNqLqZQTyWEMDUcRYG4A8NHUiUDFkt4SGgsVQ3DxQ4UdFiMV9AFTiy3wA/1SIAAAAAElFTkSuQmCC',
			'0EB6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGaY6IImxBog0sDY6BAQgiYlMAYo1BDoIIIkFtILUOToguy9q6dSwpaErU7OQ3AdVh2IeWAxonggWO0QIuAWbmwcq/KgIsbgPAFiIy5RCDVJNAAAAAElFTkSuQmCC',
			'F1C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMZAhhCHVqRxQIaGAMYHQKmOqCIsQawNggEBKCIMQDFGB1EkNwXGrUqaumqlVnTkNyHpo6AGKYdWNwSiu7mgQo/KkIs7gMAm4/K+A5RrHsAAAAASUVORK5CYII=',
			'B45B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYWllDHUMdkMQCpjBMZW1gdAhAFmtlCAWJiaCoY3RlnQpXB3ZSaNTSpUszM0OzkNwXMEWklaEhEM08UaCdgajmtQLdgi42haGV0dERRS/IzQyhjChuHqjwoyLE4j4ATsTMU4mH1FAAAAAASUVORK5CYII=',
			'8BFB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0MdkMREpoi0sjYwOgQgiQW0ijS6AsVEcKsDO2lp1NSwpaErQ7OQ3EeseUTYgXBzAyOKmwcq/KgIsbgPACKxy1YZgo+7AAAAAElFTkSuQmCC',
			'1331' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhDGVqRxVgdRFpZGx2mIouJOjA0OjQEhKLqBeprdIDpBTtpZdaqsFVTVy1Fdh+aOpgYyDwixMBuQRETDQG7OTRgEIQfFSEW9wEAewzKJwN4GQAAAAAASUVORK5CYII=',
			'B9FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA0MDkMQCprC2sjYwOiCrC2gVaXRFF5uCIgZ2UmjU0qWpoStDs5DcFzCFMRBDbysDpnmtLFjswHQL2M0NjChuHqjwoyLE4j4A+XPLJPPSv2YAAAAASUVORK5CYII=',
			'B9A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QgMYQximMIQ6IIkFTGFtZQhldAhAFmsVaXR0dGgQQVEn0ujaENAQgOS+0KilS1NXRS3NQnJfwBTGQCR1UPMYGl1DA1DNa2UBmyeC5hbWhkAUt4DczNoQgOLmgQo/KkIs7gMACSDPe0dDAiMAAAAASUVORK5CYII=',
			'55F7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA0NDkMQCGkQaWIG0CAGxwACREFawHMJ9YdOmLl0aumplFrL7WhkaXUEkss0QsSnIYgGtIiCxAGQxkSmsrawNjA7IYqwBjCHoYgMVflSEWNwHAMt8y30sKvVvAAAAAElFTkSuQmCC',
			'CFE7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WENEQ11DHUNDkMREWkUaWEE0klhAIxaxBohYAJL7olZNDVsaumplFpL7oOpaGTD1TmHAtCMAWQziFkYHVDcDxUIdUcQGKvyoCLG4DwDEb8uVg0fW8wAAAABJRU5ErkJggg==',
			'1A03' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIQ6IImxOjCGMIQyOgQgiYk6sLYyOjo0iKDoFWl0bQhoCEBy38qsaStTV0UtzUJyH5o6qJhoKEgM3TxHLHY4oLslBCiG5uaBCj8qQizuAwDGM8qdaljtLQAAAABJRU5ErkJggg==',
			'FADF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDGUNDkMQCGhhDWBsdHRhQxFhbWRsC0cREGl0RYmAnhUZNW5m6KjI0C8l9aOqgYqKhmGLY1AHFMNwCFAtlRBEbqPCjIsTiPgB+UsynyBSDKgAAAABJRU5ErkJggg==',
			'0233' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGUIdkMRYA1hbWRsdHQKQxESmiDQ6NAQ0iCCJBbQyNDqARRHui1q6aumqqauWZiG5D6huCgNCHUwsgAHNPJEpjA7oYkC3NKC7hdFBNNQRzc0DFX5UhFjcBwBq1c0nY0EygAAAAABJRU5ErkJggg==',
			'E556' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDHaY6IIkFNIg0sDYwBARgiDE6CKCKhbBOZXRAdl9o1NSlSzMzU7OQ3Ac0p9GhIRDNPLCYgwiqeY2uGGKsrYyODih6Q0MYQxhCGVDcPFDhR0WIxX0AGBTM/Z6MNOMAAAAASUVORK5CYII=',
			'050F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMIaGIImxBog0MIQyOiCrE5ki0sDo6IgiFtAqEsLaEAgTAzspaunUpUtXRYZmIbkvoJWh0RWhDqcY0I5GRzQ7WANYW9HdwujAGAJ0M4rYQIUfFSEW9wEAbXXJHQY6rLUAAAAASUVORK5CYII=',
			'8076' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA6Y6IImJTGEMYWgICAhAEgtoZW1laAh0EEBRJ9Lo0OjogOy+pVHTVmYtXZmaheQ+sLopjGjmAcUCGB1E0OxgdEAVA7mFtYEBRS/YzQ0MKG4eqPCjIsTiPgDUHcu313QPbgAAAABJRU5ErkJggg==',
			'F5FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0MdkMQCGkQaWBsYHQKwiImgioUgiYGdFBo1denS0JVZ05DcBzSn0RVDLzYxESxirK2YbmEE2Yvi5oEKPypCLO4DAOEvy/o6x55ZAAAAAElFTkSuQmCC',
			'9F1B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQx2mMIY6IImJTBFpYAhhdAhAEgtoFWlgBIqJoIkxTIGrAztp2tSpYaumrQzNQnIfqyuKOgiE6kU2TwCLGNgtaHpZA4BuCXVEcfNAhR8VIRb3AQAcJsqBei0piAAAAABJRU5ErkJggg==',
			'3996' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RAMYQxhCGaY6IIkFTGFtZXR0CAhAVtkq0ujaEOgggCw2BSKG7L6VUUuXZmZGpmYhu28KY6BDSCCaeQyNDkC9IihiLI2OaGLY3ILNzQMVflSEWNwHAJz5y7/eDJWJAAAAAElFTkSuQmCC',
			'0105' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIYGIImxBjAGMIQCZZDERKYARR0dUcQCWhkCWBsCXR2Q3Be1FIQio6KQ3AdRF9AggqEXVUxkCgPYDhEUtwDdF8oQgOw+RgfWUIYpDFMdBkH4URFicR8ATVrIen6p8RoAAAAASUVORK5CYII=',
			'738D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkNZQxhCGUMdkEVbRVoZHR0dAlDEGBpdGwIdRJDFpjCA1Ykguy9qVdiq0JVZ05Dcx+iAog4MWRswzRPBIhbQgOmWgAYsbh6g8KMixOI+APlNypIdBUJLAAAAAElFTkSuQmCC',
			'47E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37poiGuoY6tKKIhTA0ujYwTHVAEmOEiAUEIImxTmFoZW1gdBBBct+0aaumLQ1dmTUNyX0BUxgCkNSBYWgoowO6GMMU1gZWNDsYpoiAxFDcAhZDd/NAhR/1IBb3AQD8hcszvfLdpQAAAABJRU5ErkJggg==',
			'6A14' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMDQEIImJTGEMYQhhaEQWC2hhbQWKtqKINYg0OkxhmBKA5L7IqGkrs6atiopCcl/IFJA6RgcUva2ioUCx0BAUMbB5aG7BFGMNEGl0DHVAERuo8KMixOI+AJp5zomTLP+GAAAAAElFTkSuQmCC',
			'2E80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxlCGVqRxUSmiDQwOjpMdUASC2gVaWBtCAgIQNbdClLn6CCC7L5pU8NWha7MmobsvgAUdWDI6AAyLxBFjLUB0w6RBky3hIZiunmgwo+KEIv7AGllys0bqW2tAAAAAElFTkSuQmCC',
			'BF50' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgNEQ11DHVqRxQKmiDSwNjBMdUAWawWLBQSgq5vK6CCC5L7QqKlhSzMzs6YhuQ+kjqEhEKYObh42MdaGAAw7GB0dUNwSGgDUFcqA4uaBCj8qQizuAwDT2818hGMLnQAAAABJRU5ErkJggg==',
			'BE07' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgNEQxmmMIaGIIkFTBFpYAhlaBBBFmsVaWB0dEAVA6pjbQgAQoT7QqOmhi1dFbUyC8l9UHWtDGjmAcWmoIsB7QhgwHALowMWN6OIDVT4URFicR8AKyDMvDwLaKUAAAAASUVORK5CYII=',
			'6B81' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGVqRxUSmiLQyOjpMRRYLaBFpdG0ICEURawCrg+kFOykyamrYqtBVS5HdFzIFRR1EbyvYPIJiIlj0Qt0cGjAIwo+KEIv7AJkTzKR6um66AAAAAElFTkSuQmCC',
			'0E2D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGUMdkMRYA0QaGB0dHQKQxESmiDSwNgQ6iCCJBbSCeHAxsJOilk4NW7UyM2sakvvA6loZMfVOQRUD2cEQgCoGdosDI4pbQG5mDQ1EcfNAhR8VIRb3AQD9aMmTvm9T+gAAAABJRU5ErkJggg==',
			'A180' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgDGAMYHR2mOiCJiUxhDWBtCAgIQBILaGUAqnN0EEFyX9TSVVGrQldmTUNyH5o6MAwNZQCaF4giBlKH3Q5UtwS0soaiu3mgwo+KEIv7ABhLygtSHvjvAAAAAElFTkSuQmCC',
			'DA7D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYAlhDA0MdkMQCpjCGMDQEOgQgi7WytoLERFDERBodGh1hYmAnRS2dtjJr6cqsaUjuA6ubwoimVzTUIQBdTARoGprYFJFG1wZGFLeEBoDFUNw8UOFHRYjFfQACgs2v9rg9dAAAAABJRU5ErkJggg=='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>