document.addEventListener("DOMContentLoaded", () => {
    $(".select-2").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Please Select",
        allowClear: true,
    });

    /* ============================================================
     * DOM CACHE
     * ============================================================ */

    const DOM = {
        checkboxSkip: document.getElementById("skipPart"),
        typeSelect: document.getElementById("type_of_work"),
        materialSelect: document.getElementById("material_of_work"),
        applyBtn: document.getElementById("applyToothGroup"),
        clearBtn: document.getElementById("clearSelection"),
        groupsContainer: document.getElementById("toothGroupsContainer"),
        form: document.querySelector(".work-page form"),
        svg: document.querySelector(".tooth-chart svg")
    };

    if (!DOM.svg || !DOM.form) return;

    const toothElements = Array.from(
        DOM.svg.querySelectorAll("#Spots [id^='Tooth']")
    );

    /* ============================================================
     * CONSTANTS
     * ============================================================ */

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

    /* ============================================================
     * STATE
     * ============================================================ */

    const state = {
        selection: new Set(),
        lastKey: null,
        groups: [],
        toothToGroup: new Map()
    };

    const toothOrder = toothElements
        .map(el => +el.dataset.key)
        .sort((a, b) => a - b);

    /* ============================================================
     * HELPERS
     * ============================================================ */

    const getSelectedOption = select => ({
        id: select.value,
        label: select.options[select.selectedIndex]?.text || null
    });

    const resetSelection = () => {
        state.selection.clear();
        state.lastKey = null;
    };

    /* ============================================================
     * UI RENDERING
     * ============================================================ */

    function updateTeethVisuals() {
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
    }

    function renderGroupsPreview() {
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    ${state.groups.map((g, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${g.typeOfWork || "-"}</td>
                            <td>${g.material || "-"}</td>
                            <td>${g.teeth
            .map(t => TOOTH_LABEL_MAP[t])
            .sort()
            .join(", ")}</td>
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
    }

    /* ============================================================
     * TOOTH SELECTION
     * ============================================================ */

    function handleToothClick(e) {
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
    }

    toothElements.forEach(el => {
        el.style.cursor = "pointer";
        el.addEventListener("click", handleToothClick);
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

        const type = getSelectedOption(DOM.typeSelect);
        const material = getSelectedOption(DOM.materialSelect);
        const color = COLORS[state.groups.length % COLORS.length];
        const groupId = crypto.randomUUID();

        const teeth = [...state.selection];

        teeth.forEach(t => state.toothToGroup.set(t, groupId));

        state.groups.push({
            id: groupId,
            color,
            typeOfWorkId: type.id,
            typeOfWork: type.label,
            materialId: material.id,
            material: material.label,
            teeth
        });

        resetSelection();
        updateTeethVisuals();
        renderGroupsPreview();
    });

    /* ============================================================
     * MATERIAL FETCH
     * ============================================================ */

    $('#type_of_work').on('change', function () {
        if (!this.value) return;

        fetch(`/work_type/materials/${this.value}`)
            .then(res => res.json())
            .then(materials => {
                const materialSelect = document.getElementById('material_of_work');

                $(materialSelect).empty().append(
                    new Option('Select material', '', false, false)
                );

                materials.forEach(m => {
                    const option = new Option(m.name, m.id, false, false);
                    $(materialSelect).append(option);
                });

                $(materialSelect).trigger('change');
            });
    });

    /* ============================================================
     * FORM SUBMIT
     * ============================================================ */

    DOM.form.addEventListener("submit", e => {
        e.preventDefault();

        fetch("/api/work", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nameOfWork: DOM.form.querySelector("input[type=text]").value,
                skipPart: DOM.checkboxSkip.checked,
                groups: state.groups
            })
        })
            .then(r => r.json())
            .then(() => alert("Work saved successfully"))
            .catch(() => alert("Error saving work"));
    });

});
