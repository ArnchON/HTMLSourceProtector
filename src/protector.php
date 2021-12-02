<?php
namespace Html;
use \Exception;

ini_set('memory_limit', '-1');
set_time_limit(0);


if ( version_compare(PHP_VERSION, '5.0.0') < 0 )
{
	throw new Exception('PHP 5.0 or greater is required');
}

class Protector {
	const VERSION = '1.0';
	
	public $variable;
	public $function_name;	
	public $html_tag;
	public $char_per_line = 111;
	

	public function __construct()
	{
		 $this->variable  = 'crypt_data';
		 $this->function_name  = 'cryptor';
		 $this->html_tag  = 'cryptor_div';
		 
		 if($this->char_per_line % 3 != 0)
		 {
			 throw new Exception('Characters per line must be divisible by 3');
		 }
	}
	
	public function Encrypt()
	{
		ob_start(array($this,'HtmlEncryptor'));
	}
	
	public function EncryptionEnd()
	{
		ob_end_flush();
	}
	
	
	public function HtmlEncryptor($buffer)
	{
		$code = "<script type=\"text/javascript\" language=\"Javascript\">function ".$this->function_name."(s){var i=0,out='';l=s.length;for(;i<l;i+=3){out+=String.fromCharCode(parseInt(s.substr(i,2),16));}document.write(out);}</script>";
		$out = $this->function_name."(".$this->variable.");\n</script>";
		

		$output  = "<head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1,  user-scalable=no\"></head><script type=\"text/javascript\" language=\"Javascript\">\n";
		$output .= "/*\n";
		$output .= " _____________________________________________________________________________________  \n";
		$output .= "|  HTML SourceProtector                                                               | \n";
		$output .= "|  Built by OmegaLolBro <https://github.com/OmegaLolBro/HTMLSourceProtector>          | \n";
		$output .= "|  Made by : OmegaLolBro                                                              | \n";
		$output .= "|  This Website is protected against people stealing our original source code         | \n";
		$output .= "|_____________________________________________________________________________________| \n\n";
		$output .= " */\n";
		$output .= "document.write(unescape('".$this->JsEscape($code)."'));\n";
		$output .= "var ".$this->variable."='';\n";
		$output .= $this->Encryptor($buffer);
		$output .= $out;
		$noscript = '<noscript><div style="color:white;background:red;padding:20px;text-align:center"><tt><strong><big>For functionality of this site it is necessary to enable JavaScript. <br><br> Here are the <a target="_blank"href="http://www.enable-javascript.com/" style="color:white">instructions how to enable JavaScript in your web browser</a>.</big></strong></tt></div></noscript>';

		return($output.$noscript);
		
	}
	
	public function Encryptor( $in ) {
		
		$out = '';
		$in = utf8_decode($in);
		$in = htmlentities($in);
		$in = $this->HTMLSpecialCharsDecode($in);
		for ( $i = 0; $i < strlen( $in ); $i++)
		{
			$hex = dechex( ord($in[$i]) );
			if ( $hex == '' )
			{
				$temp = urlencode( $in[$i] );
				$temp = str_replace('%', '', $temp);
				$out = $out.$temp.'.';
			} else {
				$out = $out.((strlen($hex)==1) ? ( '0'.strtoupper( $hex ) ):( strtoupper( $hex ) ) ).'.';
			}
		}
		$out = str_replace('+', '20.', $out);
		$out = str_replace('_', '5F.', $out);
		$out = str_replace('-', '2D.', $out);
		$out = $this->variable."+='".chunk_split($out,$this->char_per_line, "';\n".$this->variable."+='")."';\n";
		$out = str_replace("cryptor_data+='';\n", '', $out);

		return $out;
		
	}
	
	public function HTMLSpecialCharsDecode($str, $quote_style = ENT_COMPAT)
	{
		return strtr($str, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	}
	
	public function JsEscape($in)
	{
		$out = '';
		for ($i=0;$i<strlen($in);$i++)
		{
			$hex = dechex(ord($in[$i]));
			if ($hex=='')
				$out = $out.urlencode($in[$i]);
		    else
			$out = $out .'%'.((strlen($hex)==1) ? ('0'.strtoupper($hex)):(strtoupper($hex)));
		}
		
		return $out;
   
   }
	
	
	
}
