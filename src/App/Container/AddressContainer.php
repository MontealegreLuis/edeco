<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Container;

use App\Form\Address\Detail;
use App\Model\Dao\AddressDao;
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\Cache\CachingAddressGateway;
use App\Model\Gateway\Cache\City;
use App\Model\Gateway\Cache\State;
use App\Service\AddressService;
use Mandragora\Application\Doctrine\Manager;
use Mandragora\FormFactory;
use Zend_Application_Bootstrap_BootstrapAbstract as Bootstrap;
use Zend_Cache_Core as Cache;
use Zend_Cache_Manager as CacheManager;
use Zend_Controller_Front as FrontController;

class AddressContainer
{
    public function getAddressService(): AddressService
    {
        $this->getDoctrineManager()->setup();

        return new AddressService(
            $this->getGateway(),
            $this->getForm(),
            $this->getCityGateway(),
            $this->stateGateway()
        );
    }

    private function getForm(): Detail
    {
        $formFactory = FormFactory::useConfiguration(
            $this->getCacheManager()->getCache('form')
        );
        return $formFactory->create('Detail', 'Address');
    }

    private function getDoctrineManager(): Manager
    {
        return $this->getBootstrap()->getResource('doctrine');
    }

    private function getGateway(): AddressGateway
    {
        $addressGateway = new CachingAddressGateway(
            new AddressGateway(new AddressDao())
        );
        $addressGateway->setCache($this->getCacheForGateway());
        return $addressGateway;
    }

    private function stateGateway(): State
    {
        $stateGateway = new State(new \App\Model\Gateway\State(new StateDao()));
        $stateGateway->setCache($this->getCacheForGateway());

        return $stateGateway;
    }

    private function getCityGateway(): City
    {
        $cityGateway = new City(new \App\Model\Gateway\City(new CityDao()));
        $cityGateway->setCache($this->getCacheForGateway());

        return $cityGateway;
    }

    private function getCacheForGateway(): Cache
    {
        return $this->getCacheManager()->getCache('gateway');
    }

    private function getBootstrap(): Bootstrap
    {
        return FrontController::getInstance()->getParam('bootstrap');
    }

    private function getCacheManager(): CacheManager
    {
        return $this->getBootstrap()->getResource('cachemanager');
    }
}
