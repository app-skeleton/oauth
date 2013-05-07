<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		OAuth Module
 * @author		Pap Tamas
 * @copyright	(c) 2011-2013 Pap Tamas
 * @website		http://github.com/paptamas
 * @license		http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Google_OAuth
{
    /**
     * Instance
     *
     * @var Google_Auth
     */
    protected static $_instance;

    /**
     * Google client
     *
     * @var Google_Client
     */
    protected $_google_client;

    /**
     * Google OAuth2 Service
     *
     * @var Google_Oauth2Service
     */
    protected $_oauth2;

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
	 * Setup the Google Client
	 */
	protected function __construct()
	{
		// Load config
		$this->_config = Kohana::$config->load('oauth/google');

        // Include vendor files
        require Kohana::find_file('Vendor', 'Google/Src/Google_Client');
        require Kohana::find_file('Vendor', 'Google/Src/Contrib/Google_Oauth2Service');

		// Create Google client
        $this->_google_client = new Google_Client();
        $this->_google_client->setApplicationName($this->_config['app_name']);

        $this->_google_client->setClientId($this->_config['client_id']);
        $this->_google_client->setClientSecret($this->_config['client_secret']);
        $this->_google_client->setDeveloperKey($this->_config['developer_key']);

        $this->_google_client->setApprovalPrompt($this->_config['approval_prompt']);
        $this->_google_client->setRedirectUri($this->_config['redirect_uri']);

        $this->_oauth2 = new Google_Oauth2Service($this->_google_client);

        // Init data array
        $this->_data = array();
	}

    /**
     * Get the access token (if exists)
     *
     * @throws Kohana_Exception
     * @return string
     */
	public function get_access_token()
	{
		if ($access_token = $this->_google_client->getAccessToken())
        {
            // Return current access token
            return $access_token;
        }
        else
        {
            // No access token set, check for it in session
            $session = Session::instance();
            $key = $this->_create_key('access_token');
            if ($access_token = $session->get($key))
            {
                $this->set_access_token($access_token);
                return $access_token;
            }
            else
            {
                // No access token in session, check for 'code' in GET
                if (isset($_GET['code'])) {
                    $this->_google_client->authenticate($_GET['code']);
                    $access_token = $this->_google_client->getAccessToken();

                    // Save to session
                    $session->set($key, $access_token);
                    return $access_token;
                }
                else
                {
                    // Can't get access token
                    throw new Kohana_Exception('Can not get access token.');
                }
            }
        }
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
     * Set the access token
     *
     * @param $access_token
     */
    public function set_access_token($access_token) {
        $this->_google_client->setAccessToken($access_token);
    }

    /**
     * Get the basic info about the logged user
     *
     * @throws Kohana_Exception
     * @return mixed
     */
    public function get_user_data() {
        if ($this->_data)
        {
            return $this->_data;
        }

        // Get data from Google
        $this->_data = $this->_oauth2->userinfo->get();

        return $this->_data;
    }

    /**
     * Create a login url
     *
     * @return	string
     */
    public function login_url()
    {
        return $this->_google_client->createAuthUrl();
    }

    /**
     * Logout - revokes token
     */
    public function logout()
    {
        $key = $this->_create_key('access_token');

        // Delete from session
        Session::instance()->delete($key);
    }

    /**
     * Generate a session key
     *
     * @param $key
     * @return string
     */
    protected function _create_key($key)
    {
        return 'google_'.md5($this->_config['client_id']).'_'.$key;
    }

    /**
   	 * Creates a singleton instance of the class
   	 *
   	 * @return	Google_OAuth
   	 */
   	public static function instance()
   	{
        if ( ! Google_OAuth::$_instance instanceof Google_OAuth)
        {
            Google_OAuth::$_instance = new Google_OAuth();
        }

        return Google_OAuth::$_instance;
   	}
}

// END Kohana_Google_OAuth
