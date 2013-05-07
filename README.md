Kohana OAuth module
====================

This is a Kohana module for Facebook and Google OAuth. This module is developed and maintained by Pap Tamas (me), and has nothing to do with the default Kohana Auth module.


##API

The list of main classes and their API:

### Facebook_OAuth

- get_access_token()
- has_access_token()
- set_access_token($access_token)
- get_user_data()
- login_url()
- logout_url($next_url)
- logout
- fql($query)
- graph($segment, $method = 'GET', $parameters = NULL)
- instance()



### Google_OAuth

- get_access_token()
- has_access_token()
- set_access_token($access_token)
- get_user_data()
- login_url()
- logout
- instance()



### OAuth_Manager

- get_oauth_identity($oauth_id, $oauth_provider)
- create_oauth_identity($user_id, $oauth_id, $oauth_provider)
- instance()

