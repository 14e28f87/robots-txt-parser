<?php

class HttpTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Load library
	 */
	public static function setUpBeforeClass()
	{
		require_once(realpath(__DIR__.'/../RobotsTxtParser.php'));
	}

	public function testGoogleCom()
	{
		$robotsTxtContent = $this->getRobotsTxtContent('google.com');

		$parser = new RobotsTxtParser($robotsTxtContent);
		$rules = $parser->getRules('*');
		$this->assertNotEmpty($rules);
		$this->assertArrayHasKey('disallow', $rules);
		$this->assertGreaterThan(100, count($rules['disallow']), 'expected more than 100 disallow rules');
		$this->assertGreaterThan(5, count($rules['sitemap']), 'expected more than 5 sitemaps');
	}

	public function testRozetkaComUa()
	{
		$robotsTxtContent = $this->getRobotsTxtContent('rozetka.com.ua');

		$parser = new RobotsTxtParser($robotsTxtContent);
		$rules = $parser->getRules('*');

		$this->assertNotEmpty($rules);
		$this->assertArrayHasKey('disallow', $rules);
		$this->assertGreaterThan(3, count($rules['disallow']), 'expected more than 3 disallow rules');
		$this->assertNotEmpty($parser->getRules('mediapartners-google'), 'expected Mediapartners-Google rules');
	}

	private function getRobotsTxtContent($domain)
	{
		$robotsTxtContent = @file_get_contents("http://$domain/robots.txt");

		if ($robotsTxtContent === false) {
			$this->markTestSkipped('robots.txt not found');
		}

		return $robotsTxtContent;
	}
}
