<?php

declare(strict_types=1);

namespace Drupal\password_policy_enforcer\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordPolicyConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  protected mixed $passwordPolicyValidator;
  public function __construct(mixed $passwordPolicyValidator)
  {
    $this->passwordPolicyValidator = $passwordPolicyValidator;
  }

  public static function create(ContainerInterface $container): static
  {
    return new static(
      $container->get('password_policy.validator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, Constraint $constraint): void
  {
    \Drupal::logger('password_policy_enforcer')->debug('Validator called');

    if (!$constraint instanceof PasswordPolicyConstraint) {
      return;
    }

    $password = $value->value ?? NULL;

    if (empty($password)) {
      return;
    }

    $user = $value->getEntity();

    if (!$user instanceof UserInterface) {
      return;
    }

    $violations = $this->passwordPolicyValidator->validatePassword($password, $user);

    if (!empty($violations)) {
      $this->context->addViolation($constraint->message);
    }
  }


}
