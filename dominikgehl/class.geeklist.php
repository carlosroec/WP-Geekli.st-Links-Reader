<?php
require_once dirname(__FILE__) . '/OAuth.php';

class Geeklist {
	protected $consumerKey;
	protected $consumerSecret;
	protected $accessTokenKey;
	protected $accessTokenSecret;
	protected $endPoint;
	protected $responseHeaders;

	function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
		$this->accessTokenKey = null;
		$this->accessTokenSecret = null;
		$this->endPoint = 'http://api.geekli.st/v1';
	}

	public function setEndPoint($endPoint) {
		$this->endPoint = $endPoint;
	}

	public function setAccessToken($accessTokenKey, $accessTokenSecret) {
		$this->accessTokenKey = $accessTokenKey;
		$this->accessTokenSecret = $accessTokenSecret;
	}

	public function getRequestToken($type=null) {
		if (! is_null($type)) {
			return $this->_doCall('GET', '/oauth/request_token', array('oauth_callback' => $type));
		}
		return $this->_doCall('GET', '/oauth/request_token');
	}

	public function getAccessToken($oauth_token, $oauth_verifier) {
		return $this->_doCall('GET', '/oauth/access_token', array('oauth_token' => $oauth_token, 'oauth_verifier' => $oauth_verifier));
	}

	public function getUser($user=null) {
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user);
		}
		return $this->_doCall('GET', '/user');
	}

	public function getCards($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/cards', $params);
		}
		return $this->_doCall('GET', '/user/cards', $params);
	}

	public function getContributions($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/contribs', $params);
		}
		return $this->_doCall('GET', '/user/contribs', $params);
	}

	public function getCard($cardId) {
		return $this->_doCall('GET', '/cards/' . $cardId);
	}

	public function createCard($headline) {
		return $this->_doCall('POST', '/cards', array('headline' => $headline));
	}

	public function getMicros($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/micros', $params);
		}
		return $this->_doCall('GET', '/user/micros', $params);
	}

	public function getMicro($microId) {
		return $this->_doCall('GET', '/micros/' . $microId);
	}

	public function createMicro($status, $in_reply_to=null) {
		$params = array('status' => $status);
		if (! is_null($in_reply_to)) {
			$params['in_reply_to'] = $in_reply_to;
		}
		return $this->_doCall('POST', '/micros', $params);
	}

	public function getLinks($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/links', $params);
		}
		return $this->_doCall('GET', '/user/links', $params);
	}

	public function getPopularLinks($community=null) {
		$params = array();
		if (! is_null($community)) {
			$params['community'] = $community;
		}
		return $this->_doCall('GET', '/links', $params);
	}

	public function getLink($linkId) {
		return $this->_doCall('GET', '/links/' . $linkId);
	}

	public function createLink($url, $title, $category=null, $description=null, $communities=null) {
		$params = array('url' => $url, 'title' => $title);
		if (! is_null($category)) {
			$params['category'] = $category;
		}
		if (! is_null($description)) {
			$params['description'] = $description;
		}
		if (! is_null($communities)) {
			$params['communities'] = $communities;
		}
		return $this->_doCall('POST', '/micros', $params);
	}

	public function voteForLink($linkId, $direction) {
		if (! in_array($direction, array('up', 'down'))) {
			throw new InvalidArgumentException('direction must be up/down');
		}
		$params = array('direction' => $direction);
		return $this->_doCall('PUT', '/links/' . $linkId . '/vote', $params);
	}

	public function getFollowers($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/followers', $params);
		}
		return $this->_doCall('GET', '/user/followers', $params);
	}

	public function getFollowing($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/following', $params);
		}
		return $this->_doCall('GET', '/user/following', $params);
	}

	public function getConnections($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/connections', $params);
		}
		return $this->_doCall('GET', '/user/connections', $params);
	}

	public function follow($user) {
		return $this->_doCall('POST', '/users/' . $user . '/follow');
	}

	public function unfollow($user) {
		return $this->_doCall('DELETE', '/users/' . $user . '/follow');
	}

	public function getActivity($user=null, $page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		if (! is_null($user)) {
			return $this->_doCall('GET', '/users/' . $user . '/activity', $params);
		}
		return $this->_doCall('GET', '/user/activity', $params);
	}

	public function getAllActivity($page=null, $count=null) {
		$params = array();
		if (! is_null($page)) {
			$params['page'] = $page;
		}
		if (! is_null($count)) {
			$params['count'] = $count;
		}
		return $this->_doCall('GET', '/activity', $params);
	}

	public function highfive($item, $type) {
		return $this->_doCall('POST', '/highfive', array('gfk' => $item, 'type' => $type));
	}

	protected function _doCall($httpMethod, $path, $parameters=null) {
		$url = $this->endPoint . $path;
		$this->responseHeaders = array();
		$consumer = new OAuthConsumer($this->consumerKey, $this->consumerSecret);
		$accessToken = null;
		if (! is_null($this->accessTokenKey) && ! is_null($this->accessTokenSecret)) {
			$accessToken = new OAuthConsumer($this->accessTokenKey, $this->accessTokenSecret);
		}
		$request = OAuthRequest::from_consumer_and_token($consumer, $accessToken, $httpMethod, $url, $parameters);
		$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, $accessToken);
		if ($httpMethod != 'POST') {
			$curl = curl_init((is_null($parameters) || count($parameters) == 0) ? $url : $url. '?' . OAuthUtil::build_http_query($parameters));
		}
		else {
			$curl = curl_init($url);
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, array($request->to_header()));
		if ($httpMethod != 'GET') {
			if ($httpMethod == 'POST') {
				curl_setopt($curl, CURLOPT_POST, true);
				if (! is_null($parameters)) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
				}
			}
			else {
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpMethod);
			}
		}
		curl_setopt($curl, CURLOPT_HEADERFUNCTION, array($this,'_setHeader'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
		$result = curl_exec($curl);
		$returnObj = new StdClass();
		$returnObj->error = false;
		if ($result === false) {
			$returnObj->error = true;
		}
		else {
			$returnObj->httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if (($returnObj->httpCode < 200) || $returnObj->httpCode >= 400) {
				$returnObj->error = true;
			}
			$returnObj->response = $result;
			$returnObj->contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
			$returnObj->requestHeaders = preg_split('/(\\n|\\r){1,2}/', curl_getinfo($curl, CURLINFO_HEADER_OUT));

			$returnObj->responseHeaders = $this->responseHeaders;
			curl_close($curl);
		}
		return $returnObj;
	}

	public function _setHeader($curl,$headers) {
		$this->responseHeaders[] = trim($headers,"\n\r");
		return strlen($headers);
	}


}
?>