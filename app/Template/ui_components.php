<?php
// Simple UI component helpers to mimic shadcn Input/Label behavior for PHP views
// Functions return HTML strings with Neobrutalism styling

function ui_label($for, $text, $required = false) {
    $requiredSpan = $required ? '<span class="required">*</span>' : '';
    return '<label for="' . htmlspecialchars($for) . '" class="form-label">' . htmlspecialchars($text) . $requiredSpan . '</label>';
}

function ui_input($id, $type = 'text', $value = '', $attrs = '', $placeholder = '') {
    $idEsc = htmlspecialchars($id);
    $typeEsc = htmlspecialchars($type);
    $valueAttr = $type !== 'file' ? ' value="' . htmlspecialchars($value) . '"' : '';
    $placeholderAttr = $placeholder ? ' placeholder="' . htmlspecialchars($placeholder) . '"' : '';
    return '<input id="' . $idEsc . '" name="' . $idEsc . '" type="' . $typeEsc . '" class="form-control ui-input"' . $valueAttr . $placeholderAttr . ' ' . $attrs . ' />';
}

function ui_textarea($id, $value = '', $attrs = '', $placeholder = '') {
    $idEsc = htmlspecialchars($id);
    $placeholderAttr = $placeholder ? ' placeholder="' . htmlspecialchars($placeholder) . '"' : '';
    return '<textarea id="' . $idEsc . '" name="' . $idEsc . '" class="form-control ui-textarea" ' . $placeholderAttr . ' ' . $attrs . '>' . htmlspecialchars($value) . '</textarea>';
}

function ui_checkbox($id, $checked = false, $attrs = '', $label = '') {
    $idEsc = htmlspecialchars($id);
    $checkedAttr = $checked ? ' checked' : '';
    $labelHtml = $label ? '<label for="' . $idEsc . '" style="display:inline-flex;align-items:center;gap:0.5rem;cursor:pointer;"><input id="' . $idEsc . '" name="' . $idEsc . '" type="checkbox" class="form-check-input ui-checkbox" value="1"' . $checkedAttr . ' ' . $attrs . ' /><span>' . htmlspecialchars($label) . '</span></label>' : '<input id="' . $idEsc . '" name="' . $idEsc . '" type="checkbox" class="form-check-input ui-checkbox" value="1"' . $checkedAttr . ' ' . $attrs . ' />';
    return $labelHtml;
}

function ui_select($id, $options = [], $selected = '', $attrs = '') {
    $idEsc = htmlspecialchars($id);
    $html = '<select id="' . $idEsc . '" name="' . $idEsc . '" class="form-control ui-select" ' . $attrs . '>';
    foreach ($options as $val => $label) {
        $selectedAttr = ((string)$val === (string)$selected) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($val) . '"' . $selectedAttr . '>' . htmlspecialchars($label) . '</option>';
    }
    $html .= '</select>';
    return $html;
}

function ui_form_group($label_text, $input_html, $required = false, $hint = '') {
    $label = ui_label('temp', $label_text, $required);
    $hint_html = $hint ? '<div class="form-text">' . htmlspecialchars($hint) . '</div>' : '';
    return '<div class="form-group">' . $label . $input_html . $hint_html . '</div>';
}

?>