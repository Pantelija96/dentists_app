document.addEventListener("DOMContentLoaded", function () {
      const checkbox = document.getElementById("skipPart");

      // disablovanje form polja kada je cekirano "skip this part"
      const dropdowns = [
         document.getElementById("exampleFormControlSelect1"),
         document.getElementById("countryOption"),
         document.getElementById("applyToothGroup")
      ];

      checkbox.addEventListener("change", function () {
         dropdowns.forEach(drop => {
               if (this.checked) {
                  drop.setAttribute("disabled", "disabled", "disabled");   // disable
                  drop.style.opacity = "0.5";                  
               } else {
                  drop.removeAttribute("disabled");            // enable
                  drop.style.opacity = "1";
               }
         });
      });
    });

   // LOGIKA ZA IZBOR ZUBA I GRUPE ZUBA

   const svg = document.querySelector(".tooth-chart svg");
   const toothElements = Array.from(svg.querySelectorAll("#Spots [id^='Tooth']"));
   const groupsContainer = document.getElementById("toothGroupsContainer");

   // mapiranje između brojeva zuba (1–32) i njihovih oznaka (11–48)
   const toothLabelMap = {
      32: "48",
      31: "47",
      30: "46",
      29: "45",
      28: "44",
      27: "43",
      26: "42",
      25: "41",
      24: "31",
      23: "32",
      22: "33",
      21: "34",
      20: "35",
      19: "36",
      18: "37",
      17: "38",
      16: "28",
      15: "27",
      14: "26",
      13: "25",
      12: "24",
      11: "23",
      10: "22",
      9:  "21",
      8:  "11",
      7:  "12",
      6:  "13",
      5:  "14",
      4:  "15",
      3:  "16",
      2:  "17",
      1:  "18",
   };

   // sortiramo zube po broju (1–32) da bi SHIFT radio lepo
   const toothOrder = toothElements
      .map(el => parseInt(el.dataset.key, 10))
      .sort((a, b) => a - b);

   const state = {
      selection: new Set(),          // trenutno selektovani zubi
      lastKey: null,                 // poslednji kliknuti zub (za SHIFT)
      groups: [],                    // {id, color, typeOfWorkId, typeOfWork, materialId, material, teeth: [], teethLabels: []}
      toothToGroup: new Map(),       // toothNumber -> groupId
      colorPalette: [
         "#FF6B6B", "#4D96FF", "#6BCB77", "#FFD93D",
         "#FF8FAB", "#845EC2", "#00C9A7", "#FF9671"
      ]
   };

   function renderGroupsPreview() {
   if (!groupsContainer) return;

   if (state.groups.length === 0) {
      groupsContainer.innerHTML = "<em>No groups yet.</em>";
      return;
   }

   // pravimo tabelu
   let html = `
      <table class="table table-sm mb-0">
         <thead>
            <tr>
               <th>#</th>
               <th>Type of work</th>
               <th>Material</th>
               <th>Teeth</th>
               <th>Color</th>
            </tr>
         </thead>
         <tbody>
   `;

   state.groups.forEach((g, idx) => {
      const teethLabelSorted = [...g.teeth]
         .map(t => toothLabelMap[t] ?? t)   // mapiramo na 48,47,... ili fallback
         .sort((a, b) => a.localeCompare(b));

      html += `
         <tr>
            <td>${idx + 1}</td>
            <td>${g.typeOfWork || "-"}</td>
            <td>${g.material || "-"}</td>
            <td>${teethLabelSorted.join(", ")}</td>
            <td>
               <span style="
                  display:inline-block;
                  width:14px;
                  height:14px;
                  border-radius:3px;
                  background:${g.color};
                  border:1px solid #ccc;
               "></span>
            </td>
         </tr>
      `;
   });

   html += `</tbody></table>`;
   groupsContainer.innerHTML = html;
   }

   function updateTeethVisuals() {
      toothElements.forEach(el => {
         const key = parseInt(el.dataset.key, 10);
         const groupId = state.toothToGroup.get(key);

         // default boja
         let fill = "#FFFFFF";

         if (groupId) {
            const group = state.groups.find(g => g.id === groupId);
            if (group) fill = group.color;
         }

         // ako je selektovan – privremeno siva boja
         if (state.selection.has(key)) {
            el.style.fill = "#B0B0B0";      // promena boje za selekciju
            el.style.stroke = "#000000";
            el.style.strokeWidth = "2px";
         } else {
            el.style.fill = fill;
            el.style.stroke = "none";
         }
      });
   }

   function handleToothClick(e) {
      const key = parseInt(this.dataset.key, 10);
      const isCtrl = e.ctrlKey || e.metaKey;
      const isShift = e.shiftKey;

      if (!isCtrl && !isShift) {
         // obican klik - nova selekcija
         state.selection.clear();
         state.selection.add(key);
      } else if (isCtrl && !isShift) {
         // CTRL – dodaj/ukloni jedan zub
         if (state.selection.has(key)) {
            state.selection.delete(key);
         } else {
            state.selection.add(key);
         }
      } else if (isShift && state.lastKey != null) {
         // SHIFT – selektuj sve izmedju lastKey i ovog
         const from = Math.min(state.lastKey, key);
         const to = Math.max(state.lastKey, key);
         state.selection.clear();
         toothOrder.forEach(num => {
            if (num >= from && num <= to) {
               state.selection.add(num);
            }
         });
      } else {
         // ako je SHIFT bez lastKey – tretiraj kao običan klik
         state.selection.clear();
         state.selection.add(key);
      }

      state.lastKey = key;
      updateTeethVisuals();
   }

   // dodajemo event listenere na zube
   toothElements.forEach(el => {
      el.style.cursor = "pointer";
      el.addEventListener("click", handleToothClick);
   });

   // primena grupe na selektovane zube

   const applyBtn = document.getElementById("applyToothGroup");
   const typeSelect = document.getElementById("exampleFormControlSelect1");
   const materialSelect = document.getElementById("countryOption");

   function getSelectedOption(select) {
      return {
         id: select.value,
         label: select.options[select.selectedIndex].text
      };
   }

   applyBtn.addEventListener("click", function () {
      if (state.selection.size === 0) {
         alert("Select at least one tooth first.");
         return;
      }

      const typeOfWork = getSelectedOption(typeSelect);
      const material = getSelectedOption(materialSelect);

      const groupIndex = state.groups.length;
      const color = state.colorPalette[groupIndex % state.colorPalette.length];
      const groupId = "g-" + Date.now() + "-" + groupIndex;

      const teethArray = Array.from(state.selection);

      // cistimo stare assigmente za ove zube
      teethArray.forEach(key => {
         const oldGroupId = state.toothToGroup.get(key);
         if (oldGroupId) {
            const oldGroup = state.groups.find(g => g.id === oldGroupId);
            if (oldGroup) {
               oldGroup.teeth = oldGroup.teeth.filter(t => t !== key);
            }
         }
         state.toothToGroup.set(key, groupId);
      });

      const newGroup = {
         id: groupId,
         color,
         typeOfWorkId: typeOfWork.id,
         typeOfWork: typeOfWork.label,
         materialId: material.id,
         material: material.label,
         teeth: teethArray,
         teethLabels: teethArray.map(k => toothLabelMap[k] ?? k)
      };

      state.groups.push(newGroup);
      console.log(newGroup); 
      state.selection.clear();
      updateTeethVisuals();
      renderGroupsPreview();
   });

   // slanje podataka na backend prilikom submit-a forme

   const form = document.querySelector(".work-page form");

   form.addEventListener("submit", function (e) {
      e.preventDefault();

      const nameOfWork = document.querySelector(".work-fields input[type='text']").value.trim();
      const skipPart = document.getElementById("skipPart").checked;
      const deliverWork = document.getElementById("deliverWork").checked;

      const payload = {
         nameOfWork,
         skipPart,
         deliverWork,
         groups: state.groups   // ovde imamo: id, color, typeOfWorkId, typeOfWork, materialId, material, teeth[], teethLabels[]
      };

      console.log("Payload to send:", payload);

      // slanje podataka na backend
      fetch("/api/work", {
         method: "POST",
         headers: {
            "Content-Type": "application/json"
         },
         body: JSON.stringify(payload)
      })
      .then(res => {
         if (!res.ok) throw new Error("Network response was not ok");
         return res.json();
      })
      .then(data => {
         console.log("Saved:", data);
         alert("Work saved successfully!");
         // opciono: redirect ili reset forme
      })
      .catch(err => {
         console.error(err);
         alert("Error while saving work.");
      });
   });