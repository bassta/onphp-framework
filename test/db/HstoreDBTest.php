<?php

namespace onphp\test\db;

use onphp\core\Base\Timestamp;
use onphp\core\Cache\Cache;
use onphp\core\DB\DBPool;
use onphp\core\Form\FormUtils;
use onphp\core\Form\Primitive;
use onphp\main\Base\Hstore;
use onphp\test\meta\Business\Credentials;
use onphp\test\meta\Business\TestCity;
use onphp\test\meta\Business\TestUser;
use onphp\test\misc\DBTestPool;
use onphp\test\misc\TestCaseDAO;

class HstoreDBTest extends TestCaseDAO
{
    /**
     * Install hstore
     * /usr/share/postgresql/contrib # cat hstore.sql | psql -U pgsql -d onphp
     **/
    public function testHstore()
    {
        foreach (DBTestPool::me()->getPool() as $connector => $db) {
            DBPool::me()->setDefault($db);
            $properties = array('age' => '23', 'weight' => 80, 'comment' => null);
            $user       = TestUser::create()->setCity($moscow = TestCity::create()->setName('Moscow'))
                                  ->setCredentials(Credentials::create()->setNickname('fake')
                                                              ->setPassword(sha1('passwd')))
                                  ->setLastLogin(Timestamp::create(time()))->setRegistered(Timestamp::create(time())
                                                                                                    ->modify('-1 day'))
                                  ->setProperties(Hstore::make($properties));
            $moscow     = TestCity::dao()->add($moscow);
            $user       = TestUser::dao()->add($user);
            Cache::me()->clean();
            TestUser::dao()->dropIdentityMap();
            $user = TestUser::dao()->getById('1');
            $this->assertInstanceOf('Hstore', $user->getProperties());
            $this->assertEquals($properties, $user->getProperties()->getList());
            $form = TestUser::proto()->makeForm();
            $form->get('properties')
                 ->setFormMapping(array(Primitive::string('age'), Primitive::integer('weight'), Primitive::string('comment')));
            $form->import(array('id' => $user->getId()));
            $this->assertNotNull($form->getValue('id'));
            $object = $user;
            FormUtils::object2form($object, $form);
            $this->assertInstanceOf('Hstore', $form->getValue('properties'));
            $this->assertEquals(array_filter($properties), $form->getValue('properties')->getList());
            $subform = $form->get('properties')->getInnerForm();
            $this->assertEquals($subform->getValue('age'), '23');
            $this->assertEquals($subform->getValue('weight'), 80);
            $this->assertNull($subform->getValue('comment'));
            $user = new TestUser();
            FormUtils::form2object($form, $user, false);
            $this->assertEquals($user->getProperties()->getList(), array_filter($properties));
        }
    }
}