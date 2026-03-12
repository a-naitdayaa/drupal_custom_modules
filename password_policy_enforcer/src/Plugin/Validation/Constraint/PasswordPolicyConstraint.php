<?php

declare(strict_types=1);

namespace Drupal\password_policy_enforcer\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Drupal\Core\Validation\Attribute\Constraint as ConstraintAttribute;
use Drupal\Core\StringTranslation\TranslatableMarkup;

#[ConstraintAttribute(
  id: 'UserPasswordPolicyConstraint',
  label: new TranslatableMarkup('Password Policy Constraint')
)]
class PasswordPolicyConstraint extends Constraint {
  public $message = 'The password does not satisfy the configured password policy.';

  /**
   * {@inheritdoc}
   */
  public function validatedBy(): string {
    return PasswordPolicyConstraintValidator::class;
  }
}
