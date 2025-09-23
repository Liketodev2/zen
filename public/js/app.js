$(".owl-carousel").owlCarousel({
    loop: true,
    margin: 20,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive: {
        0: { items: 1 },
        576: { items: 2 },
        768: { items: 3 },
        992: { items: 4 },
        1200: { items: 5 },
    },
});

const toggleButtons = document.querySelectorAll(".toggle-password");
toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const passwordInput = this.previousElementSibling;
        const type =
            passwordInput.getAttribute("type") === "password"
                ? "text"
                : "password";
        passwordInput.setAttribute("type", type);

        const icon = this.querySelector("i");
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    });
});

// Tabs (Choosing Business Type)
const tabs = Array.from(document.querySelectorAll(".tab-item"));
const panes = Array.from(document.querySelectorAll(".tab-pane"));
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const confirmBtn = document.getElementById("confirmBtn");
let currentTab = 0;

function updateTab(index) {
    tabs.forEach((tab, i) => {
        tab.classList.toggle("active", i === index);
        tab.setAttribute("aria-selected", i === index);
    });

    panes.forEach((pane, i) => {
        pane.classList.toggle("show", i === index);
        pane.classList.toggle("active", i === index);
        pane.style.display = i === index ? "block" : "none";
    });

    prevBtn?.classList.toggle("d-none", index === 0);
    nextBtn?.classList.toggle("d-none", index === tabs.length - 1);
    confirmBtn?.classList.toggle("d-none", index !== tabs.length - 1);
}

tabs.forEach((tab, i) => {
    tab.addEventListener("click", () => {
        currentTab = i;
        updateTab(currentTab);
    });
});

nextBtn?.addEventListener("click", () => {
    if (currentTab < tabs.length - 1) {
        currentTab++;
        updateTab(currentTab);
    }
});

prevBtn?.addEventListener("click", () => {
    if (currentTab > 0) {
        currentTab--;
        updateTab(currentTab);
    }
});


updateTab(currentTab);

// Occupation
const occupationSelect = document.getElementById("occupationSelect");
const otherField = document.getElementById("otherOccupationField");
occupationSelect?.addEventListener("change", function () {
    otherField.style.display = this.value === "other" ? "flex" : "none";
});

// Visa
const visaSelect = document.getElementById("visaSelect");
const otherVisaInput = document.getElementById("otherVisaInput");

visaSelect?.addEventListener("change", function () {
    otherVisaInput.style.display = this.value === "other" ? "block" : "none";
});

// Toggle List Function
function initToggleList({
    containerSelector,
    itemSelector,
    toggleBtnSelector,
    visibleCount = 5,
    showMoreText = "Show More",
    showLessText = "Show Less",
}) {
    const toggleBtn = document.querySelector(toggleBtnSelector);
    const container = document.querySelector(containerSelector);
    if (!toggleBtn || !container) return;

    const items = container.querySelectorAll(itemSelector);
    let isExpanded = false;

    function updateView() {
        items.forEach((item, index) => {
            if (isExpanded || index < visibleCount) {
                item.classList.remove("hidden");
            } else {
                item.classList.add("hidden");
            }
        });
        toggleBtn.textContent = isExpanded ? showLessText : showMoreText;
    }

    toggleBtn.addEventListener("click", () => {
        isExpanded = !isExpanded;
        updateView();
    });

    updateView();
}

initToggleList({
    containerSelector: ".select_income_container",
    itemSelector: ".income-item",
    toggleBtnSelector: "#toggleBtnIncome",
    visibleCount: 10,
    showMoreText: "Show More",
    showLessText: "Show Less",
});

initToggleList({
    containerSelector: ".select_deduction_container1",
    itemSelector: ".deduction-item",
    toggleBtnSelector: "#toggleDeductionBtn",
    visibleCount: 10,
    showMoreText: "Show More",
    showLessText: "Show Less",
});

initToggleList({
    containerSelector: ".select_deduction_container2",
    itemSelector: ".other-details-item",
    toggleBtnSelector: "#toggleBtnOther",
    visibleCount: 10,
    showMoreText: "Show More",
    showLessText: "Show Less",
});
