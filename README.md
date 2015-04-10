# curly
## Helps sending curl requests more efficiently
### List of all arguments
    curly($url (string), $options (array))
    $url = 'http://www.test.com';
    $options=array(
    	'cookies_file'	=> 'cookies.txt', // store cookies always in the same file for several calls
    	'postfields'	=> array('login'=>'admin', 'password'=>'azerty123'), // activates post query
    	'xpath'			=> '//a/href', // retrieve xpath data
    	'delimiters'	=> array('<h1>', '</h1>'), // delimiters
    	'proxy' 		=> array('host'=>'192.168.1.1:8080', 'login'=>'login:password'),
    	'setopt'		=> array('CURLOPT_CONNECTTIMEOUT'=>value)
    	)

### Example to retrieve cloudflare's token + login

**Include curly**

    require_once('curly.php');

**First, we have to get the token on the login page**

    // step 1
    $options = array();
    $options['xpath'] = "//input[@name='security_token']/@value";
    $options['cookies_file'] = 'cookie.txt';
    $security_token = curly("https://www.cloudflare.com/login", $options);
    $security_token = $security_token['xpath'];

**Then, we can login**

    // step 2
    $options = array();
		$postfields = array();
		$postfields["login_email"]='username@gmail.com'; // input 
		$postfields["login_pass"]='qwerty123'; // input 
		$postfields["autologin"]="1"; // input 
		$postfields[""]="Login to CloudFlare"; // input 
		$postfields["security_token"]= $security_token; // input 
		$postfields["act"]="login"; // input 
	$options['postfields'] = $postfields;
	$options['cookies_file'] = 'cookie.txt';
	$ret = curly("https://www.cloudflare.com/login", $options);

## Other examples

**We can scrap easily with delimiters**

	$options['delimiters'] = array('<h1>Votre adresse IP : ', '</h1>');
	$ret = curly("http://1ip.fr", $options);


**We can also use a proxy**

	$options['delimiters'] = array('<h1>Votre adresse IP : ', '</h1>');
	$options['proxy'] = array('host'=>'192.168.1.1:8080', 'login'=>'login:password');
	$ret = curly("http://1ip.fr", $options);

**We can pass raw arguments**

	$options['setopt'] = array('CURLOPT_CONNECTTIMEOUT'=> 0, 'CURLOPT_TIMEOUT' => 400);
	$ret = curly("http://1ip.fr", $options);