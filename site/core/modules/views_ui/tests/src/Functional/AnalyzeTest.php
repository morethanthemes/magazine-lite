<?php

declare(strict_types=1);

namespace Drupal\Tests\views_ui\Functional;

/**
 * Tests the views analyze system.
 *
 * @group views_ui
 */
class AnalyzeTest extends UITestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['views_ui'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_view'];

  /**
   * Tests that analyze works in general.
   */
  public function testAnalyzeBasic(): void {
    $this->drupalLogin($this->adminUser);

    $this->drupalGet('admin/structure/views/view/test_view/edit');
    $this->assertSession()->linkExists('Analyze view');

    // This redirects the user to the analyze form.
    $this->clickLink('Analyze view');
    $this->assertSession()->titleEquals('View analysis | Drupal');

    foreach (['ok', 'warning', 'error'] as $type) {
      // Check that analyze messages with the expected type found.
      $this->assertSession()->elementExists('css', 'div.' . $type);
    }

    // This redirects the user back to the main views edit page.
    $this->submitForm([], 'Ok');
  }

}
