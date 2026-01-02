document.addEventListener("DOMContentLoaded", () => {

    /* ============================================================
     * DOM CACHE
     * ============================================================ */
    const DOM = {
        typeRadios: document.querySelectorAll('input[name="work_type_radio"]'),
        materialRadiosContainer: document.getElementById("material_radios_container"),
        materialSelectHidden: $('#material_of_work'),
        typeSelectHidden: $('#type_of_work'),
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
        svg: document.querySelector(".tooth-chart svg"),
        uploadsContainer: document.getElementById('uploads-container'),
        addFileBtn: document.getElementById('add-file')
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
            const labelEl = $(`#parameters-container #param_${id}`).prev('label');
            const label = labelEl.length ? labelEl.text() : `Param ${id}`;
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

        const typeRadio = document.querySelector('input[name="work_type_radio"]:checked');
        const materialRadio = document.querySelector('input[name="material_of_work_radio"]:checked');

        if (!typeRadio || !materialRadio) {
            alert("Please select work type and material.");
            return;
        }

        const typeId = typeRadio.dataset.id;
        const typeLabel = typeRadio.dataset.label;
        const materialId = materialRadio.value;
        const materialLabel = materialRadio.dataset.label;

        // update hidden selects
        DOM.typeSelectHidden.val(typeId).trigger('change');
        DOM.materialSelectHidden.val(materialId).trigger('change');

        const color = COLORS[state.groups.length % COLORS.length];
        const groupId = crypto.randomUUID();
        const teeth = [...state.selection];

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

        DOM.payloadInput.val(""); // clear payload
        resetSelection();
        updateTeethVisuals();
        renderGroupsPreview();
    });

    /* ============================================================
     * FETCH MATERIALS WHEN WORK TYPE RADIO CHANGES
     * ============================================================ */
    DOM.typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const workTypeId = this.dataset.id;
            if (!workTypeId) return;

            // update hidden select
            DOM.typeSelectHidden.val(workTypeId).trigger('change');

            fetch(`/work_type/materials/${workTypeId}`)
                .then(res => res.json())
                .then(materials => {
                    DOM.materialRadiosContainer.innerHTML = '';
                    if (!materials.length) {
                        DOM.materialRadiosContainer.innerHTML = '<em class="text-muted">No materials for this type</em>';
                        return;
                    }
                    materials.forEach(m => {
                        const radioHTML = `
                                <input type="radio" class="btn-check" name="material_of_work_radio" value="${m.id}" data-label="${m.translated_name}" id="material_radio_${m.id}" autocomplete="off">
                                <label class="type-of-material-btn btn-outline-primary" for="material_radio_${m.id}">${m.translated_name}</label>
                        `;
                        DOM.materialRadiosContainer.insertAdjacentHTML('beforeend', radioHTML);
                    });
                });
        });
    });

    /* ============================================================
     * PARAMETERS MODAL
     * ============================================================ */
    DOM.addParamsBtn.on('click', function() {
        const typeRadio = document.querySelector('input[name="work_type_radio"]:checked');
        const materialRadio = document.querySelector('input[name="material_of_work_radio"]:checked');

        if (!typeRadio || !materialRadio) {
            alert('Please select work type and material first.');
            return;
        }

        const workTypeId = typeRadio.dataset.id;
        const materialId = materialRadio.value;

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


    /* ============================================================
     * FILE UPLOADS
     * ============================================================ */
    let index = DOM.uploadsContainer.querySelectorAll('.upload-card').length;

    // Open file dialog
    DOM.uploadsContainer.addEventListener('click', e => {
        const uploadBtn = e.target.closest('.btn-upload');
        if (!uploadBtn) return;
        uploadBtn.closest('.upload-card').querySelector('input[type="file"]').click();
    });

    // File selected
    DOM.uploadsContainer.addEventListener('change', e => {
        if (!e.target.classList.contains('upload-one')) return;
        const file = e.target.files[0];
        const list = e.target.closest('.upload-card').querySelector('.file-list');
        list.innerHTML = '';
        if (!file) return;
        list.innerHTML = `
            <li class="d-flex align-items-center justify-content-between">
                <span>${file.name}</span>
                <button type="button" class="btn remove-file">✕</button>
            </li>
        `;
    });

    // Remove file
    DOM.uploadsContainer.addEventListener('click', e => {
        if (!e.target.classList.contains('remove-file')) return;
        const card = e.target.closest('.upload-card');
        card.querySelector('input[type="file"]').value = '';
        card.querySelector('.file-list').innerHTML = '';
    });

    // Add new upload card
    DOM.addFileBtn.addEventListener('click', e => {
        e.preventDefault();
        index++;
        const last = DOM.uploadsContainer.querySelector('.upload-card:last-child');
        const clone = last.cloneNode(true);
        clone.dataset.index = index;
        clone.querySelector('h6').textContent = `Upload file #${index}`;
        const input = clone.querySelector('input[type="file"]');
        input.id = `upload-${index}`;
        input.value = '';
        clone.querySelector('.file-list').innerHTML = '';
        DOM.uploadsContainer.appendChild(clone);
    });

    /* ============================================================
     * FORM SUBMIT
     * ============================================================ */
    DOM.form.addEventListener('submit', function () {
        document.getElementById('groups_payload').value = JSON.stringify(state.groups);
        document.getElementById('parameters_payload').value = JSON.stringify(window.selectedParameters || {});
    });

    $(".select-2").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Please select",
        allowClear: true
    });

});
