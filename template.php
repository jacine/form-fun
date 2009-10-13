<?php

/**
 * Implementation of hook_theme().
 */
function form_fun_theme() {
  return array(
    'render_attributes' => array(
      'arguments' => array('attributes'),
    ),
  );
}

/**
 * Custom function to render attributes courtesy of the Studio theme.
 */
function form_fun_render_attributes($attributes) {
  if ($attributes) {
    $items = array();
    foreach ($attributes as $attribute => $data) {
      if (is_array($data)) {
        $data = implode(' ', $data);
      }
      $items[] = $attribute .'="'. $data .'"';
    }
    $output = ' '. str_replace('_', '-', implode(' ', $items));
  }
  return $output;
}

/**
 * Overriding theme_form_element().
 */
function form_fun_form_element($element, $value) {
  // This is also used in the installer, pre-database setup.
  $t = get_t();
  $attributes = array();

  // Add drupal defaults
  if (!empty($element['#id'])) {
    $attributes['id'] = $element['#id'] .'-wrapper';
  }
  $attributes['class'][] = 'form-item';

  // Add a class indicating if a label isn't inside this element.
  // (Not sure if I'm keeping this or not)
  if (empty($element['#title'])) {
    $attributes['class'][] = 'form-item-no-label';
  }

  // Add a clearfix classes on most elements, might add some more here.
  $inline = array('radio', 'checkbox');
  if (!in_array($element['#type'], $inline)) {
    $attributes['class'][] = 'clearfix';
  }
  // Flatten the attributes for output.
  $attributes = theme('render_attributes', $attributes);

  // Form required marker
  $required = !empty($element['#required']) ? '<span class="form-required" title="'. $t('This field is required.') .'">*</span>' : '';

  // Label
  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $label = '<label for="'. $element['#id'] .'">'. $t('!title !required', array('!title' => filter_xss_admin($title), '!required' => $required)) .'</label>'."\n";
    }
    else {
      $label = '<label>'. $t('!title !required', array('!title' => filter_xss_admin($title), '!required' => $required)) .'</label>'."\n";
    }
  }

  // Description
  if (!empty($element['#description'])) {
    $description = '<div class="description">'. $element['#description'] .'</div>'."\n";
  }

  // Prepare the output
  $output = "\n".'<div'. $attributes .'>'."\n";
  // If there is no label, we can have less markup.  Wrapper level class can handle positioning.
  if (!$label) {
    $output .= '<div class="inner">'."\n". $value ."\n". $description .'</div>';
  }
  else {
    $output .= $label .'<div class="right">'."\n".'<div class="inner">'."\n". $value ."\n". $description .'</div>'."\n".'</div>'."\n";
  }
  $output .= '</div>'."\n";

  return $output;
}

/**
 * Overriding theme_fieldset().
 */
function form_fun_fieldset($element) {
  if (!empty($element['#collapsible'])) {
    drupal_add_js('misc/collapse.js');

    if (!isset($element['#attributes']['class'])) {
      $element['#attributes']['class'] = '';
    }

    $element['#attributes']['class'] .= ' collapsible';
    if (!empty($element['#collapsed'])) {
      $element['#attributes']['class'] .= ' collapsed';
    }
  }

  // Preparing the form description & value here, it's too cluttered in the return.
  $legend = $element['#title'] ? '<legend>'. $element['#title'] .'</legend>' : '';
  $description = isset($element['#description']) && $element['#description'] ? '<div class="description">'. $element['#description'] .'</div>'."\n" : '';
  $children = !empty($element['#children']) ? $element['#children'] ."\n" : '';
  $value = isset($element['#value']) ? $element['#value'] ."\n" : '';

  return '<fieldset'. drupal_attributes($element['#attributes']) .'>'."\n". $legend ."\n".'<div class="fieldset-inner">'."\n". $description ."\n". $children ."\n". $value ."\n".'</div>'."\n".'</fieldset>'."\n";

  return $output;
}