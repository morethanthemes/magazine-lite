<?php

declare(strict_types=1);

namespace Drupal\Tests\system\Functional\Entity;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests that operations can be injected from the hook.
 *
 * @group Entity
 */
class EntityOperationsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['entity_test'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and log in user.
    $this->drupalLogin($this->drupalCreateUser(['administer permissions']));
  }

  /**
   * Checks that hook_entity_operation_alter() can add an operation.
   *
   * @see entity_test_entity_operation_alter()
   */
  public function testEntityOperationAlter(): void {
    // Check that role listing contain our test_operation operation.
    $this->drupalGet('admin/people/roles');
    $roles = Role::loadMultiple();
    foreach ($roles as $role) {
      $this->assertSession()->linkByHrefExists($role->toUrl()->toString() . '/test_operation');
      $this->assertSession()->linkExists(new FormattableMarkup('Test Operation: @label', ['@label' => $role->label()]));
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function createRole(array $permissions, $rid = NULL, $name = NULL, $weight = NULL) {
    // The parent method uses random strings by default, which may include HTML
    // entities for the entity label. Since in this test the entity label is
    // used to generate a link, and AssertContentTrait::assertLink() is not
    // designed to deal with links potentially containing HTML entities this
    // causes random failures. Use a random HTML safe string instead.
    $name = $name ?: $this->randomMachineName();
    return parent::createRole($permissions, $rid, $name, $weight);
  }

}
