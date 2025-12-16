
document.addEventListener('DOMContentLoaded', function () {
    var select = document.getElementById('exampleFormControlSelect1');
    var firmGroup = document.getElementById('firmNameDiv');

    if (!select || !firmGroup) {
        // console.warn('Nisu pronađeni potrebni elementi: select ili firmNameDiv');
        return;
    }

    function toggleFirmField() {
        // Prikaz trenutnog stanja u konzoli
        // console.log('Tip profila:', select.value);
        if (select.value === 'person') {
            firmGroup.style.display = 'none';
            // console.log('Div za firmu je sakriven');
        } else {
            firmGroup.style.display = 'block';
            // console.log('Div za firmu je prikazan');
        }
    }

    // Native event
    select.addEventListener('change', toggleFirmField);

    // Select2 event
    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
        window.jQuery(select).on('select2:select', toggleFirmField);
    }

    // Prvi poziv da se postavi ispravno stanje na ucitavanju stranice
    toggleFirmField();

    $(".select-2").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Please select",
        allowClear: true
    });
});

