<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @package		OAuth Module
 * @author      Pap Tamas
 * @copyright   (c) 2011-2012 Pap Tamas
 * @website		https://github.com/paptamas/kohana-oauth
 * @license		http://www.opensource.org/licenses/isc-license.txt
 *
 */

class Model_Kohana_Oauth_Identity extends ORM {

    protected $_table_name = 'oauth_identities';

    protected $_primary_key = 'identity_id';

    protected $_table_columns = array(
        'identity_id' => array(),
        'user_id' => array(),
        'oauth_id' => array(),
        'oauth_provider' => array()
    );

    protected $_belongs_to = array(
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        )
    );
}

// END Model_Kohana_Oauth_Identity
