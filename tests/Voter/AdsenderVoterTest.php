<?php

namespace Webcook\Cms\SecurityBundle\Tests\Controller;

use Webcook\Cms\SecurityBundle\Authorization\Voter\WebcookCmsVoter;

class WebcookCmsVoterTest extends \Webcook\Cms\CommonBundle\Tests\BasicTestCase
{
	private $WebcookCmsVoter;

	public function setUp()
	{
		parent::setUp();
		$this->WebcookCmsVoter = new WebcookCmsVoter($this->em);
	}

	public function testVoterClass()
	{
		$this->assertTrue($this->WebcookCmsVoter->supportsClass(''));
	}

	public function testVoterException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$tokenInterface = $this->mockTokenInterface();

		$this->WebcookCmsVoter->vote($tokenInterface, null, array());
	}

	public function testVoterDenied()
	{
		$tokenInterface = $this->mockTokenInterface();

		$attributes = array();
		$attributes[] = WebcookCmsVoter::ACTION_VIEW;

		$result = $this->WebcookCmsVoter->vote($tokenInterface, 'bad-resource', $attributes);

		$this->assertEquals(-1, $result);
	}

	private function mockTokenInterface()
	{
		$tokenInterface = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

		$user = $this->getMock('Webcook\Cms\SecurityBundle\Entity', array('getRoles'));

		$user->expects($this->any())
			->method('getRoles')
			->will($this->returnValue(array()));

		$tokenInterface->expects($this->any())
			->method('getUser')
			->will($this->returnValue($user));

		return $tokenInterface;
	}
}