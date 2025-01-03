<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Contact\View\Helper\ContactHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * ContactHelper tests.
 */
#[UsesClass(ContactHelper::class)]
#[CoversClass(ContactHelper::class)]
class ContactHelperTest extends TestCase
{
    private ?ContactHelper $helper = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->helper = new ContactHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->helper = null;
    }

    /**
     * @test
     */
    public function phone()
    {
        $output = $this->helper->phone('07-795-841-283', ['country' => 'GB', 'format' => 'short']);
        self::assertSame('+447795841283', $output);
    }
}
