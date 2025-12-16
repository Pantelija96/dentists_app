$(document).ready(function() {
    // Initialize select2
    // $('#country, #language').select2({
    //     minimumResultsForSearch: Infinity,
    //     placeholder: "Select option",
    //     allowClear: true
    // });

    // When country changes
    $('#country').change(function() {
        let country = $(this).val();
        let languageSelect = $('#language');

        languageSelect.empty(); // remove old options

        if (country === 'Serbia') {
            languageSelect.append(new Option('English', 'en'));
            languageSelect.append(new Option('Serbian', 'sr', true, true));
        } else if (country === 'Croatia') {
            languageSelect.append(new Option('English', 'en'));
            languageSelect.append(new Option('Croatian', 'cr', true, true));
        } else {
            // default, only English
            languageSelect.append(new Option('English', 'en', true, true));
        }

        // trigger change for select2
        languageSelect.trigger('change');
    });

    $(".select-2").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Please select",
        allowClear: true
    });
});
