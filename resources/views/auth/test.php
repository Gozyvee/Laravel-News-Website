jQuery(document).ready(function ($) {
    // ... existing variables ...
     // This is the new variable for the language dropdown

    // ... existing functions and event handlers ...

    // Update existing AJAX calls to include the selected language
    wpaicg_template_generate_titles.click(function (){
        // ... existing code ...

        // Get the selected language value
        let language = wpaicg_language.val(); // Get the language from the dropdown

        // Include the language in the AJAX data
        let data = wpaicg_custom_template_form.serialize() + '&language=' + language;
        data += '&action=wpaicg_template_generator&step=titles&topic=' + topic;

        // ... existing AJAX setup ...
    });

    // Update other AJAX calls similarly by adding the '&language=' + language to the data string

    // Event handler for language selection change
    wpaicg_language.change(function () {
        // Enable or disable buttons based on whether a language is selected
        let languageSelected = $(this).val() !== '';
        wpaicg_template_generate_titles.prop('disabled', !languageSelected);
        wpaicg_template_generate_sections.prop('disabled', !languageSelected);
        wpaicg_template_generate_content.prop('disabled', !languageSelected);
        wpaicg_template_generate_excerpt.prop('disabled', !languageSelected);
        wpaicg_template_generate_meta.prop('disabled', !languageSelected);
    });

    // Call the change handler on page load to set the initial state of the buttons
    wpaicg_language.change();

    // ... other existing code ...
});
