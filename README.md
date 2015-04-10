# curly
## Helps sending curl requests more efficiently
### Example to retrieve cloudflare's token + login

**Include curly**

    require_once('curly.php');

**First, we have to get the token on the login page**

    // step 1
    $options = array();
    $options['xpath'] = "//input[@name='security_token']/@value";
    $options['cookies_file'] = 'cookie.txt';
    $security_token = curly("https://www.cloudflare.com/login", $options); $security_token = $security_token['xpath'];

**Then, we can login**

    // step 2
    $options = array();
		$postfields = array();
		$postfields["login_email"]=$this->email; // input 
		$postfields["login_pass"]=$this->password; // input 
		$postfields["autologin"]="1"; // input 
		$postfields[""]="Login to CloudFlare"; // input 
		$postfields["security_token"]= $security_token; // input 
		$postfields["act"]="login"; // input 
	$options['postfields'] = $postfields;
	$options['cookies_file'] = 'cookie.txt';
	$ret = curly("https://www.cloudflare.com/login", $options);