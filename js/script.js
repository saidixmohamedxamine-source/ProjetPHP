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
    const requestTitle =
      this.closest(".request-card").querySelector(".request-title").textContent;
    alert(`Offering help with: ${requestTitle}`);
  });
});

// View All Button
const viewAllBtn = document.querySelector(".view-all-btn");
if (viewAllBtn) {
  viewAllBtn.addEventListener("click", function () {
    alert("Viewing all requests...");
  });
}

const createRequestNavButton = document.querySelector(".create-request-button");
if (createRequestNavButton) {
  createRequestNavButton.addEventListener("click", function () {
    window.location.href = "create-request.html";
  });
}

const filterButton = document.querySelector(".filter-button");
if (filterButton) {
  filterButton.addEventListener("click", function () {
    alert("Filter options will open here.");
  });
}

// Mobile Menu Toggle
const sidebar = document.querySelector(".sidebar");
const menuToggle = document.getElementById("menu-toggle");

if (menuToggle) {
  menuToggle.addEventListener("click", function () {
    sidebar.classList.toggle("active");
  });
}

// Close sidebar when clicking outside on mobile
document.addEventListener("click", function (event) {
  const isClickInsideSidebar = sidebar.contains(event.target);
  const isClickOnMenuToggle = menuToggle && menuToggle.contains(event.target);

  if (
    !isClickInsideSidebar &&
    !isClickOnMenuToggle &&
    window.innerWidth <= 768
  ) {
    sidebar.classList.remove("active");
  }
});

// Responsive handling
window.addEventListener("resize", function () {
  if (window.innerWidth > 768) {
    sidebar.classList.remove("active");
  }
});

// Update active nav link based on current page
function setActiveNavLink(linkText) {
  document.querySelectorAll(".nav-link").forEach((link) => {
    if (link.textContent.trim() === linkText) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
}

// Profile Page Interactions
const editProfileBtn = document.querySelector(".edit-profile-btn");
if (editProfileBtn) {
  editProfileBtn.addEventListener("click", function () {
    alert("Opening Edit Profile form...");
  });
}

const manageProfileSkillsBtn = document.querySelector(".manage-skills-btn");
if (manageProfileSkillsBtn) {
  manageProfileSkillsBtn.addEventListener("click", function () {
    alert("Opening Manage Skills panel...");
  });
}

const addSkillBtn = document.querySelector(".add-skill-btn");
if (addSkillBtn) {
  addSkillBtn.addEventListener("click", function () {
    alert("Opening Add Skill form...");
  });
}

document.querySelectorAll(".edit-skill").forEach((btn) => {
  btn.addEventListener("click", function () {
    const title =
      this.closest(".skill-card").querySelector(
        ".skill-card-title",
      ).textContent;
    alert(`Edit ${title}`);
  });
});

document.querySelectorAll(".delete-skill").forEach((btn) => {
  btn.addEventListener("click", function () {
    const title =
      this.closest(".skill-card").querySelector(
        ".skill-card-title",
      ).textContent;
    alert(`Delete ${title}`);
  });
});

const createRequestPage = document.querySelector(".create-request-page");
if (createRequestPage) {
  const submitButton = document.querySelector(".submit-request-button");
  const cancelButton = document.querySelector(".cancel-request-button");
  const addTagButton = document.querySelector(".tag-button");
  const tagInput = document.getElementById("request-tags");
  const tagList = document.getElementById("tag-list");

  function addTag() {
    const value = tagInput.value.trim();
    if (!value) return;

    const pill = document.createElement("span");
    pill.className = "tag-pill";
    pill.innerHTML = `${value} <button type="button">×</button>`;

    pill.querySelector("button").addEventListener("click", () => {
      pill.remove();
    });

    tagList.appendChild(pill);
    tagInput.value = "";
  }

  if (addTagButton) {
    addTagButton.addEventListener("click", addTag);
  }

  if (tagInput) {
    tagInput.addEventListener("keydown", function (event) {
      if (event.key === "Enter") {
        event.preventDefault();
        addTag();
      }
    });
  }

  if (submitButton) {
    submitButton.addEventListener("click", function () {
      alert("Help request submitted!");
    });
  }

  if (cancelButton) {
    cancelButton.addEventListener("click", function () {
      window.location.href = "index.html";
    });
  }
}

// Search Page Buttons
const requestHelpButtons = document.querySelectorAll(".request-help-button");
requestHelpButtons.forEach((btn) => {
  btn.addEventListener("click", function () {
    alert("Request sent to mentor!");
  });
});

const viewProfileButtons = document.querySelectorAll(".view-profile-button");
viewProfileButtons.forEach((btn) => {
  btn.addEventListener("click", function () {
    alert("Opening mentor profile...");
  });
});

const clearFiltersButton = document.querySelector(".clear-filters-button");
if (clearFiltersButton) {
  clearFiltersButton.addEventListener("click", function () {
    document.querySelectorAll(".filter-tag").forEach((tag) => {
      tag.classList.remove("active");
    });
    document.querySelectorAll(".form-select").forEach((select) => {
      select.selectedIndex = 0;
    });
    alert("Filters cleared.");
  });
}
