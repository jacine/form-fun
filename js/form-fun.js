/**
 * Find the first .form-item in each each form and fieldset,
 * then add a .form-item-first to it.
 */
$(document).ready(function() {
  $('form, form fieldset').each(function() {
    // Find the first
    $(this).find('div.form-item:first').addClass('form-item-first');
  });
});

