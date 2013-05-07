<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		OAuth Module
 * @author		Pap Tamas
 * @copyright	(c) 2011-2013 Pap Tamas
 * @website		http://github.com/paptamas
 * @license		http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Facebook_OAuth
{
    /**
     * Instance
     *
     * @var Facebook_OAuth
     */
    protected static $_instance;

    /**
     * Facebook object
     *
     * @var Facebook
     */
    protected $_facebook;

    /**
     * User data
     *
     * @var array
     */
    protected $_data;

    /**
     * Config object
     *
     * @var Config
     */
    protected $_config;
	
	/**
	 * Create the Facebook object
	 */
	protected function __construct()
	{
		// Load config
		$this->_config = Kohana::$config->load('oauth/facebook');

        /**
         * Prevent Kohana session bug
         *
         * The vendor script will start the session if not started, but we must make sure
         * that the session is started by the Session class to prevent any Session_Exception
         *
         * We have to start the session
         */
        Session::instance();

        require Kohana::find_file('Vendor', 'Facebook/Facebook');

		// Create Facebook object
		$this->_facebook = new Facebook(
			array(
				'appId'  => $this->_config['app_id'],
				'secret' => $this->_config['secret']
			)
		);
	}

    /**
     * Try to get the user access token
     *
     * @throws Kohana_Exception
     * @return string
     */
    public function get_access_token()
    {
        $application_access_token = $this->_facebook->getAppId().'|'.$this->_facebook->getAppSecret();
        $access_token = $this->_facebook->getAccessToken();

        if ($access_token != $application_access_token)
        {
            return $access_token;
        }

        // Can't get access token
        throw new Kohana_Exception('Can not get access token.');
	}

    /**
     * Check if an access token is set
     *
     * @return bool
     */
    public function has_access_token()
    {
        try
        {
            $this->get_access_token();
            return TRUE;
        }
        catch (Exception $e)
        {
            return FALSE;
        }
    }

    /**
     * Set access token
     *
     * @param $access_token
     */
    public function set_access_token($access_token)
    {
        $this->_facebook->setAccessToken($access_token);
    }

    /**
     * Get the basic info about the logged user
     *
     * @return mixed
     */
    public function get_user_data() {
        if ($this->_data)
        {
            return $this->_data;
        }

        // Get data from Facebook
        $this->_data = $this->_facebook->api('/me');

        return $this->_data;
    }

    /**
     * Create a login url, based on req_perms, next, and cancel_url
     *
     * @return	string
     */
    public function login_url()
    {
        return $this->_facebook->getLoginUrl(
            array
            (
                'scope'		    => $this->_config['req_perms'],
                'next'			=> $this->_config['next'],
                'cancel_url'	=> $this->_config['cancel_url']
            ));
    }

    /**
     * Create a logout url
     *
     * @param $next_url
     * @return	string
     */
    public function logout_url($next_url)
    {
        return $this->_facebook->getLogoutUrl(array(
            'next' => $next_url
        ));
    }

    /**
     * Destroy Facebook session
     */
    public function logout()
    {
        $this->_facebook->destroySession();
    }

    /**
     * Performs an fql query
     *
     * example:
     *  $facebook_oauth->fql('SELECT uid2 FROM friend WHERE uid1 = me()'); //gets the list of friends of the user
     *
     * @param $query
     * @return mixed
     * @throws Kohana_Exception
     */
    public function fql($query) {
        $fql_query  =   array(
            'method' => 'fql.query',
            'query' => $query
        );

        return $this->_facebook->api($fql_query);
    }

    /**
     * Performs a request to graph api
     * example:
     *  $facebook_oauth->graph('/me/friends'); // gets the list of friends of the user
     *  $facebook_oauth->graph('/me/feed', 'POST', array('link' => 'www.example.com', 'message' => 'Posting with the PHP SDK!' )); // posts to users wall
     *
     * @param $segment
     * @param string $method
     * @param null $parameters
     * @return mixed
     * @throws Kohana_Exception
     */
    public function graph($segment, $method = 'GET', $parameters = NULL) {
        return $this->_facebook->api($segment, $method, $parameters);
    }

    /**
   	 * Creates a singleton instance of the class
   	 *
   	 * @return	Facebook_OAuth
   	 */
   	public static function instance()
   	{
        if ( ! Facebook_OAuth::$_instance instanceof Facebook_OAuth)
        {
            Facebook_OAuth::$_instance = new Facebook_OAuth();
        }

        return Facebook_OAuth::$_instance;
   	}
	
}

// END Kohana_Facebook_OAuth
