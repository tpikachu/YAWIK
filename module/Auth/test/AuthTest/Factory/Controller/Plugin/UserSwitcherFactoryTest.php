<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace AuthTest\Factory\Controller\Plugin;

use Auth\AuthenticationService;
use Auth\Factory\Controller\Plugin\UserSwitcherFactory;
use CoreTestUtils\TestCase\ServiceManagerMockTrait;
use CoreTestUtils\TestCase\TestInheritanceTrait;
use Zend\ServiceManager\FactoryInterface;

/**
 * Tests for \Auth\Factory\Controller\Plugin\USerSwitcherFactory
 * 
 * @covers \Auth\Factory\Controller\Plugin\USerSwitcherFactory
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @group Auth
 * @group Auth.Factory
 * @group Auth.Factory.Controller
 * @group Auth.Factory.Controller.Plugin
 */
class UserSwitcherFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestInheritanceTrait, ServiceManagerMockTrait;

    /**
     *
     *
     * @var array|UserSwitcherFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $target = [
        UserSwitcherFactory::class,
        '@testCreateServiceInvokesItself' => [
            'mock' => [ '__invoke' ],
        ],
    ];

    private $inheritance = [ FactoryInterface::class ];

    public function testCreateServiceInvokesItself()
    {
        $container = $this->getServiceManagerMock();
        $plugins   = $this->getPluginManagerMock($container);
        $this->target->expects($this->once())->method('__invoke')->with($container, 'Auth/User/Switcher');

        $this->target->createService($plugins);
    }

    public function testInvokationCreatesPluginInstance()
    {
        $auth = $this->getMockBuilder(AuthenticationService::class)->disableOriginalConstructor()->getMock();
        $container = $this->getServiceManagerMock(['AuthenticationService' => ['service' => $auth, 'count_get' => 1]]);

        $plugin = $this->target->__invoke($container, 'Test');

        $this->assertAttributeSame($auth, 'auth', $plugin);
    }
}