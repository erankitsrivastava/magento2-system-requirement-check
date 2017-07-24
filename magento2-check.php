<?php

/**
 * A simple fix for a shell execution on preg_match('/[0-9]\.[0-9]+\.[0-9]+/', shell_exec('mysql -V'), $version);
 * The only edit that was done is that shell_exec('mysql -V') was changed to mysql_get_server_info() because not all
 * systems have shell access. XAMPP, WAMP, or any Windows system might not have this type of access. mysql_get_server_info()
 * is easier to use because it pulls the MySQL version from phpinfo() and is compatible with all Operating Systems.
 * @link http://www.magentocommerce.com/knowledge-base/entry/how-do-i-know-if-my-server-is-compatible-with-magento
 * @author Magento Inc.
 */


 
 function extension_check($extensions) {
  $fail = '';
  $pass = '';
	
	if(is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec')){
		if(shell_exec('composer -v')!=''){
			if(strpos(shell_exec('composer -v'),'Composer version')){
				$pass .='<li>You have successfully installed composer.</li>';
			}else{
				$fail .='<li>You have not installed composer.</li>';
			}
			
			
		}
		
	}else{
		$fail .= '<li>shell_exec is not enabled. please check composer availability using command  line "composer -v"</li>';
	}
	if(version_compare(phpversion(), '7.0.6', '<') || phpversion()!='7.0.2' || phpversion()!='7.0.4') {
		$fail .= '<li>You need<strong> PHP  7.0.2, 7.0.4, and 7.0.6 </strong> (or greater)</li>';
	} else {
		$pass .='<li>You have<strong> PHP 7.0.2, 7.0.4, and 7.0.6</strong> (or greater)</li>';
	}

	if(!ini_get('safe_mode')) {
		$pass .='<li>Safe Mode is <strong>off</strong></li>';
		preg_match('/[0-9]\.[0-9]+\.[0-9]+/', mysql_get_server_info(), $version);
		
		if(version_compare($version[0], '5.6', '<')) {
			$fail .= '<li>You need<strong> MySQL 5.6</strong> (or greater)</li>';
		} else {
			$pass .='<li>You have<strong> MySQL 5.6</strong> (or greater)</li>';
		}
	} else { $fail .= '<li>Safe Mode is <strong>on</strong></li>';  }

	foreach($extensions as $extension) {
		
		if(!extension_loaded($extension)) {
			$fail .= '<li> You are missing the <strong>'.$extension.'</strong> extension</li>';
		} else{	
			$pass .= '<li>You have the <strong>'.$extension.'</strong> extension</li>';
		}
	}
	
	if(version_compare(phpversion(), '7.0', '>')){
		if(!extension_loaded('json')) {
			$fail .= '<li> You are missing the <strong>json</strong> extension</li>';
		} else{	
			$pass .= '<li>You have the <strong>json</strong> extension</li>';
		}
		if(!extension_loaded('iconv')) {
			$fail .= '<li> You are missing the <strong>iconv</strong> extension</li>';
		} else{	
			$pass .= '<li>You have the <strong>iconv</strong> extension</li>';
		}
	}
	
	if($fail) {
		echo '<p><strong>Your server does not meet the following requirements in order to install Magento.</strong>';
		echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for Magento:';
		echo '<ul>'.$fail.'</ul></p>';
		echo 'The following requirements were successfully met:';
		echo '<ul>'.$pass.'</ul>';
	} else {
		echo '<p><strong>Congratulations!</strong> Your server meets the requirements for Magento.</p>';
		echo '<ul>'.$pass.'</ul>';

	}
}

extension_check(array( 
	'curl',
	'gd',
	'dom', 
	'hash',
	'imagick',
	'iconv',
	'intl',
	'mbstring',
	'mcrypt',
	'mhash',
	'openssl',
	'pcre', 
	'pdo', 
	'pdo_mysql', 
	'simplexml',
	'soap',
	'xml',
	'xsl',
	'zip',
	
));