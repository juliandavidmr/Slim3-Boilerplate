<?php
namespace Tests\Functional;

final class HomepageTest extends BaseTestCase {

	public function testGetHomepageWithoutName() {
		$response = $this->runApp('GET', '/');
				
		$this->assertEquals(200, $response->getStatusCode(), "Codigo de respuesta no es igual");
		$this->assertContains('microframework', (string) $response->getBody(), "No contiene SlimFramework");
	}

	/**
	 * Test that the index route with optional name argument returns a rendered greeting
	 */
	public function testGetHomepageWithGreeting() {
		$response = $this->runApp('GET', '/julian');
		
		$this->assertEquals(404, $response->getStatusCode());
		$this->assertContains('Page Not Found', (string) $response->getBody());
	}

	/**
	 * Test that the index route won't accept a post request
	 */
	public function testPostHomepageNotAllowed() {
		$response = $this->runApp('POST', '/', [
			'test'
		]);
		
		$this->assertEquals(405, $response->getStatusCode());
		$this->assertContains('Method not allowed', (string) $response->getBody());
	}

	#region Test Login
	public function testPostLoginFailDataEmpty() {
		$response = $this->runApp('POST', '/auth/login', array(
			"email" => "",
			"pass" => ""
		));

		$this->assertContains('Acceso denegado', (string) $response->getBody());
	}

	public function testPostLoginFailWithoutData() {
		$response = $this->runApp('POST', '/auth/login');

		$this->assertContains('Acceso denegado', (string) $response->getBody());
	}
	
	public function testPostLoginDataIncorrect() {
		$response = $this->runApp('POST', '/auth/login', array(
			"email" => "jul.mora",
			"pass" => "123123123abcabc"
		));

		$this->assertContains('no son correctos', (string) $response->getBody());
	}
	#endregion

	#region Dynamic Route
	/* public function testGetDynamicRoute() {
		$response = $this->runApp('POST', '/dynamic/query/1');

		$this->assertContains('no son correctos', (string) $response->getBody());
	} */
	#endregion
}