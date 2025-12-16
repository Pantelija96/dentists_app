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
        typeSelect: $('#type_of_work'),
        materialSelect: $('#material_of_work'),
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
    const toothOrder = toothElements.map(el => +el.dataset.key).sort((a,b)=>a-b);

    const TOOTH_LABEL_MAP = {
        32:"48",31:"47",30:"46",29:"45",28:"44",27:"43",26:"42",25:"41",
        24:"31",23:"32",22:"33",21:"34",20:"35",19:"36",18:"37",17:"38",
        16:"28",15:"27",14:"26",13:"25",12:"24",11:"23",10:"22",9:"21",
        8:"11",7:"12",6:"13",5:"14",4:"15",3:"16",2:"17",1:"18"
    };

    const COLORS = [
        "#FF6B6B","#4D96FF","#6BCB77","#FFD93D",
        "#FF8FAB","#845EC2","#00C9A7","#FF9671"
    ];

    const state = {
        selection: new Set(),
        lastKey: null,
        groups: [],
        toothToGroup: new Map()
    };

    let selectedGroupId = null; // for editing

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
            ${state.groups.map((g, i) => {
            const teethStr = g.teeth.map(t => TOOTH_LABEL_MAP[t]).sort().join(", ");
            const paramsHTML = g.parameters && Object.keys(g.parameters).length
                ? Object.entries(g.parameters).map(([id, val]) => {
                    const label = g.parameterLabels[id] || `Param ${id}`;
                    const type = g.parameterFieldTypes[id] || 'string';
                    const options = g.parameterOptions[id] || [];

                    let displayVal = val;
                    if(type === 'boolean') displayVal = val == 1 ? 'Yes' : 'No';
                    else if(type === 'select') displayVal = options.find(o=>o==val) || val;

                    return `<div data-param-id="${id}"><strong>${label}:</strong> ${displayVal}</div>`;
                }).join('')
                : "-";

            return `
                <tr>
                    <td>${i + 1}</td>
                    <td>${g.typeOfWork || "-"}</td>
                    <td>${g.material || "-"}</td>
                    <td>${teethStr}</td>
                    <td>${paramsHTML}</td>
                    <td>
                        <span style="width:14px;height:14px;display:inline-block;background:${g.color};border-radius:3px;"></span>
                        <button type="button" class="btn btn-sm btn-link edit-params" data-group-id="${g.id}">Edit</button>
                    </td>
                </tr>
            `;
        }).join('')}
        </tbody>
    </table>
    `;
    };

    /* ============================================================
 * INITIAL LOAD OF EXISTING GROUPS
 * ============================================================ */
    if (window.__WORK_DATA__) {
        state.groups = window.__WORK_DATA__.groups.map((g,i) => {
            g.teeth = g.teeth.map(t => +t);
            g.parameterLabels = g.parameterLabels || {};
            g.parameterFieldTypes = g.parameterFieldTypes || {};
            g.parameterOptions = g.parameterOptions || {};

            g.color = g.color || COLORS[i % COLORS.length];

            return g;
        });

        state.groups.forEach(g => g.teeth.forEach(t => state.toothToGroup.set(t, g.id)));

        updateTeethVisuals();
        renderGroupsPreview();
    }

    /* ============================================================
     * TOOTH CLICK
     * ============================================================ */
    toothElements.forEach(el=>{
        el.style.cursor="pointer";
        el.addEventListener("click",function(e){
            const key=+this.dataset.key;
            if(e.shiftKey && state.lastKey!==null){
                const [from,to] = [state.lastKey,key].sort((a,b)=>a-b);
                toothOrder.forEach(n=>{if(n>=from&&n<=to) state.selection.add(n)});
            }else{
                state.selection.has(key) ? state.selection.delete(key) : state.selection.add(key);
            }
            state.lastKey = key;
            updateTeethVisuals();
        });
    });

    /* ============================================================
     * CLEAR / APPLY GROUP
     * ============================================================ */
    DOM.applyBtn?.addEventListener("click", () => {
        if (!state.selection.size) { alert("Select at least one tooth."); return; }

        const typeId = DOM.typeSelect.val();
        const typeLabel = DOM.typeSelect.find('option:selected').text();
        const materialId = DOM.materialSelect.val();
        const materialLabel = DOM.materialSelect.find('option:selected').text();
        const color = COLORS[state.groups.length % COLORS.length];
        const groupId = crypto.randomUUID();
        const teeth = [...state.selection];
        const parameters = JSON.parse(DOM.payloadInput.val() || "{}");

        const parameterLabels = {};
        const parameterFieldTypes = {};
        const parameterOptions = {};

        Object.keys(parameters).forEach(id => {
            const input = document.getElementById(`param_${id}`);
            let type = 'string';
            if(input) {
                if(input.type === 'checkbox') type = 'boolean';
                else if(input.tagName === 'SELECT') type = 'select';
            }
            parameterLabels[id] = input?.closest('div')?.querySelector('label')?.innerText || `Param ${id}`;
            parameterFieldTypes[id] = type;
            parameterOptions[id] = [];
            if(type === 'select') {
                Array.from(input.options).forEach(opt => parameterOptions[id].push(opt.value));
            }
        });

        teeth.forEach(t => state.toothToGroup.set(t, groupId));

        state.groups.push({
            id: groupId,
            color,
            typeOfWorkId: typeId,
            typeOfWork: typeLabel,
            materialId,
            material: materialLabel,
            teeth,
            parameters,
            parameterLabels,
            parameterFieldTypes,
            parameterOptions
        });

        DOM.payloadInput.val("");
        resetSelection();
        updateTeethVisuals();
        renderGroupsPreview();
    });

    /* ============================================================
     * FETCH MATERIALS WHEN TYPE CHANGES
     * ============================================================ */
    DOM.materialSelect.prop('disabled',true);
    DOM.typeSelect.on('change', function(){
        const workTypeId = $(this).val();
        if(!workTypeId) return;

        DOM.materialSelect.prop('disabled',true).empty().append(new Option('Loading...','',true,false));

        fetch(`/work_type/materials/${workTypeId}`)
            .then(res=>res.json())
            .then(materials=>{
                DOM.materialSelect.empty().append(new Option('Select material','',true,false));
                materials.forEach(m=>DOM.materialSelect.append(new Option(m.name,m.id,false,false)));
                DOM.materialSelect.prop('disabled',false).trigger('change.select2');
            });
    });

    /* ============================================================
     * PARAMETERS MODAL
     * ============================================================ */
    DOM.addParamsBtn.on('click',()=>{
        const workTypeId = DOM.typeSelect.val();
        const materialId = DOM.materialSelect.val();

        if(!workTypeId||!materialId){ alert('Select type and material'); return;}

        fetch(`/work_type/${workTypeId}/material/${materialId}/parameters`)
            .then(res=>res.json())
            .then(parameters=>{
                renderParameters(parameters);

                // Prefill if editing a group
                if(selectedGroupId){
                    const group = state.groups.find(g=>g.id===selectedGroupId);
                    if(group?.parameters){
                        Object.entries(group.parameters).forEach(([id,val])=>{
                            const input=document.getElementById(`param_${id}`);
                            if(!input) return;
                            if(input.type==='checkbox') input.checked=val==1;
                            else input.value=val;
                        });
                    }
                }

                $('#parameters-modal select').select2({
                    minimumResultsForSearch: Infinity,
                    placeholder:"Please select",
                    allowClear:true
                });

                DOM.parametersModal.modal('show');
            });
    });

    function renderParameters(parameters){
        const container = DOM.parametersContainer;
        container.empty();
        if(!parameters.length){ container.html('<em>No parameters for this combination.</em>'); return; }
        parameters.forEach(p=>{
            let fieldHTML='';
            switch(p.field_type){
                case 'string':
                case 'text':
                    fieldHTML = `<div class="form-group mb-3">
                        <label for="param_${p.id}">${p.name}</label>
                        <input type="text" class="form-control" name="param[${p.id}]" id="param_${p.id}" placeholder="Enter value">
                    </div>`;
                    break;
                case 'number':
                    fieldHTML = `<div class="form-group mb-3">
                        <label for="param_${p.id}">${p.name}</label>
                        <input type="number" class="form-control" name="param[${p.id}]" id="param_${p.id}">
                    </div>`;
                    break;
                case 'boolean':
                    fieldHTML = `<div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="param[${p.id}]" id="param_${p.id}">
                        <label class="form-check-label" for="param_${p.id}">${p.name}</label>
                    </div>`;
                    break;
                case 'select':
                    const options = p.options ? JSON.parse(p.options).map(o=>`<option value="${o}">${o}</option>`).join('') : '';
                    fieldHTML = `<div class="form-group mb-3">
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

    DOM.saveParamsBtn.on('click', () => {
        const data = {};
        const labels = {};
        const types = {};
        const options = {};

        $('#parameters-container [name]').each(function() {
            const id = $(this).attr('name').match(/\d+/)[0];
            const input = this;
            let value;

            if(input.type === 'checkbox') value = input.checked ? 1 : 0;
            else value = input.value;

            data[id] = value;

            const label = $(input).closest('div').find('label').first().text() || `Param ${id}`;
            labels[id] = label;

            if(input.type === 'checkbox') types[id] = 'boolean';
            else if(input.tagName === 'SELECT') types[id] = 'select';
            else types[id] = 'string';

            if(types[id] === 'select') {
                options[id] = Array.from(input.options).map(o => o.value);
            } else {
                options[id] = [];
            }
        });

        DOM.payloadInput.val(JSON.stringify(data));

        if (selectedGroupId) {
            const group = state.groups.find(g => g.id === selectedGroupId);
            if (group) {
                group.parameters = data;
                group.parameterLabels = labels;
                group.parameterFieldTypes = types;
                group.parameterOptions = options;
            }
        }

        DOM.parametersModal.modal('hide');
        renderGroupsPreview();
        updateTeethVisuals();
        selectedGroupId = null;
    });

    /* ============================================================
     * EDIT EXISTING GROUP PARAMETERS
     * ============================================================ */
    DOM.groupsContainer.addEventListener('click', e => {
        const btn = e.target.closest('.edit-params');
        if (!btn) return;

        selectedGroupId = btn.dataset.groupId;
        const group = state.groups.find(g => g.id === selectedGroupId);
        if (!group) return;

        DOM.typeSelect.val(group.typeOfWorkId).trigger('change');

        const interval = setInterval(() => {
            if (!DOM.materialSelect.prop('disabled')) {
                DOM.materialSelect.val(group.materialId).trigger('change');
                clearInterval(interval);

                // Fetch parameters for this type+material
                fetch(`/work_type/${group.typeOfWorkId}/material/${group.materialId}/parameters`)
                    .then(res => res.json())
                    .then(parameters => {
                        renderParameters(parameters);
                        if (group.parameters) {
                            Object.entries(group.parameters).forEach(([id, val]) => {
                                const input = document.getElementById(`param_${id}`);
                                if (!input) return;
                                if (input.type === 'checkbox') input.checked = val == 1;
                                else input.value = val;
                            });
                        }
                        $('#parameters-modal select').select2({ minimumResultsForSearch: Infinity, placeholder:"Please select", allowClear:true });
                        DOM.parametersModal.modal('show');
                    });
            }
        }, 50);
    });



    /* ============================================================
     * FORM SUBMIT
     * ============================================================ */
    DOM.form.addEventListener('submit',()=>{
        document.getElementById('groups_payload').value = JSON.stringify(state.groups);
        document.getElementById('parameters_payload').value = JSON.stringify(window.selectedParameters||{});
    });


    /* ============================================================
 * UPLOADS — EDIT PAGE (MATCHES EXISTING HTML)
 * ============================================================ */

    const uploadsContainer = document.getElementById('uploads-container');
    const addFileBtn = document.getElementById('add-file');
    const deletedUploadsInput = document.getElementById('deleted_uploads');
    const template = document.getElementById('upload-template');

    let deletedUploads = [];
    let uploadIndex = uploadsContainer.children.length + 1;

    /* ---------------------------
     * ADD NEW FILE
     * --------------------------- */
    addFileBtn.addEventListener('click', e => {
        e.preventDefault();

        const fragment = template.content.cloneNode(true);
        const card = fragment.querySelector('.upload-card');

        card.innerHTML = card.innerHTML.replace('__INDEX__', uploadIndex);
        uploadIndex++;

        uploadsContainer.appendChild(card);
    });

    /* ---------------------------
     * CLICK HANDLER
     * --------------------------- */
    uploadsContainer.addEventListener('click', e => {

        // Upload click
        const uploadBtn = e.target.closest('.btn-upload');
        if (uploadBtn && !uploadBtn.classList.contains('disabled')) {
            uploadBtn.closest('.upload-card')
                .querySelector('input[type="file"]')
                .click();
            return;
        }

        // Remove upload
        const removeBtn = e.target.closest('.btn-remove-upload');
        if (removeBtn) {
            const card = removeBtn.closest('.upload-card');

            if (card.dataset.existing === "1") {
                deletedUploads.push(card.dataset.uploadId);
                deletedUploadsInput.value = JSON.stringify(deletedUploads);
            }

            card.remove();
        }
    });

    /* ---------------------------
     * FILE SELECTED
     * --------------------------- */
    uploadsContainer.addEventListener('change', e => {
        if (!e.target.classList.contains('upload-one')) return;

        const file = e.target.files[0];
        const list = e.target.closest('.upload-card')
            .querySelector('.dm-upload__file ul');

        list.innerHTML = '';

        if (file) {
            list.innerHTML = `<li>${file.name}</li>`;
        }
    });


});
