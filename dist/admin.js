const btnShortcode = document.querySelector("button#copyShortcode");
const btncopyRestApiBaseUrl = document.querySelector(
  "button#copyRestApiBaseUrl"
);

const checkboxes = document.querySelectorAll("input[type=checkbox]");
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener("change", (ev) => {
    const checked = ev.currentTarget.checked;
    const tr = ev.currentTarget.closest("tr");
    const color = getComputedStyle(tr).backgroundColor;
    const settings = tr.nextElementSibling;
    settings.style.backgroundColor = checked ? color : "transparent";
    settings.classList.toggle("shown");
  });
});

const copyAssociatedValue = async (ev) => {
  ev.preventDefault();
  let text = "";
  const btn = ev.currentTarget;
  const btnText = btn.textContent;
  const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
  const resetBtn = () => {
    btn.textContent = btnText;
  };
  let node = btn.parentElement;
  while (node) {
    if (node.tagName === "LABEL") {
      const id = node.getAttribute("for");
      const input = node.querySelector(`#${id}`);
      text = input.value;
      break;
    }
    node = node.parentElement;
  }
  if (!text) return;
  try {
    if (navigator.clipboard) {
      btn.textContent = "En cours...";
      await navigator.clipboard.writeText(text);
    } else {
      btn.textContent = "Erreur !";
    }
    wait(500).then(resetBtn);
  } catch (e) {
    resetBtn();
    (e) => console.error(e);
  }
};

// Alterner la couleur des lignes des TABLE 'champs'
// (ça ne marche pas avec le CSS seul parce qu'il y a des TR masqués qui foutent le bordel)
const stripTable = () => {
  const tables = document.querySelectorAll("table.champs");
  tables.forEach((table) => {
    const trs = table.querySelectorAll("tr.field-info");
    trs.forEach((tr, idx) => {
      if (idx % 2 === 0) {
        tr.classList.add("even");
      } else {
        tr.classList.add("odd");
      }
    });
  });
};

document.addEventListener("DOMContentLoaded", () => {
  btnShortcode.addEventListener("click", copyAssociatedValue);
  btncopyRestApiBaseUrl.addEventListener("click", copyAssociatedValue);
  stripTable();
});
