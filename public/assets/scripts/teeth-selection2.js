document.addEventListener("DOMContentLoaded", () => {

    /* ============================================================
     * SELECT2 INIT
     * ============================================================ */
    $(".select-2").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Please select",
        allowClear: true
    });

    /* ============================================================
     * DOM CACHE
     * ============================================================ */
    const DOM = {
        typeSelect: $('#type_of_work'),           // jQuery
        materialSelect: $('#material_of_work'),   // jQuery
        addParamsBtn: $('#add_parameters_button'),
        parametersModal: $('#parameters-modal'),
        parametersContainer: $('#parameters-container'),
        saveParamsBtn: $('#save-parameters'),
        payloadInput: $('#payload'),
        checkboxSkip: document.getElementById("skipPart"),
        applyBtn: document.getElementById("applyToothGroup"),
        clearBtn: document.getElementById("clearSelection"),
        groupsContainer: document.getElementById("toothGroupsContainer"),
        form: document.querySelector(".work-page form"),
        svg: document.querySelector(".tooth-chart svg")
    };

    if (!DOM.svg || !DOM.form) return;

    /* ============================================================
     * TOOTH SELECTION STATE
     * ============================================================ */
    const toothElements = Array.from(DOM.svg.querySelectorAll("#Spots [id^='Tooth']"));
    const toothOrder = toothElements.map(el => +el.dataset.key).sort((a, b) => a - b);

    const TOOTH_LABEL_MAP = {
        32:"48",31:"47",30:"46",29:"45",28:"44",27:"43",26:"42",25:"41",
        24:"31",23:"32",22:"33",21:"34",20:"35",19:"36",18:"37",17:"38",
        16:"28",15:"27",14:"26",13:"25",12:"24",11:"23",10:"22",9:"21",
        8:"11",7:"12",6:"13",5:"14",4:"15",3:"16",2:"17",1:"18"
    };

    const COLORS = [
        "#FF6B6B", "#4D96FF", "#6BCB77", "#FFD93D",
        "#FF8FAB", "#845EC2", "#00C9A7", "#FF9671"
    ];

    const state = {
        selection: new Set(),
        lastKey: null,
        groups: [],
        toothToGroup: new Map()
    };

    const resetSelection = () => {
        state.selection.clear();
        state.lastKey = null;
    };

    const updateTeethVisuals = () => {
        toothElements.forEach(el => {
            const key = +el.dataset.key;
            const groupId = state.toothToGroup.get(key);

            el.style.fill = groupId
                ? state.groups.find(g => g.id === groupId)?.color
                : "#FFFFFF";

            if (state.selection.has(key)) {
                el.style.fill = "#B0B0B0";
                el.style.stroke = "#000";
                el.style.strokeWidth = "2px";
            } else {
                el.style.stroke = "none";
            }
        });
    };

    const renderGroupsPreview = () => {
        if (!DOM.groupsContainer) return;

        if (!state.groups.length) {
            DOM.groupsContainer.innerHTML = "<em>No groups yet.</em>";
            return;
        }

        DOM.groupsContainer.innerHTML = `
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Material</th>
                    <th>Teeth</th>
                    <th>Parameters</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                ${state.groups.map((g, i) => `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${g.typeOfWork || "-"}</td>
                        <td>${g.material || "-"}</td>
                        <td>${g.teeth.map(t => TOOTH_LABEL_MAP[t]).sort().join(", ")}</td>
                        <td>
                            ${g.parameters ? Object.entries(g.parameters).map(([id, value]) => {
            // Get parameter label from modal
            const labelEl = $(`#parameters-container #param_${id}`).prev('label');
            const label = labelEl.length ? labelEl.text() : `Param ${id}`;

            // Determine field type to display nicely
            const inputEl = $(`#parameters-container #param_${id}`);
            let displayValue = value;

            if (inputEl.attr('type') === 'checkbox') {
                displayValue = value == 1 ? 'Yes' : 'No';
            } else if (inputEl.is('select')) {
                displayValue = inputEl.find(`option[value="${value}"]`).text() || value;
            }

            return `<div data-param-id="${id}"><strong>${label}:</strong> ${displayValue}</div>`;
        }).join('') : "-"}
                        </td>
                        <td>
                            <span style="
                                width:14px;
                                height:14px;
                                display:inline-block;
                                background:${g.color};
                                border-radius:3px;
                            "></span>
                        </td>
                    </tr>
                `).join("")}
            </tbody>
        </table>
    `;
    };

    /* ============================================================
     * TOOTH CLICK
     * ============================================================ */
    toothElements.forEach(el => {
        el.style.cursor = "pointer";
        el.addEventListener("click", function(e) {
            const key = +this.dataset.key;

            if (e.shiftKey && state.lastKey !== null) {
                const [from, to] = [state.lastKey, key].sort((a,b)=>a-b);
                toothOrder.forEach(n => {
                    if (n >= from && n <= to) state.selection.add(n);
                });
            } else {
                state.selection.has(key)
                    ? state.selection.delete(key)
                    : state.selection.add(key);
            }

            state.lastKey = key;
            updateTeethVisuals();
        });
    });

    /* ============================================================
     * GROUP APPLY / CLEAR
     * ============================================================ */
    DOM.clearBtn?.addEventListener("click", () => {
        state.groups = [];
        state.toothToGroup.clear();
        resetSelection();
        updateTeethVisuals();
        renderGroupsPreview();
    });

    DOM.applyBtn?.addEventListener("click", () => {
        if (!state.selection.size) {
            alert("Select at least one tooth.");
            return;
        }

        const typeId = DOM.typeSelect.val();
        const typeLabel = DOM.typeSelect.find('option:selected').text();
        const materialId = DOM.materialSelect.val();
        const materialLabel = DOM.materialSelect.find('option:selected').text();
        const color = COLORS[state.groups.length % COLORS.length];
        const groupId = crypto.randomUUID();
        const teeth = [...state.selection];

        // Parameters for this group, temporarily empty
        const parameters = JSON.parse(DOM.payloadInput.val() || "{}");

        teeth.forEach(t => state.toothToGroup.set(t, groupId));

        state.groups.push({
            id: groupId,
            color,
            typeOfWorkId: typeId,
            typeOfWork: typeLabel,
            materialId: materialId,
            material: materialLabel,
            teeth,
            parameters
        });

        DOM.payloadInput.val(""); // clear payload for next group
        resetSelection();
        updateTeethVisuals();
        renderGroupsPreview();
    });

    /* ============================================================
     * FETCH MATERIALS WHEN WORK TYPE CHANGES
     * ============================================================ */
    DOM.materialSelect.prop('disabled', true);
    DOM.typeSelect.on('change', function() {
        const workTypeId = $(this).val();
        if (!workTypeId) return;

        fetch(`/work_type/materials/${workTypeId}`)
            .then(res => res.json())
            .then(materials => {
                DOM.materialSelect.empty().append(new Option('Select material', '', true, false)).prop('disabled', false);
                materials.forEach(m => DOM.materialSelect.append(new Option(m.name, m.id, false, false)));
                DOM.materialSelect.trigger('change.select2');
            });
    });

    /* ============================================================
     * PARAMETERS MODAL
     * ============================================================ */
    DOM.addParamsBtn.on('click', function() {
        const workTypeId = DOM.typeSelect.val();
        const materialId = DOM.materialSelect.val();

        if (!workTypeId || !materialId) {
            alert('Please select work type and material first.');
            return;
        }

        fetch(`/work_type/${workTypeId}/material/${materialId}/parameters`)
            .then(res => res.json())
            .then(parameters => {
                renderParameters(parameters);
                $('#parameters-modal select').select2({
                    minimumResultsForSearch: Infinity,
                    placeholder: "Please select",
                    allowClear: true
                });
                DOM.parametersModal.modal('show');
            });
    });

    function renderParameters(parameters) {
        const container = DOM.parametersContainer;
        container.empty();

        if (!parameters.length) {
            container.html('<em>No parameters for this combination.</em>');
            return;
        }

        parameters.forEach(p => {
            let fieldHTML = '';
            switch (p.field_type) {
                case 'string':
                case 'text':
                    fieldHTML = `
                        <div class="form-group mb-3">
                            <label for="param_${p.id}">${p.name}</label>
                            <input type="text" class="form-control" name="param[${p.id}]" id="param_${p.id}" placeholder="Enter value">
                        </div>`;
                    break;
                case 'number':
                    fieldHTML = `
                        <div class="form-group mb-3">
                            <label for="param_${p.id}">${p.name}</label>
                            <input type="number" class="form-control" name="param[${p.id}]" id="param_${p.id}">
                        </div>`;
                    break;
                case 'boolean':
                    fieldHTML = `
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="param[${p.id}]" id="param_${p.id}">
                            <label class="form-check-label" for="param_${p.id}">${p.name}</label>
                        </div>`;
                    break;
                case 'select':
                    const options = p.options ? JSON.parse(p.options).map(o => `<option value="${o}">${o}</option>`).join('') : '';
                    fieldHTML = `
                        <div class="form-group mb-3">
                            <label for="param_${p.id}">${p.name}</label>
                            <select class="form-control" name="param[${p.id}]" id="param_${p.id}">
                                <option value="">Select</option>
                                ${options}
                            </select>
                        </div>`;
                    break;
            }
            container.append(fieldHTML);
        });
    }

    DOM.saveParamsBtn.on('click', function() {
        const data = {};
        $('#parameters-container [name]').each(function() {
            const name = $(this).attr('name').match(/\d+/)[0];
            let value = $(this).attr('type') === 'checkbox' ? ($(this).is(':checked') ? 1 : 0) : $(this).val();
            data[name] = value;
        });

        DOM.payloadInput.val(JSON.stringify(data));
        DOM.parametersModal.modal('hide');
    });

    const container = document.getElementById('uploads-container');
    const addBtn = document.getElementById('add-file');

    let index = 1;

    // Open file dialog
    container.addEventListener('click', e => {
        const uploadBtn = e.target.closest('.btn-upload');
        if (!uploadBtn) return;

        uploadBtn
            .closest('.upload-card')
            .querySelector('input[type="file"]')
            .click();
    });

    // File selected
    container.addEventListener('change', e => {
        if (!e.target.classList.contains('upload-one')) return;

        const file = e.target.files[0];
        const list = e.target
            .closest('.upload-card')
            .querySelector('.file-list');

        list.innerHTML = '';

        if (!file) return;

        list.innerHTML = `
            <li class="d-flex align-items-center justify-content-between">
                <span>${file.name}</span>
                <button type="button" class="btn remove-file">
                    ✕
                </button>
            </li>
        `;
    });

    // Remove file
    container.addEventListener('click', e => {
        if (!e.target.classList.contains('remove-file')) return;

        const card = e.target.closest('.upload-card');
        card.querySelector('input[type="file"]').value = '';
        card.querySelector('.file-list').innerHTML = '';
    });

    // Add new upload card
    addBtn.addEventListener('click', e => {
        e.preventDefault();

        index++;

        const last = container.querySelector('.upload-card:last-child');
        const clone = last.cloneNode(true);

        clone.dataset.index = index;
        clone.querySelector('h6').textContent = `Upload file #${index}`;

        const input = clone.querySelector('input[type="file"]');
        input.id = `upload-${index}`;
        input.value = '';

        clone.querySelector('.file-list').innerHTML = '';

        container.appendChild(clone);
    });

    /* ============================================================
     * FORM SUBMIT
     * ============================================================ */
    // DOM.form.addEventListener("submit", e => {
    //     e.preventDefault();
    //
    //     fetch("/api/work", {
    //         method: "POST",
    //         headers: { "Content-Type": "application/json" },
    //         body: JSON.stringify({
    //             nameOfWork: DOM.form.querySelector("input[type=text]").value,
    //             skipPart: DOM.checkboxSkip.checked,
    //             groups: state.groups
    //         })
    //     })
    //         .then(r => r.json())
    //         .then(() => alert("Work saved successfully"))
    //         .catch(() => alert("Error saving work"));
    // });

    const form = document.querySelector('.work-page form');

    form.addEventListener('submit', function () {

        // Groups (teeth + work type + material + params)
        document.getElementById('groups_payload').value =
            JSON.stringify(state.groups);

        // Parameters (modal collected data)
        document.getElementById('parameters_payload').value =
            JSON.stringify(window.selectedParameters || {});

    });

});
