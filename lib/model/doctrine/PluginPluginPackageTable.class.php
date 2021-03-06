<?php

/**
* Copyright 2010 Kousuke Ebihara
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

/**
 * PluginPluginPackageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    opPluginChannelServerPlugin
 * @subpackage model
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PluginPluginPackageTable extends opAccessControlDoctrineTable
{
  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('sns_member'), 'anonymous')
      ->addRole(new Zend_Acl_Role('contributor'), 'sns_member')
      ->addRole(new Zend_Acl_Role('developer'), 'contributor')
      ->addRole(new Zend_Acl_Role('lead'), 'developer');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    return $acl
      ->allow('anonymous', $resource, 'view')
      ->allow('sns_member', $resource, 'create')
      ->allow('sns_member', $resource, 'request')
      ->allow('sns_member', $resource, 'countUser')
      ->deny('contributor', $resource, 'request')
      ->allow('lead', $resource, 'edit')
      ->allow('lead', $resource, 'delete')
      ->allow('lead', $resource, 'release')
      ->allow('lead', $resource, 'manageMember')
    ;
  }

  public function getMemberPlugin($memberId, $size = 20)
  {
    return $this->createQuery()
      ->where('id IN (SELECT pm.package_id FROM PluginMember pm WHERE pm.member_id = ? AND pm.is_active = ?)', array($memberId, true))
      ->limit($size)
      ->execute();
  }

  public function getMemberPluginPager($memberId, $page = 1, $size = 20)
  {
    $q = $this->createQuery()
      ->where('id IN (SELECT pm.package_id FROM PluginMember pm WHERE pm.member_id = ? AND pm.is_active = ?)', array($memberId, true));

    $pager = new sfDoctrinePager('PluginPackage', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
