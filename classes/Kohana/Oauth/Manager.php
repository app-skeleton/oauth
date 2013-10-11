<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		OAuth Module
 * @author		Pap Tamas
 * @copyright	(c) 2011-2013 Pap Tamas
 * @website		http://github.com/paptamas
 * @license		http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_OAuth_Manager extends Service_Manager {

    /**
     * Singleton instance
     *
     * @var OAuth_Manager   Singleton instance of the OAuth Manager
     */
    protected static $_instance;

    /**
     * Get oauth identity, based on oauth id and oauth provider
     *
     * @param   int     $oauth_id
     * @param   string  $oauth_provider
     * @throws  Kohana_Exception
     * @return  array
     */
    public function get_oauth_identity($oauth_id, $oauth_provider)
    {
        $oauth_identity = ORM::factory('OAuth_Identity')
            ->where('oauth_provider', '=', $oauth_provider)
            ->where('oauth_id', '=', $oauth_id)
            ->find();

        if ( ! $oauth_identity->loaded())
        {
            throw new Kohana_Exception(
                'Can not find the oauth identity with oauth id :oauth_id, and oauth provider :oauth_provider.', array(
                ':oauth_id' => $oauth_id,
                ':oauth_provider' => $oauth_provider
            ), Kohana_Exception::E_RESOURCE_NOT_FOUND);
        }

        return $oauth_identity->as_array();
    }

    /**
     * Create an oauth identity
     *
     * @param   int     $user_id
     * @param   int     $oauth_id
     * @param   string  $oauth_provider
     * @return  array
     */
    public function create_oauth_identity($user_id, $oauth_id, $oauth_provider)
    {
        // Create the oauth identity
        $oauth_identity = ORM::factory('OAuth_Identity');
        $oauth_identity->set('user_id', $user_id);
        $oauth_identity->set('oauth_id', $oauth_id);
        $oauth_identity->set('oauth_provider', $oauth_provider);

        // Save the oauth identity
        $oauth_identity->save();

        return $oauth_identity->as_array();
    }

    /**
     * Create a singleton instance of the class
     *
     * @return	OAuth_Manager
     */
    public static function instance()
    {
        if ( ! OAuth_Manager::$_instance instanceof OAuth_Manager)
        {
            OAuth_Manager::$_instance = new OAuth_Manager();
        }

        return OAuth_Manager::$_instance;
    }
}

// End Kohana_OAuth_Manager
