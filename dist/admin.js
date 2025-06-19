const btnShortcode = document.querySelector('button#copyShortcode');
const inputShortcode = document.querySelector('input#shortcode');

const copyShortcode = async () => {
  const btnText = btnShortcode.textContent;
  const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
  const resetBtn = () => {
    btnShortcode.textContent = btnText;
  };
  try {
    if (navigator.clipboard) {
      btnShortcode.textContent = "Processing...";
      await navigator.clipboard.writeText(inputShortcode.value);
    } else {
      btnShortcode.textContent = "Error!";
    }
    wait(500).then(resetBtn);
  } catch (e) {
    (e) => console.error(e);
  }
};

document.addEventListener('DOMContentLoaded', function() {

    btnShortcode.addEventListener('click', function(ev) {
        ev.preventDefault();
        copyShortcode();
    });

});