document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('button#plugin-message-button');
    const input = document.querySelector('input#plugin-message');

    btn.addEventListener('click', function(ev) {
        ev.preventDefault();
        console.log(input.value);
    });

});