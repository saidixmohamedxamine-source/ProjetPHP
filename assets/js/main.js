// Navigation Active State
document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function (e) {
    if (this.getAttribute("href") === "#") {
      e.preventDefault();
    }
    document
      .querySelectorAll(".nav-link")
      .forEach((l) => l.classList.remove("active"));
    this.classList.add("active");
  });
});

// Set default active nav link on load if none already exists
document.addEventListener("DOMContentLoaded", function () {
  const activeLink = document.querySelector(".nav-link.active");
  if (!activeLink) {
    const firstLink = document.querySelector(".nav-link");
    if (firstLink) {
      firstLink.classList.add("active");
    }
  }
});

// Quick Action Buttons
const requestHelpBtn = document.querySelector(".action-button.primary");
if (requestHelpBtn) {
  requestHelpBtn.addEventListener("click", function () {
    alert("Opening Request Help form...");
  });
}

const manageSkillsBtn = document.querySelectorAll(
  ".action-button.secondary",
)[0];
if (manageSkillsBtn) {
  manageSkillsBtn.addEventListener("click", function () {
    alert("Opening Manage Skills...");
  });
}

const findMentorsBtn = document.querySelector(".action-button.outline");
if (findMentorsBtn) {
  findMentorsBtn.addEventListener("click", function () {
    alert("Finding mentors...");
  });
}

// Offer Help Button
document.querySelectorAll(".offer-help-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    alert("Thank you for offering help!");
  });
});
