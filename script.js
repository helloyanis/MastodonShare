//Auto redirect ASAP for better performance
window.addEventListener('pageshow', function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === 'back_forward') {
        // User navigated back to the page so they probably don't want to be auto redirected again.
    }
    else if (localStorage.getItem("skip-confirm")) {
        // Skip confirmation, redirect immediately
        redirectToInstance();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Hide the no js warning
    document.getElementById("nojs").classList.add("hidden");
    mdui.setColorScheme("#6364FF")
    init()
    registerServiceWorker();
});

const registerServiceWorker = async () => {
  if ("serviceWorker" in navigator) {
    try {
      const registration = await navigator.serviceWorker.register("/worker.js", {
        scope: "/",
      });
      if (registration.installing) {
        console.log("Service worker installing");
      } else if (registration.waiting) {
        console.log("Service worker installed");
      } else if (registration.active) {
        console.log("Service worker active");
      }
    } catch (error) {
      console.error(`Registration failed with ${error}`);
    }
  }
};

function init() {
    //Updates the UI based on the presence of URL parameters and stored instance
    //Check if the text or URL parameter is present
    const urlParams = new URLSearchParams(window.location.search);
    const textParam = urlParams.get('text');
    const urlParam = urlParams.get('url');
    const instanceParam = urlParams.get('instance');

    if (textParam || urlParam) {
        showRedirectSection(textParam, urlParam, instanceParam);
    } else {
        showHomeSection();
    }

    handleForms()
}

function showHomeSection() {
    document.getElementById("home").classList.remove("hidden");
    const instanceInput = document.getElementById("current-instance");
    instanceInput.removeAttribute("disabled");
    instanceInput.setCustomValidity("");
    updateInstanceDisplay();
}
function showRedirectSection(text, url, instance) {
    let redirectURL = ""
    if (url) {
        text += `\n\n${url}`;
    }
    document.getElementById("redirect").classList.remove("hidden");
    document.getElementById("sharing-body").innerText = text || url || "No content provided";
    if (instance) {
        // Instance provided in URL parameter, use it
        redirectURL = `https://${instance}/share?text=${encodeURIComponent(text || "")}&url=${encodeURIComponent(url || "")}`;
        document.getElementById("sharing-instance").innerText = `You're about to share this on ${instance} :`;
        document.getElementById("go-to-instance-text").textContent = `Share on ${instance}`;
        document.getElementById("sharing-instance-form").classList.add("hidden");
        document.getElementById("sharing-instance-options").classList.remove("hidden");
    } else if (localStorage.getItem("instance")) {
        // No instance in URL parameter, use stored instance
        redirectURL = `https://${localStorage.getItem("instance")}/share?text=${encodeURIComponent(text || "")}&url=${encodeURIComponent(url || "")}`;
        document.getElementById("sharing-instance").innerText = `You're about to share this on ${localStorage.getItem("instance")} :`;
        document.getElementById("go-to-instance-text").textContent = `Share on ${localStorage.getItem("instance")}`;
        document.getElementById("sharing-instance-form").classList.add("hidden");
        document.getElementById("sharing-instance-options").classList.remove("hidden");
    } else {
        // No instance provided, ask the user
        document.getElementById("sharing-instance-form").classList.remove("hidden");
        document.getElementById("sharing-instance-options").classList.add("hidden");
    }
}

function updateInstanceDisplay() {
    const instanceInput = document.getElementById("current-instance");
    instanceInput.removeAttribute("disabled");
    if (localStorage.getItem("instance")) {
        instanceInput.value = localStorage.getItem("instance");
    }
}

function redirectToInstance() {
    const urlParams = new URLSearchParams(window.location.search);
    const textParam = urlParams.get('text');
    const urlParam = urlParams.get('url');
    const instanceParam = urlParams.get('instance');
    let redirectURL = ""
    if (instanceParam) {
        document.getElementById("go-to-instance-button").setAttribute("loading", "true");
        redirectURL = `https://${instanceParam}/share?text=${encodeURIComponent(textParam || "")}&url=${encodeURIComponent(urlParam || "")}`;
    } else if (localStorage.getItem("instance")) {
        document.getElementById("go-to-instance-button").setAttribute("loading", "true");
        redirectURL = `https://${localStorage.getItem("instance")}/share?text=${encodeURIComponent(textParam || "")}&url=${encodeURIComponent(urlParam || "")}`;
    }
    else {
        // No instance provided, do nothing
    }
    window.location.href = redirectURL;
}

function handleForms() {
    // Handle changes to the input fields

    // Instance field in home section
    const instanceInput = document.getElementById("current-instance");
    instanceInput.addEventListener("input", function () {
        localStorage.setItem("instance", instanceInput.value);
    });

    //Form to create a shareable link
    const copyShareLink = document.getElementById("copy-share-link");
    const messageBodyInput = document.getElementById("message-body");

    messageBodyInput.removeAttribute("disabled");

    messageBodyInput.addEventListener("input", function () {
        if (messageBodyInput.value.trim() === "") {
            copyShareLink.setAttribute("disabled", "true");
        } else {
            copyShareLink.removeAttribute("disabled");
        }
    });

    copyShareLink.addEventListener("click", async function () {
        const messageBody = document.getElementById("message-body").value;
        try {
            await navigator.clipboard.writeText(`${window.location.origin}/?text=${encodeURIComponent(messageBody)}`);
            mdui.snackbar(
                {
                    message: 'Share link copied to clipboard!'
                }
            );
        } catch (error) {
            mdui.snackbar(
                {
                    message: `Failed to copy share link to clipboard : ${error.message}`,
                }
            );
            console.error('Failed to copy text: ', error);
        }

    });

    if (localStorage.getItem("skip-confirm")) {
        document.getElementById("skip-confirm-checkbox").setAttribute("checked", "true");
        document.getElementById("skip-confirm-checkbox-sharing").setAttribute("checked", "true");
    }

    document.getElementById("skip-confirm-checkbox").addEventListener("change", function () {
        if (this.checked) {
            localStorage.setItem("skip-confirm", "true");
        } else {
            localStorage.removeItem("skip-confirm");
        }
    });

    document.getElementById("skip-confirm-checkbox-sharing").addEventListener("change", function () {
        if (this.checked) {
            localStorage.setItem("skip-confirm", "true");
        } else {
            localStorage.removeItem("skip-confirm");
        }
    });

    document.getElementById("set-sharing-instance").addEventListener("click", function () {
        const newInstance = document.getElementById("sharing-current-instance").value
        if (newInstance.trim() === "") {
            mdui.snackbar(
                {
                    message: 'Please enter a valid instance.',
                }
            );
            return;
        }
        else {
            localStorage.setItem("instance", newInstance);
            mdui.snackbar(
                {
                    message: `Instance set to ${newInstance}`,
                }
            );
            init();
            document.getElementById("sharing-instance").innerText = `You're about to share this on ${newInstance} :`;
        }
    });
    document.getElementById("change-instance").addEventListener("click", function () {
        localStorage.removeItem("instance");
        init();
    });
    document.getElementById("go-to-instance-button").addEventListener("click", function () {
        redirectToInstance();
    });
}