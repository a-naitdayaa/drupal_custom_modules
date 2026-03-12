<?php

declare(strict_types=1);

namespace Drupal\content_entity_example;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;


/**
 * Access controller for the content_entity_example entity.
 */
class ContactAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *  Link the activities to the permissions.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    $admin_permission = $this->entityType->getAdminPermission();
    if ($account->hasPermission($admin_permission)) {
      return AccessResult::allowed();
    }

    switch ($operation) {
      case 'view':
        return AccessREsult::allowedIfHasPermission($account, 'view contact entity');
      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contact entity');
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contact entity');
    }
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    // Check the admin_permission as defined in #[ContentEntityType] attribute.
    $admin_permission = $this->entityType->getAdminPermission();
    if ($account->hasPermission($admin_permission)) {
      return AccessResult::allowed();
    }
    return AccessResult::allowedIfHasPermission($account, 'add contact entity');
  }
}
