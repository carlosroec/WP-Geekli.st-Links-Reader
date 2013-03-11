<?php

require_once dirname(__FILE__) . '/conf.php';
require_once dirname(__FILE__) . '/../class.geeklist.php';

class GeeklistTest extends PHPUnit_Framework_TestCase {

	protected $geeklist;

	protected function setUp() {
		$this->geeklist = new Geeklist(CONSUMER_KEY, CONSUMER_SECRET);
		$this->geeklist->setAccessToken(ACCESSTOKEN_KEY, ACCESSTOKEN_SECRET);
		$this->geeklist->setEndPoint(ENDPOINT);
	}

	public function testGetUser() {
		$result = $this->geeklist->getUser();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('id', $resultData['data']);
		$this->assertArrayHasKey('screen_name', $resultData['data']);
	}

	public function testGetCards() {
		$result = $this->geeklist->getCards();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('total_cards', $resultData['data']);
	}

	public function testGetCardsWithPagination() {
		$result = $this->geeklist->getCards(null,1,1);
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('total_cards', $resultData['data']);
	}

	public function testCreateCard() {
		$result = $this->geeklist->createCard('testing ' . time());
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('author_id', $resultData['data']);
		$this->assertArrayHasKey('headline', $resultData['data']);
	}

	public function testGetMicros() {
		$result = $this->geeklist->getMicros();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('total_micros', $resultData['data']);
	}

	public function testGetFollowers() {
		$result = $this->geeklist->getFollowers();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('total_followers', $resultData['data']);
	}

	public function testGetFollowing() {
		$result = $this->geeklist->getFollowing();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertArrayHasKey('total_following', $resultData['data']);
	}

	public function testGetActivity() {
		$result = $this->geeklist->getActivity();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertInternalType('array', $resultData['data']);
	}

	public function testGetAllActivity() {
		$result = $this->geeklist->getAllActivity();
		$this->assertInstanceOf('StdClass', $result);
		$this->assertFalse($result->error);
		$this->assertEquals(200, $result->httpCode);
		$resultData = json_decode($result->response,true);
		$this->assertArrayHasKey('status', $resultData);
		$this->assertEquals('ok', $resultData['status']);
		$this->assertArrayHasKey('data', $resultData);
		$this->assertInternalType('array', $resultData['data']);
	}
}
