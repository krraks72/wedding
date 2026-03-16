document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".tab-button");
    const tabs = document.querySelectorAll(".tab-content");

    buttons.forEach(button => {

        button.addEventListener("click", function () {

            buttons.forEach(b => b.classList.remove("active"));
            tabs.forEach(t => t.classList.remove("active"));

            button.classList.add("active");

            const tab = button.dataset.tab;

            document
                .querySelector('.tab-content[data-tab="' + tab + '"]')
                .classList.add("active");

        });

    });

});