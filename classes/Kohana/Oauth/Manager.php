<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		OAuth Module
 * @author		Pap Tamas
 * @copyright	(c) 2011-2013 Pap Tamas
 * @website		http://github.com/paptamas
 * @license		http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_OAuth_Manager
{
    /**
     * Singleton instance
     *
     * @var OAuth_Manager
     */
    protected static $_instance;

    /**
     * Get oauth identity, based on oauth id and oauth provider
     *
     * @param $oauth_id
     * @param $oauth_provider
     * @throws OAuth_Exception
     * @return array
     */
    public function get_oauth_identity($oauth_id, $oauth_provider)
    {
        $oauth_identity = ORM::factory('OAuth_Identity')
            ->where('oauth_provider', '=', $oauth_provider)
            ->where('oauth_id', '=', $oauth_id)
            ->find();

        if ($oauth_identity->loaded())
        {
            return $oauth_identity->as_array();
        }

        throw new OAuth_Exception('Can not find the user.');
    }

    /**
     * Create an oauth identity
     *
     * @param $user_id
     * @param $oauth_id
     * @param $oauth_provider
     * @return array
     */
    public function create_oauth_identity($user_id, $oauth_id, $oauth_provider)
    {
        $oauth_identity = ORM::factory('OAuth_Identity');
        $oauth_identity->user_id = $user_id;
        $oauth_identity->oauth_id = $oauth_id;
        $oauth_identity->oauth_provider = $oauth_provider;

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
