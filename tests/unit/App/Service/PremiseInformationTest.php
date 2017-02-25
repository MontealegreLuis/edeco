<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\PremiseInformation;
use App\Service\PremiseInformation as PremiseInformationService;
use PHPUnit_Framework_TestCase as TestCase;

class PremiseInformationTest extends TestCase
{
    /** @test */
    function it_creates_a_premise_information_model()
    {
        $this->assertInstanceOf(PremiseInformation::class, $this->premiseService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->premiseService = new PremiseInformationService('PremiseInformation');
    }

    /** @var PremiseInformationService */
    private $premiseService;
}
